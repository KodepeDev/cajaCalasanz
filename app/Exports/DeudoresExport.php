<?php

namespace App\Exports;

use App\Models\Partner;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class DeudoresExport implements FromQuery, WithMapping, WithHeadings, WithStyles, WithColumnFormatting, WithCustomStartCell, ShouldAutoSize
{
    use Exportable;

    public function __construct()
    {

    }

    public function query()
    {
        return Partner::query()->where('is_active', 1)
                ->whereHas('details', function($q) {
                    $q->where('status', 0);
                })
                ->with(['details' => function($q) {
                    $q->where('status', 0);
                }]);
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($partner) : array
    {
        $total_soles = $partner->details->where('currency_id', 1)->sum('amount');
        $total_dolares = $partner->details->where('currency_id', 2)->sum('amount');
        return [
            $partner->document,
            $partner->full_name,
            $total_soles,
            $total_dolares,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '0.00" PEN"',
            'D' => '0.00" USD"',
        ];
    }

    public function headings(): array
    {
        return [
            'DOCUMENTO',
            'NOMBRE',
            'SOLES',
            'DOLAR',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Deudores');
        $sheet->mergeCells('A3:D4');
        $sheet->getRowDimension('3')->setRowHeight(30);
        $sheet->getRowDimension('6')->setRowHeight(30);
        $sheet->getStyle('A3:D4')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => 'thick',
                ],
            ]
        ]);
        $sheet->setCellValue('A3', "LISTADO DE DEUDORES");
        $sheet->getStyle('A6:D6')->applyFromArray([
            'font' => [
                'bold' => true,
                'name' => 'Arial',
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],

            'fill' => [
                'fillType' => 'solid',
                'startColor' => [
                    'argb' => 'FCC203'
                ],
            ]
        ]);
        $sheet->getStyle('A3:D4')->applyFromArray([
            'font' => [
                'bold' => true,
                'name' => 'Arial',
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => [
                    'argb' => 'FCC203'
                ],
            ]
        ]);

        // dd($sheet->getHighestRow() + 1);

        $sheet->getStyle('A6:D'.$sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ]
            ]
        ]);

        $sheet->setCellValue('B'. $sheet->getHighestRow() + 2, "Suma Total");
        $sheet->setCellValue('C'. $sheet->getHighestRow(), '=SUM(C7:C'. ($sheet->getHighestRow()-2) .')');
        $sheet->setCellValue('D'. $sheet->getHighestRow(), '=SUM(D7:D'. ($sheet->getHighestRow()-2) .')');


        $sheet->getStyle('A7')->applyFromArray([

        ]);
    }
}
