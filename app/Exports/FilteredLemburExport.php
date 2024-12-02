<?php

namespace App\Exports;

use App\Models\Lembur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Http\Request;

class FilteredLemburExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;
    protected $counter = 1; // Initialize counter

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Lembur::query();

        // Apply filters
        if ($this->request->filled('nama_lengkap_excel')) {
            $query->where('nama_lengkap', 'like', '%' . $this->request->nama_lengkap_excel . '%');
        }

        if ($this->request->filled('start_date_excel') && $this->request->filled('end_date_excel')) {
            $query->whereBetween('tanggal_lembur', [
                $this->request->start_date_excel, 
                $this->request->end_date_excel
            ]);
        }

        $lemburRecords = $query->get();

        // Group records by nama_lengkap and aggregate totals
        $groupedRecords = $lemburRecords->groupBy('nama_lengkap')->map(function ($group) {
            return [
                'nama_lengkap' => $group->first()->nama_lengkap, // Use the first record's nama_lengkap
                'jam_kerja_lembur' => $group->sum('jam_kerja_lembur'),
                'upah_lembur' => $group->sum('upah_lembur'),
            ];
        })->values(); // Use values() to get a collection of the aggregated records

        // Calculate totals for footer
        $totalJamKerjaLembur = $groupedRecords->sum('jam_kerja_lembur');
        $totalUpahLembur = $groupedRecords->sum('upah_lembur');

        // Add totals to the collection
        $groupedRecords->push([
            'nama_lengkap' => 'Total',
            'jam_kerja_lembur' => $totalJamKerjaLembur,
            'upah_lembur' => $totalUpahLembur,
        ]);

        return $groupedRecords;
    }

    public function headings(): array
    {
        $startDate = $this->request->start_date_excel 
        ? \Carbon\Carbon::parse($this->request->start_date_excel)
            ->locale('id') // Set locale to Indonesian
            ->translatedFormat('d F Y') 
        : '';
    
        $endDate = $this->request->end_date_excel 
            ? \Carbon\Carbon::parse($this->request->end_date_excel)
                ->locale('id') // Set locale to Indonesian
                ->translatedFormat('d F Y') 
            : '';
        
        $dateRange = $startDate && $endDate ? $startDate . ' - ' . $endDate : '';
    
        return [
            ['PT. Tolan Tiga Indonesia'], // Title row
            ['HRD'], // Subtitle row
            ['Daftar Pembayaran Lembur Non Staff'], // Subtitle row
            ['Di Bayarkan Perusahaan'], // Subtitle row
            [$dateRange], // Date range row
            [], // Empty row for spacing
            ['No', 'Nama', 'Jumlah', ''], // Table headings
            ['', '', 'Jam', 'Rp.'], // Sub-headings
        ];
    }

    public function map($data): array
    {
        // Skip counter increment for 'Total' row
        if ($data['nama_lengkap'] === 'Total') {
            return [
                '', // Empty for 'No' column
                $data['nama_lengkap'],
                number_format($data['jam_kerja_lembur'], 1),
                'Rp. ' . number_format($data['upah_lembur'], 0, ',', '.'),
            ];
        }

        // Return data with the incremented counter
        return [
            $this->counter++, // Increment counter
            $data['nama_lengkap'],
            number_format($data['jam_kerja_lembur'], 1),
            'Rp. ' . number_format($data['upah_lembur'], 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $cellRange = 'A1:' . $highestColumn . $highestRow;
    
        // Apply font style to the entire sheet
        $sheet->getStyle($cellRange)
              ->getFont()
              ->setName('Book Antiqua')
              ->setSize(12);
    
        // Center align all cells
        $sheet->getStyle($cellRange)
              ->getAlignment()
              ->setHorizontal(Alignment::HORIZONTAL_CENTER)
              ->setVertical(Alignment::VERTICAL_CENTER);
    
        // Apply borders to all cells
        $sheet->getStyle('A7:' . $highestColumn . $highestRow)
              ->getBorders()
              ->getAllBorders()
              ->setBorderStyle(Border::BORDER_THIN);
    
        // Apply custom style to title rows
        $sheet->getStyle('A1:A5')
              ->getFont()
              ->setSize(12)
              ->setBold(true);
    
        $sheet->getStyle('A7:D8')
              ->getFont()
              ->setSize(12)
              ->setBold(true);
    
        // Merge cells for title and subtitle
        $sheet->mergeCells('A1:D1'); // PT. Tolan Tiga Indonesia
        $sheet->mergeCells('A2:D2'); // HRD
        $sheet->mergeCells('A3:D3'); // Daftar Pembayaran Lembur Non Staff
        $sheet->mergeCells('A4:D4'); // Di Bayarkan Perusahaan
        $sheet->mergeCells('A5:D5'); // Date Range
    
        // Align PT. Tolan Tiga Indonesia and HRD to the left
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    
        // Merge cells for headings and sub-headings
        $sheet->mergeCells('A7:A8'); // No
        $sheet->mergeCells('B7:B8'); // Nama
        $sheet->mergeCells('C7:D7'); // Jumlah
    
        // Apply number format to the specific columns
        $sheet->getStyle('C9:D' . $highestRow)
              ->getNumberFormat()
              ->setFormatCode('#,##0.0');
    
        // Add additional styles for the footer and signature section
        $footerRow = $highestRow + 2; // Footer starts two rows below the last data row
        $signatureRow = $footerRow + 2;
    
        // Footer total
        $sheet->setCellValue('A' . $footerRow, 'Medan, 18 Juni 2024');
        $sheet->getStyle('A' . $footerRow)
              ->getFont()
              ->setName('Book Antiqua')
              ->setBold(true);
        $sheet->mergeCells('A' . $footerRow . ':D' . $footerRow);
        $sheet->getStyle('A' . $footerRow . ':D' . $footerRow)
              ->getAlignment()
              ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    
        // Signature section
        $sheet->setCellValue('A' . $signatureRow, 'Approved By,');
        $sheet->getStyle('A' . $signatureRow)
              ->getFont()
              ->setName('Book Antiqua')
              ->setBold(true);
        $sheet->mergeCells('A' . $signatureRow . ':D' . $signatureRow);
        $sheet->getStyle('A' . $signatureRow . ':D' . $signatureRow)
              ->getAlignment()
              ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    
    }
}
