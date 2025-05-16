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
            // Ambil semua tanggal dari data Excel
            $tanggalList = [];
            foreach ($data as $row) {
                $tanggal = \DateTime::createFromFormat('d/m/Y', $row[1]);
                if ($tanggal) {
                    $tanggalList[] = $tanggal->format('Y-m-d');
                }
            }

            // Cari rentang tanggal minimum dan maksimum
            $minTanggal = min($tanggalList);
            $maxTanggal = max($tanggalList);

            // Buat array semua tanggal dari min ke max
            $allDates = [];
            $start = new \DateTime($minTanggal);
            $end = new \DateTime($maxTanggal);
            while ($start <= $end) {
                $allDates[] = $start->format('Y-m-d');
                $start->modify('+1 day');
            }

            // Index data Excel berdasarkan tanggal
            $excelDataByDate = [];
            foreach ($data as $row) {
                $tanggal = \DateTime::createFromFormat('d/m/Y', $row[1]);
                $formattedTanggal = $tanggal ? $tanggal->format('Y-m-d') : null;
                if ($formattedTanggal) {
                    $excelDataByDate[$formattedTanggal] = [
                        'jumlah_pendaftar' => $row[2],
                        'bulan' => $row[3],
                    ];
                }
            }

            // Susun datasetData, isi 0 jika tanggal tidak ada di Excel
            foreach ($allDates as $tgl) {
                $jumlah = isset($excelDataByDate[$tgl]) ? $excelDataByDate[$tgl]['jumlah_pendaftar'] : 0;
                $bulan = isset($excelDataByDate[$tgl]) ? $excelDataByDate[$tgl]['bulan'] : date('F', strtotime($tgl));
                $datasetData[] = [
                    'tanggal' => $tgl,
                    'jumlah_pendaftar' => $jumlah,
                    'bulan' => $bulan,
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
    // Prediksi manual moving average per tanggal, tanpa library eksternal
    public function prediksi(): string
    {
        $datasetModel = new DatasetModel();

        $prediksiData = [];
        $summary = [
            'total' => 0,
            'avg' => 0,
            'min' => 0,
            'max' => 0
        ];
        $labels = [];
        $jumlahPrediksi = [];

        if ($this->request->getGet('tanggal_mulai') && $this->request->getGet('tanggal_akhir')) {
            $tanggalMulai = $this->request->getGet('tanggal_mulai');
            $tanggalAkhir = $this->request->getGet('tanggal_akhir');

            // Ambil data historis sampai tanggal terakhir sebelum tanggal mulai prediksi
            $historis = $datasetModel
                ->where('tanggal <', $tanggalMulai)
                ->orderBy('tanggal', 'ASC')
                ->findAll();

            // Buat periode tanggal prediksi
            $period = [];
            $start = new \DateTime($tanggalMulai);
            $end = new \DateTime($tanggalAkhir);
            while ($start <= $end) {
                $period[] = clone $start;
                $start->modify('+1 day');
            }

            // Siapkan data historis untuk moving average (hanya jumlah_pendaftar)
            $series = [];
            foreach ($historis as $row) {
                $series[] = (int)$row['jumlah_pendaftar'];
            }

            // Prediksi manual moving average (window 3 hari)
            $window = 3;
            $prediksi = [];
            $seriesPrediksi = $series; // Copy data historis untuk menambah prediksi ke belakang

            // Pastikan data historis tidak kosong, jika kosong isi dengan 0 agar prediksi tidak selalu 0
            if (empty($seriesPrediksi)) {
                $seriesPrediksi = [0, 0, 0];
            }

            foreach ($period as $i => $dt) {
                $countSeries = count($seriesPrediksi);
                if ($countSeries >= $window) {
                    // Ambil rata-rata window terakhir
                    $sum = 0;
                    for ($j = $countSeries - $window; $j < $countSeries; $j++) {
                        $sum += $seriesPrediksi[$j];
                    }
                    $avg = round($sum / $window);
                } elseif ($countSeries > 0) {
                    // Jika data kurang dari window, rata-rata seluruh data
                    $avg = round(array_sum($seriesPrediksi) / $countSeries);
                } else {
                    $avg = 0;
                }
                $prediksi[] = $avg;
                $seriesPrediksi[] = $avg; // Tambahkan hasil prediksi ke deret untuk prediksi berikutnya
            }

            // Siapkan data untuk tabel dan chart
            $prediksiData = [];
            $total = 0;
            $min = null;
            $max = null;
            foreach ($period as $i => $dt) {
                $tgl = $dt->format('d/m/Y');
                $bulan = $dt->format('F');
                $jumlah = $prediksi[$i] ?? 0;
                $prediksiData[] = [
                    'no' => $i+1,
                    'tanggal' => $tgl,
                    'bulan' => $bulan,
                    'jumlah' => number_format($jumlah, 0, ',', '.')
                ];
                $labels[] = $tgl;
                $jumlahPrediksi[] = $jumlah;
                $total += $jumlah;
                $min = $min === null ? $jumlah : min($min, $jumlah);
                $max = $max === null ? $jumlah : max($max, $jumlah);
            }
            $avg = count($jumlahPrediksi) ? $total / count($jumlahPrediksi) : 0;

            $summary = [
                'total' => number_format($total, 0, ',', '.'),
                'avg' => number_format($avg, 0, ',', '.'),
                'min' => number_format($min, 0, ',', '.'),
                'max' => number_format($max, 0, ',', '.')
            ];
        }

        return view('admin/prediksi', [
            'prediksiData' => $prediksiData,
            'summary' => $summary,
            'labels' => $labels,
            'jumlahPrediksi' => $jumlahPrediksi
        ]);
    }

    public function akun(): string
    {
        return view('admin/akun');
    }

    public function reset_password()
    {
        $passwordBaru = $this->request->getPost('password_baru');
        $passwordKonfirmasi = $this->request->getPost('password_konfirmasi');
    
        if ($passwordBaru !== $passwordKonfirmasi) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }
    
        if (strlen($passwordBaru) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }
    
        // Misal user admin hanya satu, update password di tabel users/admin
        $userModel = new \App\Models\AkunModel();
        $admin = $userModel->where('username', 'fadli')->first();
    
        if (!$admin) {
            return redirect()->back()->with('error', 'Akun admin tidak ditemukan.');
        }
    
        $userModel->update($admin['id_akun'], [
            'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset.');
    }

    
}