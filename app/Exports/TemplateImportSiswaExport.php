<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Laravolt\Indonesia\Models\Province;

class TemplateImportSiswaExport implements WithMultipleSheets
{
    protected $withData;

    public function __construct($withData = false)
    {
        $this->withData = $withData;
    }

    public function sheets(): array
    {
        return [
            'Template'  => new MainTemplateSheet($this->withData),
            'Referensi' => new ReferenceDataSheet(),
        ];
    }
}

class MainTemplateSheet implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    protected $withData;

    public function __construct($withData = false)
    {
        $this->withData = $withData;
    }

    public function title(): string
    {
        return 'Template';
    }

    public function array(): array
    {
        if (!$this->withData) {
            // 28 kolom
            return array_fill(0, 5, array_fill(0, 28, ''));
        }

        $siswaList = \App\Models\Siswa::with('wali')->orderBy('nama_lengkap', 'asc')->get();
        
        if ($siswaList->isEmpty()) {
            return array_fill(0, 5, array_fill(0, 28, ''));
        }

        $data = [];
        foreach ($siswaList as $s) {
            $ayah = $s->wali->firstWhere('pivot.hubungan', 'Ayah');
            $ibu = $s->wali->firstWhere('pivot.hubungan', 'Ibu');
            $wali = $s->wali->firstWhere('pivot.hubungan', 'Wali');

            $data[] = [
                $s->nama_lengkap, 
                $s->nik, 
                $s->nipd, 
                $s->nisn, 
                $s->jenis_kelamin, 
                $s->tempat_lahir, 
                $s->tanggal_lahir ? $s->tanggal_lahir->format('d/m/Y') : '', 
                $s->agama, 
                $s->nomor_hp, 
                $s->asal_sekolah, 
                $s->no_peserta_un,
                $s->provinsi, 
                $s->kota, 
                $s->kecamatan, 
                $s->kelurahan_desa, 
                $s->alamat_lengkap, 
                $s->rt, 
                $s->rw, 
                $s->kode_pos, 
                $s->tingkat, 
                $s->diterima_pada_tanggal ? $s->diterima_pada_tanggal->format('d/m/Y') : '', 
                $s->anak_ke,
                
                $ayah->nama_lengkap ?? '', 
                $ayah->pekerjaan ?? '',
                
                $ibu->nama_lengkap ?? '', 
                $ibu->pekerjaan ?? '',
                
                $wali->nama_lengkap ?? '', 
                $wali->pekerjaan ?? ''
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            // Data Utama Siswa (Kolom A - V)
            'nama_lengkap', 'nik', 'nipd', 'nisn', 'jenis_kelamin', 
            'tempat_lahir', 'tanggal_lahir', 'agama', 'nomor_hp', 
            'asal_sekolah', 'no_peserta_un', // 🆕 Tambah di sini
            'provinsi', 'kota', 'kecamatan', 'kelurahan_desa', 'alamat_lengkap', 
            'rt', 'rw', 'kode_pos', 'tingkat', 'diterima_pada_tanggal', 'anak_ke',
            
            // Data Orang Tua / Wali (Kolom W - AB)
            'ayah_nama', 'ayah_pekerjaan',
            'ibu_nama', 'ibu_pekerjaan',
            'wali_nama', 'wali_pekerjaan'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // ---- DROPDOWN JENIS KELAMIN (Kolom E) ----
                $validationJK = $sheet->getCell('E2')->getDataValidation();
                $validationJK->setType(DataValidation::TYPE_LIST);
                $validationJK->setErrorStyle(DataValidation::STYLE_STOP);
                $validationJK->setAllowBlank(false);
                $validationJK->setShowDropDown(true);
                $validationJK->setFormula1('"Laki-laki,Perempuan"');

                // ---- DROPDOWN PROVINSI (Kolom L - Bergeser karena ada kolom baru) ----
                $totalProvinces = Province::count();
                $validationProv = $sheet->getCell('L2')->getDataValidation(); // Kolom K bergeser ke L
                $validationProv->setType(DataValidation::TYPE_LIST);
                $validationProv->setErrorStyle(DataValidation::STYLE_STOP);
                $validationProv->setAllowBlank(true);
                $validationProv->setShowDropDown(true);
                $validationProv->setFormula1("Referensi!\$A\$1:\$A\$" . $totalProvinces);

                // Duplikasi dropdown untuk seluruh baris (minimal 100 baris atau sesuai jumlah data)
                $rowCount = count($sheet->toArray());
                $maxRow = max($rowCount, 100);
                
                for ($i = 2; $i <= $maxRow; $i++) {
                    $sheet->getCell("E{$i}")->setDataValidation(clone $validationJK);
                    $sheet->getCell("L{$i}")->setDataValidation(clone $validationProv);
                }
            },
        ];
    }
}

class ReferenceDataSheet implements FromArray, ShouldAutoSize, WithTitle
{
    public function title(): string
    {
        return 'Referensi';
    }

    public function array(): array
    {
        $provinces = Province::orderBy('name', 'asc')->pluck('name')->toArray();
        return array_map(function($name) {
            return [ucwords(strtolower($name))];
        }, $provinces);
    }
}