<?php

namespace App\Controllers;

use App\Models\DataFisikModel;
use App\Models\DataMetabolitikModel;
use App\Models\DatasetModel;
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
        $datasetModel = new DatasetModel();

        $data = $datasetModel->findAll();

        return view('admin/dataset', ['dataset' => $data]);
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

            // Remove the header row(s)
            array_shift($data);

            // Filter out empty rows
            $data = array_filter($data, function($row) {
                return array_filter($row);
            });

            // Prepare data for batch insert to dataset table
            $datasetData = [];
            foreach ($data as $row) {
                // Ubah format tanggal dari d/m/Y ke Y-m-d
                $tanggal = \DateTime::createFromFormat('d/m/Y', $row[1]);
                $formattedTanggal = $tanggal ? $tanggal->format('Y-m-d') : null;

                $datasetData[] = [
                    'tanggal' => $formattedTanggal,
                    'jumlah_pendaftar' => $row[2],
                    'bulan' => $row[3],
                ];
            }

            $db = Database::connect();
            $datasetModel = new \App\Models\DatasetModel();

            // Disable foreign key checks if needed
            $db->disableForeignKeyChecks();

            // Enable foreign key checks
            $db->enableForeignKeyChecks();

            // Insert new data
            if (!empty($datasetData)) {
                $datasetModel->insertBatch($datasetData);
            }

            return redirect()->to('/admin/dataset')->with('success', 'File berhasil diimpor dan data berhasil disimpan ke database.');
        }

        return redirect()->to('/admin/dataset')->with('error', 'Gagal mengimpor file.');
    }

    public function clear()
    {
        $datasetModel = new DatasetModel();
        $datasetModel->truncate();

        return redirect()->to('/admin/dataset')->with('success', 'Semua data berhasil dihapus.');
    }

    public function peforma(): string
    {
        $datasetModel = new DatasetModel();

        // Ambil data aktual (misal 10 data terakhir)
        $dataAktual = $datasetModel
            ->orderBy('tanggal', 'DESC')
            ->findAll(10);

        // Balik urutan agar tanggal lama ke baru
        $dataAktual = array_reverse($dataAktual);

        // Siapkan data untuk prediksi (misal gunakan ARIMA sederhana manual)
        // Di sini, prediksi = rata-rata 3 data sebelumnya (moving average) sebagai contoh
        $aktual = [];
        $prediksi = [];
        $tanggal = [];
        $errors = [];

        foreach ($dataAktual as $i => $row) {
            $aktual[] = (int)$row['jumlah_pendaftar'];
            $tanggal[] = date('d/m/Y', strtotime($row['tanggal']));
            if ($i < 3) {
                // Untuk 3 data pertama, prediksi = aktual (atau null)
                $prediksi[] = (int)$row['jumlah_pendaftar'];
            } else {
                $pred = round((
                    $dataAktual[$i-1]['jumlah_pendaftar'] +
                    $dataAktual[$i-2]['jumlah_pendaftar'] +
                    $dataAktual[$i-3]['jumlah_pendaftar']
                ) / 3);
                $prediksi[] = (int)$pred;
            }
            $errors[] = abs($aktual[$i] - $prediksi[$i]);
        }

        // Hitung MAE, MSE, RMSE, MAPE
        $n = count($aktual);
        $mae = $mse = $mape = 0;
        foreach ($aktual as $i => $val) {
            $mae += abs($val - $prediksi[$i]);
            $mse += pow($val - $prediksi[$i], 2);
            $mape += $val != 0 ? abs(($val - $prediksi[$i]) / $val) : 0;
        }
        $mae = $n ? $mae / $n : 0;
        $mse = $n ? $mse / $n : 0;
        $rmse = sqrt($mse);
        $mape = $n ? ($mape / $n) * 100 : 0;

        // Siapkan data untuk view
        $summary = [
            'mae' => round($mae),
            'mse' => round($mse),
            'rmse' => round($rmse),
            'mape' => round($mape, 2)
        ];

        $detail = [];
        foreach ($dataAktual as $i => $row) {
            $detail[] = [
                'no' => $i + 1,
                'tanggal' => $tanggal[$i],
                'aktual' => number_format($aktual[$i], 0, ',', '.'),
                'prediksi' => number_format($prediksi[$i], 0, ',', '.'),
                'error' => number_format($errors[$i], 0, ',', '.')
            ];
        }

        return view('admin/peforma', [
            'summary' => $summary,
            'detail' => $detail,
            'labels' => $tanggal,
            'aktual' => $aktual,
            'prediksi' => $prediksi
        ]);
    }
    public function prediksi(): string
    {
        return view('admin/prediksi');
    }
}