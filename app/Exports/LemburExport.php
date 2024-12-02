<?php

namespace App\Exports;

use App\Models\Lembur;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LemburExport implements FromView, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $namaLengkap;

    public function __construct($startDate, $endDate, $namaLengkap)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->namaLengkap = $namaLengkap;
    }

    public function view(): View
    {
        $lemburData = Lembur::select('lembur.*', 'karyawan.jabatan', 'karyawan.gaji')
                            ->join('karyawan', 'lembur.nama_lengkap', '=', 'karyawan.nama_karyawan')
                            ->whereBetween('lembur.tanggal_lembur', [$this->startDate, $this->endDate])
                            ->where('lembur.nama_lengkap', 'like', '%' . $this->namaLengkap . '%')
                            ->get();

        foreach ($lemburData as $lembur) {
            $gaji = (float) ($lembur->gaji ?? 0);
            $lembur->upahLemburPerJam = $gaji > 0 ? round($gaji / 173, 2) : 0;
        }

        return view('excelPerhitunganLembur', [
            'lemburData' => $lemburData,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'namaLengkap' => $this->namaLengkap
        ]);
    }

    public function styles(Worksheet $sheet)
{
    // Apply Book Antiqua font and size 9 to the entire sheet
    $sheet->getStyle($sheet->calculateWorksheetDimension())
          ->applyFromArray([
              'font' => [
                  'name' => 'Book Antiqua',
                  'size' => 9,  // Set font size to 9
              ],
          ]);

    // Apply alignment and borders only to the table range
    $tableRange = 'A7:Q' . ($sheet->getHighestRow()); // Adjust range as needed
    $sheet->getStyle($tableRange)->applyFromArray([
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ]);

    // Format column P to follow the Indonesian number format (e.g., 1.234,56)
    $sheet->getStyle('P7:P' . $sheet->getHighestRow())->getNumberFormat()
          ->setFormatCode('#,##0');

    return $sheet;
}



}
