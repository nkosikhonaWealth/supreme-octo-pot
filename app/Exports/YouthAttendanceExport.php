<?php

namespace App\Exports;

use App\Models\YouthAttendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class YouthAttendanceExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $record;

    public function __construct(YouthAttendance $record)
    {
        $this->record = $record;
    }

    public function view(): View
    {
        return view('exports.youth_attendance', [
            'record' => $this->record,
        ]);
    }

    public function title(): string
    {
        return 'Youth Activity Register';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:B1' => ['font' => ['bold' => true, 'size' => 14]],
            'A2:A12' => ['font' => ['bold' => true]],
            'A16:J16' => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'D9E1F2']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 8,
            'D' => 10,
            'E' => 15,
            'F' => 20,
            'G' => 20,
            'H' => 25,
            'I' => 20,
            'J' => 25,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Apply borders to attendee table
                $lastRow = 16 + $this->record->youth_attendance_entries->count();
                $sheet->getStyle("A16:J{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

            },
        ];
    }
}
