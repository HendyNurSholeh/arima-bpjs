<?php

namespace App\Controllers;

use App\Models\DataFisikModel;
use App\Models\DataMetabolitikModel;
use App\Models\PasienModel;
use App\Models\StatusHipertensiModel;
use Config\Database;
use Phpml\Classification\NaiveBayes;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends BaseController
{
    public function dashboard(): string
    {
        return view('admin/dashboard');
    }
    
    public function dataset(): string
    {
        $pasienModel = new PasienModel();
        $dataMetabolikModel = new DataMetabolitikModel();
        $dataFisikModel = new DataFisikModel();
        $statusHipertensiModel = new StatusHipertensiModel();

        $data = $pasienModel
            ->select('pasien.*, data_metabolik.*, data_fisik.*, status_hipertensi.*')
            ->join('data_metabolik', 'data_metabolik.id_pasien = pasien.id_pasien')
            ->join('data_fisik', 'data_fisik.id_pasien = pasien.id_pasien')
            ->join('status_hipertensi', 'status_hipertensi.id_pasien = pasien.id_pasien')
            ->findAll();

        return view('admin/dataset', ['data' => $data]);
    }
   
    public function import()
    {
        $file = $this->request->getFile('file');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads', $newName);
            $filePath = "uploads/{$newName}";

            // Process the file (e.g., read Excel data)
            $spreadsheet = IOFactory::load($filePath);
            $data = $spreadsheet->getActiveSheet()->toArray();

            // Remove the header row
            array_shift($data);
            array_shift($data);

            // Filter out empty rows
            $data = array_filter($data, function($row) {
                return array_filter($row);
            });

            // Prepare data for batch insert
            $pasienData = [];
            $dataMetabolikData = [];
            $dataFisikData = [];
            $statusHipertensiData = [];

            foreach ($data as $row) {
                $pasienData[] = [
                    'nama' => $row[1],
                    'umur' => $row[2],
                ];
            }
            // Insert data into the database using batch insert within a transaction
            $db = Database::connect();
            $pasienModel = new PasienModel();
            $dataMetabolikModel = new DataMetabolitikModel();
            $dataFisikModel = new DataFisikModel();
            $statusHipertensiModel = new StatusHipertensiModel();

            // Disable foreign key checks
            $db->disableForeignKeyChecks();

            // Truncate existing data
            $pasienModel->truncate();
            $dataMetabolikModel->truncate();
            $dataFisikModel->truncate();
            $statusHipertensiModel->truncate();

            // Enable foreign key checks
            $db->enableForeignKeyChecks();

            // Insert new data and get inserted IDs
            $pasienModel->insertBatch($pasienData);
            $insertedIds = $pasienModel->insertID();
            foreach ($data as $index => $row) {
                $id_pasien = $index + 1;
                $dataMetabolikData[] = [
                    'id_pasien' => $id_pasien,
                    'tekanan_darah' => $row[3],
                    'gula_darah' => $row[4],
                    'asam_urat' => str_replace(',', '.', $row[5]), // Convert comma to dot
                    'kolesterol' => $row[6],
                ];
                $dataFisikData[] = [
                    'id_pasien' => $id_pasien,
                    'berat_badan' => str_replace(',', '.', $row[7]), // Convert comma to dot
                    'tinggi_badan' => $row[8],
                    'lingkar_perut' => $row[9],
                ];
                $statusHipertensiData[] = [
                    'id_pasien' => $id_pasien,
                    'status' => $row[10],
                ];
            }

            // Insert new data
            $dataMetabolikModel->insertBatch($dataMetabolikData);
            $dataFisikModel->insertBatch($dataFisikData);
            $statusHipertensiModel->insertBatch($statusHipertensiData);

            return redirect()->to('/admin/dataset')->with('message', 'File imported and data saved to database successfully.');
        }

        return redirect()->to('/admin/dataset')->with('error', 'Failed to import file.');
    }
    public function peforma(): string
    {
        $dataPercentage = $this->request->getGet('data_percentage') ?? 70;
        $pasienModel = new PasienModel();

        // Ambil semua data pasien
        $data = $pasienModel
        ->select('pasien.*, data_metabolik.*, data_fisik.*, status_hipertensi.*')
        ->join('data_metabolik', 'data_metabolik.id_pasien = pasien.id_pasien')
        ->join('data_fisik', 'data_fisik.id_pasien = pasien.id_pasien')
        ->join('status_hipertensi', 'status_hipertensi.id_pasien = pasien.id_pasien')
        ->findAll();

        // Hitung jumlah data testing dan training
        $totalData = count($data);
        $testingDataCount = ($totalData * $dataPercentage) / 100;
        $trainingDataCount = $totalData - $testingDataCount;

        // Acak data
        shuffle($data);

        // Bagi data menjadi data training dan testing
        $dataTesting = array_slice($data, 0, $testingDataCount);
        $dataTraining = array_slice($data, $testingDataCount, $trainingDataCount);

        // Implementasi algoritma Naive Bayes
        $naiveBayes = new NaiveBayes();

        // Prepare training data
        $samples = [];
        $labels = [];
        foreach ($dataTraining as $item) {
            $samples[] = [
                $item['tekanan_darah'],
                $item['gula_darah'],
                $item['asam_urat'],
                $item['kolesterol'],
                $item['berat_badan'],
                $item['tinggi_badan'],
                $item['lingkar_perut']
            ];
            $labels[] = $item['status'];
        }
        $naiveBayes->train($samples, $labels);

        // Evaluasi performa
        $correct = 0;
        $truePositive = $falsePositive = $trueNegative = $falseNegative = 0;
        foreach ($dataTesting as $item) {
            $predicted = $naiveBayes->predict([
                $item['tekanan_darah'],
                $item['gula_darah'],
                $item['asam_urat'],
                $item['kolesterol'],
                $item['berat_badan'],
                $item['tinggi_badan'],
                $item['lingkar_perut']
            ]);
            if ($predicted == $item['status']) {
                $correct++;
                switch ($predicted) {
                    case 'positive':
                        $truePositive++;
                        break;
                    default:
                        $trueNegative++;
                        break;
                }
            } else {
                switch ($predicted) {
                    case 'positive':
                        $falsePositive++;
                        break;
                    default:
                        $falseNegative++;
                        break;
                }
            }
        }
        $accuracy = ($correct / count($dataTesting)) * 100;
        $precision = ($truePositive + $falsePositive) ? ($truePositive / ($truePositive + $falsePositive)) * 100 : 0;
        $recall = ($truePositive + $falseNegative) ? ($truePositive / ($truePositive + $falseNegative)) * 100 : 0;
        $results = [
            'accuracy' => $accuracy,
            'precision' => $precision,
            'recall' => $recall
        ];
        // dd($results);

        return view('admin/peforma', [
            'data' => $data,
            'dataTesting' => $dataTesting,
            'dataTraining' => $dataTraining,
            'dataPercentage' => $dataPercentage,
            'results' => $results,
        ]);
    }
    public function prediksi(): string
    {
        return view('admin/prediksi');
    }
}