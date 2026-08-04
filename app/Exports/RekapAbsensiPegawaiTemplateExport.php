<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAbsensiPegawaiTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Berikan 1 contoh baris pengisian data
        return collect([
            [
                '2026-10-01',
                '198001012010011001',
                '07:00',
                '16:00',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Tanggal (YYYY-MM-DD)',
            'NIP Pegawai',
            'Jam Datang (HH:MM) *Kosongkan jika absen*',
            'Jam Pulang (HH:MM) *Kosongkan jika absen*',
        ];
    }
}
