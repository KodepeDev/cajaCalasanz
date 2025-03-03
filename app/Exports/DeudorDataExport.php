<?php

namespace App\Exports;

use App\Models\Detail;
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

class DeudorDataExport implements FromQuery, WithMapping, WithHeadings, WithStyles, WithColumnFormatting, WithCustomStartCell, ShouldAutoSize
{
    use Exportable;
    public $id;
    public $partner;
    public function __construct($id, $partner)
    {
        // dd($id, $partner);
        $this->id = $id;
        $this->partner = $partner;
    }

    public function query()
    {
        return Detail::query()->whereStatus(0)->where('partner_id', $this->id);
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($detail) : array
    {
        $total_soles = $detail->currency_id == 1 ? $detail->amount : 0;
        $total_dolares = $detail->currency_id == 2 ? $detail->amount : 0;
        return [
            Date::datetimeToExcel($detail->date),
            $detail->description,
            $detail->stand ? $detail->stand->name." - ".$detail->stand->stage->name : 'S/N',
            $detail->partner ? $detail->partner->full_name : 'Varios',
            $total_soles,
            $total_dolares
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'mm/yyyy',
            'E' => '0.00" PEN"',
            'F' => '0.00" USD"',
        ];
    }

    public function headings(): array
    {
        return [
            'FECHA',
            'DESCRIPCION',
            'STAND',
            'CLIENTE/SOCIO',
            'SOLES',
            'DOLAR',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Deudas');
        $sheet->mergeCells('A3:F4');
        $sheet->getRowDimension('3')->setRowHeight(30);
        $sheet->getRowDimension('6')->setRowHeight(30);
        $sheet->getStyle('A3:F4')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => 'thick',
                ],
            ]
        ]);
        $sheet->setCellValue('A3', "DETALLE DE DEUDAS ".$this->partner);
        $sheet->getStyle('A6:F6')->applyFromArray([
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
        $sheet->getStyle('A3:F4')->applyFromArray([
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

        $sheet->getStyle('A6:F'.$sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ]
            ]
        ]);

        $sheet->setCellValue('B'. $sheet->getHighestRow() + 2, "Suma Total");
        $sheet->setCellValue('E'. $sheet->getHighestRow(), '=SUM(E7:E'. ($sheet->getHighestRow()-2) .')');
        $sheet->setCellValue('F'. $sheet->getHighestRow(), '=SUM(F7:F'. ($sheet->getHighestRow()-2) .')');


        $sheet->getStyle('A7')->applyFromArray([

        ]);
    }
}
