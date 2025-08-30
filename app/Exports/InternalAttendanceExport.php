<?php

namespace App\Exports;

use App\Models\InternalAttendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class InternalAttendanceExport implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $record;

    public function __construct(InternalAttendance $record)
    {
        $this->record = $record;
    }

    public function view(): View
    {
        return view('exports.internal_attendance', [
            'record' => $this->record,
        ]);
    }

    public function title(): string
    {
        return 'Activity Register';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:B1' => ['font' => ['bold' => true, 'size' => 14]],
            'A2:A11' => ['font' => ['bold' => true]],
            'A14:E14' => [
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
            'A' => 25,
            'B' => 30,
            'C' => 25,
            'D' => 20,
            'E' => 30,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Apply borders to attendee table
                $lastRow = 14 + $this->record->internal_attendance_entries->count();
                $sheet->getStyle("A14:E{$lastRow}")->applyFromArray([
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
