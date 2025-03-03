<?php

namespace App\Exports\Usuarios;

use Carbon\Carbon;
use App\Models\Stage;
use App\Models\Detail;
use App\Models\Summary;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class UserRecibosExport implements FromQuery, WithCustomStartCell, WithMapping, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

    protected $type, $startDate, $endDate, $user, $account;

    public function __construct($startDate, $endDate, $type, $account)
    {
        $this->type = $type;
        $this->account = $account;
        $this->startDate = Carbon::parse($startDate)->format('Y-m-d');
        $this->endDate = Carbon::parse($endDate)->format('Y-m-d');

        $this->user = Auth::user()->first_name;
    }

    public function query()
    {
        return Auth::user()->misRecibos($this->startDate, $this->endDate, $this->type, $this->account);
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($row) : array
    {
        return [
            Date::datetimeToExcel($row->date),
            $row->recipt_series,
            $row->recipt_number,
            $row->customer->full_name,
            $row->type == 'add' ? 'Ingreso' : 'Gasto',
            $row->type == 'add' ?  +$row->amount : -$row->amount,
            $row->paymentMethod->name,
            $row->account->account_name,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'dd/mm/yyyy',
            'F' => '0.00" PEN"',
        ];
    }

    public function headings(): array
    {
        return [
            'FECHA DE EMISION',
            'SERIE',
            'NÚMERO',
            'CLIENTE O PROVEEDOR',
            'TIPO',
            'MONTO',
            'MEDIO DE PAGO',
            'CUENTA',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('RECIBOS DE - ' .$this->user);
        $sheet->mergeCells('A3:H4');
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(13);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);

        $sheet->getStyle('A6:H6')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A6:H'.$sheet->getHighestRow())->getAlignment()->setIndent(1);
        $sheet->getRowDimension('3')->setRowHeight(30);
        $sheet->getRowDimension('6')->setRowHeight(30);
        $sheet->getStyle('A3:H4')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => 'thick',
                ],
            ]
        ]);
        $sheet->setCellValue('A3', "RECIBOS DE COBRANZA - " .$this->user." - DEL " .Carbon::parse($this->startDate)->format('d/m/Y') . " AL " .Carbon::parse($this->endDate)->format('d/m/Y') . "");
        $sheet->getStyle('A6:H6')->applyFromArray([
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
        $sheet->getStyle('A3:H4')->applyFromArray([
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

        $sheet->getStyle('A6:H'.$sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ]
            ],
        ]);

        $sheet->setCellValue('E'. $sheet->getHighestRow() + 2, "Suma Total");
        $sheet->setCellValue('F'. $sheet->getHighestRow(), '=SUM(F7:F'. ($sheet->getHighestRow()-2) .')');


        $sheet->getStyle('A7')->applyFromArray([

        ]);

    }
}
