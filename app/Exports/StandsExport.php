<?php

namespace App\Exports;

use App\Models\Stand;
use Illuminate\Support\Facades\Auth;
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

class StandsExport implements FromQuery, WithCustomStartCell, WithMapping, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

    protected $type, $startDate, $endDate, $user;

    public function query()
    {
        return Stand::orderBy('name', 'asc');
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($row) : array
    {
        return [
            str_pad($row->id, 8, '0', STR_PAD_LEFT),
            'STAND: '.$row->name.' - '.$row->stage->name,
            $row->partner->full_name,
            ''.$row->partner->document.'',
        ];
    }

    public function columnFormats(): array
    {
        return [
        ];
    }

    public function headings(): array
    {
        return [
            'CODIGO',
            'STAND-AREA',
            'CLIENTE',
            '#DOCUMENTO',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('STANDS - PUESTOS');
        $sheet->mergeCells('A3:D4');
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(18);

        $sheet->getStyle('A6:D6')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A6:D'.$sheet->getHighestRow())->getAlignment()->setIndent(1);
        $sheet->getRowDimension('3')->setRowHeight(30);
        $sheet->getRowDimension('6')->setRowHeight(30);
        $sheet->getStyle('A3:D4')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => 'thick',
                ],
            ]
        ]);
        $sheet->setCellValue('A3', "REPORTE DE STANDS CC5");
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
                'size' => 20,
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
            ],
        ]);


        $sheet->getStyle('A7')->applyFromArray([

        ]);

    }
}
