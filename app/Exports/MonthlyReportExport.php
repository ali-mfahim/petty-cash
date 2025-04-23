<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonthlyReportExport implements
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithStyles,
    ShouldAutoSize,
    WithColumnFormatting
{
    protected $data;
    protected $mainHeading;
    protected $userId;
    protected $role;
    protected $matchedRowIndexes = [];
    protected $unMatchedRowIndexes = [];

    public function __construct(array $data, string $mainHeading, string $userId, string $role)
    {
        $this->data = $data;
        $this->mainHeading = $mainHeading;
        $this->userId = $userId;
        $this->role = $role;
    }

    public function collection()
    {
        $totalSum = 0;
        $perHeadSum = 0;
        $rowIndex = 3;

        $rows = collect($this->data)->map(function ($item) use (&$totalSum, &$perHeadSum, &$rowIndex) {
            $data = json_decode($item['divided_in']);
            $members = "";

            foreach ($data as $i => $value) {
                if ($i != 0) {
                    $members .= ", ";
                }
                $members .= getUserName(getUser($value));
            }

            $total = (float) ($item['total_amount'] ?? 0);
            $perhead = (float) ($item['per_head_amount'] ?? 0);

            if ($this->role != "Super Admin") {
                if ($item['paid_by'] == $this->userId) {
                    $totalSum += $total;
                    $this->matchedRowIndexes[] = $rowIndex;
                } else {
                    $this->unMatchedRowIndexes[] = $rowIndex;
                }
            } else {
                $totalSum += $total;
            }

            $perHeadSum += $perhead;

            $rowIndex++;

            return [
                'PAID BY' => getUserName(getUser($item['paid_by'])) ?? '-',
                'ITEM' => $item['title'] ?? '-',
                'MEMBERS' => $members ?? '-',
                'TOTAL' => $total, // Keep as number
                'PER HEAD' => round($perhead, 2),
                'DATE' => formatDate($item['date']) ?? '-',
                'ENTRY DATE' => formatDate($item['created_at']) ?? '-',
            ];
        });
        $balance = (float) $totalSum - (float) $perHeadSum;
        if ($balance >= 0) {
            $class = "success";
        } else if ($balance < 0) {
            $class = "danger";
        }
        // Add total row
        $rows->push([
            'PAID BY' => 'Total',
            'ITEM' => '',
            'MEMBERS' => '',
            'TOTAL' => round($totalSum),
            'PER HEAD' => round($perHeadSum),
            'DATE' => 'Balance : ' . round($balance),
            'ENTRY DATE' => '',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return ['PAID BY', 'ITEM', 'MEMBERS', 'TOTAL (Rs)', 'PER HEAD (Rs)', 'DATE', 'ENTRY DATE'];
    }

    public function startCell(): string
    {
        return 'A2';
    }


    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        // === 1. Main Heading (Row 1) ===
        $sheet->setCellValue('A1', $this->mainHeading);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('000000');
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFF');

        // === 2. Header Row (Row 2) ===
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('CACACA');

        // === 3. Total Row (Last Row) ===
        $rowCount = count($this->data) + 3;

        // Merge starting 3 columns and ending 2 columns
        $sheet->mergeCells("A{$rowCount}:C{$rowCount}");
        $sheet->mergeCells("F{$rowCount}:G{$rowCount}");

        // Set value: Left (merged A-C) = "Total", Right (merged F-G) = current date
        $sheet->setCellValue("A{$rowCount}", "Total");
        // $sheet->setCellValue("F{$rowCount}", 'Date:' . formatDateTime(Carbon::now())); // use Carbon if needed

        // Center align merged cells
        $sheet->getStyle("A{$rowCount}:C{$rowCount}")
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F{$rowCount}:G{$rowCount}")
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Make total row bold
        $sheet->getStyle("A{$rowCount}:G{$rowCount}")->getFont()->setBold(true);

        // Set background color for total row
        $sheet->getStyle("A{$rowCount}:G{$rowCount}")
            ->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('CACACA');

        // === 4. Add Borders to All Cells ===
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:G{$highestRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }



    // styles to show paid and unpaid 
    // public function styles(Worksheet $sheet)
    // {
    //     // Main heading
    //     $sheet->setCellValue('A1', $this->mainHeading);
    //     $sheet->mergeCells('A1:G1');
    //     $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    //     $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


    //     $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
    //     $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFF');


    //     // Header row styles
    //     $sheet->getStyle('A2:G2')->getFont()->setBold(true);
    //     $sheet->getStyle('A2:G2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('CACACA');

    //     // Highlight rows where paid_by matched userId
    //     foreach ($this->matchedRowIndexes as $row) {
    //         $sheet->getStyle("A$row:G$row")->getFill()
    //             ->setFillType(Fill::FILL_SOLID)
    //             ->getStartColor()->setARGB('339933');
    //         $sheet->getStyle("A$row:G$row")->getFont()->getColor()->setARGB('FFFFFF');
    //     }

    //     foreach ($this->unMatchedRowIndexes as $unMatchedrow) {
    //         $sheet->getStyle("A$unMatchedrow:G$unMatchedrow")->getFill()
    //             ->setFillType(Fill::FILL_SOLID)
    //             ->getStartColor()->setARGB('c00000');
    //         $sheet->getStyle("A$unMatchedrow:G$unMatchedrow")->getFont()->getColor()->setARGB('FFFFFF');
    //     }



    //     // Make column B wider
    //     $sheet->getColumnDimension('B')->setWidth(30);



    //     // 👉 Merge "PAID BY", "ITEM", and "MEMBERS" in the Total row
    //     // And also merge "DATE" and "ENTRY DATE"

    //     $rowCount = count($this->data) + 3; // +2 for heading and header row, +1 for total row
    //     $sheet->mergeCells("A{$rowCount}:C{$rowCount}"); // Merge PAID BY to MEMBERS
    //     $sheet->mergeCells("F{$rowCount}:G{$rowCount}"); // Merge DATE to ENTRY DATE

    //     // Optional: center text inside merged cells
    //     $sheet->getStyle("A{$rowCount}:C{$rowCount}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle("F{$rowCount}:G{$rowCount}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    //     // Optional: bold the Total row
    //     $sheet->getStyle("A{$rowCount}:G{$rowCount}")->getFont()->setBold(true);

    //     // Center align merged cells
    //     $sheet->getStyle("A{$rowCount}:C{$rowCount}")
    //         ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle("F{$rowCount}:G{$rowCount}")
    //         ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    //     // Make total row bold
    //     $sheet->getStyle("A{$rowCount}:G{$rowCount}")->getFont()->setBold(true);

    //     // Set background color for total row
    //     $sheet->getStyle("A{$rowCount}:G{$rowCount}")
    //         ->getFill()->setFillType(Fill::FILL_SOLID)
    //         ->getStartColor()->setARGB('CACACA');
    //     $highestRow = $sheet->getHighestRow();
    //     $sheet->getStyle("A2:G{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    // }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // TOTAL (Rs)
            'E' => NumberFormat::FORMAT_NUMBER_00,               // PER HEAD (Rs)
        ];
    }
}
