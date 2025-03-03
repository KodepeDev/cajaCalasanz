<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Detail;
use Maatwebsite\Excel\Sheet;
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
use PhpOffice\PhpSpreadsheet\Style\Color;

class DetailsExport implements FromQuery, WithCustomStartCell, WithMapping, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

    protected $tipo, $category, $startDate, $endDate;

    public function __construct($tipo, $category, $startDate, $endDate)
    {
        $this->tipo = $tipo;
        $this->category = $category;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        // dd($startDate, $endDate);
    }

    public function query()
    {
        // return Detail::query()->whereYear('created_at', $this->year);

        $hoy = Carbon::now()->format('Y-m-d');

        $filter=array();

        if($this->tipo != null) {

            $filter[] = array('summary_type','=',$this->tipo);
            $details = Detail::query()->where($filter)->whereDate('date_paid','<=',$hoy)->orderBy('date_paid', 'asc');

        }

        if($this->category != null) {

            $filter[] = array('category_id','=',$this->category);
            $details = Detail::query()->where($filter)->whereDate('date_paid','<=',$hoy)->orderBy('date_paid', 'asc');

        }
        if((isset($this->startDate)) and (isset($this->endDate))){;

            $start1 = Carbon::parse($this->startDate)->format('Y-m-d');
            $finish1 = Carbon::parse($this->endDate)->format('Y-m-d');


            $details = Detail::query()->where($filter)->whereBetween('date_paid', [$start1, $finish1])->orderBy('date_paid', 'asc');

        }else{

            if($filter) {
                $details = Detail::query()->where($filter)->where('date_paid','=',$hoy)->orderBy('date_paid', 'asc');
            }else {
                $details = Detail::query()->where('date_paid','=',$hoy)->orderBy('date_paid', 'asc');
            }
        }

        return $details;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($detail) : array
    {
        // Calcula los valores para cada moneda
        $amountInSoles = $detail->currency_id != 2
        ? ($detail->summary_type == 'add' ? $detail->amount : -$detail->amount)
        : ($detail->summary_type == 'add' ? $detail->changed_amount : -$detail->changed_amount);

        $amountInDollars = $detail->currency_id == 2
        ? ($detail->summary_type == 'add' ? $detail->amount : -$detail->amount)
        : 0;

        return [
            Date::datetimeToExcel($detail->summary->date),
            $detail->category->name,
            $detail->description,
            $detail->stand ? $detail->stand->name : '',
            $detail->summary_type == 'add'
                ? 'Ingreso ' . ($detail->stand ? strtoupper($detail->stand->stage->name) : '')
                : 'Gasto ' . ($detail->summary->section_type == "AD" ? 'ADMINISTRACION' : ($detail->summary->section_type == "1E" ? 'AREA PRINCIPAL' : 'AREA SECUNDARIA')),
            $detail->summary->customer ? $detail->summary->customer->full_name : 'Varios',

            // G: Monto en soles
            $amountInSoles,

            // H: Monto en dólares
            $amountInDollars,

            $detail->summary->recipt_series . '-' . str_pad($detail->summary->recipt_number, 8, '0', STR_PAD_LEFT),
            $detail->summary->paymentMethod->name,
            $detail->summary->operation_number,
            $detail->summary->status == 'PAID' ? $detail->summary->observation : $detail->summary->nulled_motive,
            $detail->summary->status == 'NULLED' ? 'ANULADO' : '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'dd/mm/yyyy',
            'G' => '0.00" PEN"',
            'H' => '0.00" USD"',
        ];
    }

    public function rowFormats(): array
    {
        return [
            '6' => '@',
        ];
    }

    public function headings(): array
    {
        return [
            'FECHA',
            'CATEGORÍA',
            'DESCRIPCION',
            'STAND',
            'TIPO',
            'CLIENTE/PROVEEDOR',
            'SOLES',
            'DOLARES',
            'N° DE RECIBO',
            'MEDIO DE PAGO',
            '#OPERACION',
            'OBSERVACIONES',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Detalle de Moviemientos');

        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(50);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);

        $sheet->getRowDimension('6')->setRowHeight(30);
        $sheet->getStyle('A6:K6')->getAlignment()->setWrapText(true);

        $sheet->mergeCells('A3:L4');
        $sheet->getStyle('A3:L4')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ]
        ]);
        $sheet->setCellValue('A3', "Detalle de Movimientos del " .$this->startDate . ' al '.$this->endDate);
        $sheet->getStyle('A6:L6')->applyFromArray([
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
        $sheet->getStyle('A3:L4')->applyFromArray([
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

        $sheet->getStyle('A6:L'.$sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ]
            ]
        ]);

        $sheet->setCellValue('F'. $sheet->getHighestRow() + 2, "Suma Total");
        $sheet->setCellValue('G'. $sheet->getHighestRow(), '=SUM(G7:G'. ($sheet->getHighestRow()-2) .')');
        $sheet->setCellValue('H'. $sheet->getHighestRow(), '=SUM(H7:H'. ($sheet->getHighestRow()-2) .')');

        // Obtener la última fila con datos
        $lastRow = $sheet->getHighestRow();

        // Aplicar estilo a las celdas de la columna 'D' (tipo)
        for ($row = 1; $row <= $lastRow; $row++) {
            $cell = 'E' . $row;
            $cell2 = 'M' . $row;
            $type = $sheet->getCell($cell)->getValue();
            $stat = $sheet->getCell($cell2)->getValue();

            // Verificar si el tipo de movimiento es 'gasto'
            if ($type === 'Gasto') {
                switch ($stat) {
                    case 'ANULADO':
                        $sheet->getStyle($row)->getFont()->setColor(new Color(Color::COLOR_RED));
                        break;
                    default:
                        $sheet->getStyle($row)->getFont()->setColor(new Color(Color::COLOR_DARKYELLOW));
                        break;
                }
            }
        }

        $sheet->getStyle('A7')->applyFromArray([

        ]);


    }
}
