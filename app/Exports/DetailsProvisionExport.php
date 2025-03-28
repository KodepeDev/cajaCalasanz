<?php

namespace App\Exports;

use App\Models\Category;
use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Stage;
use Maatwebsite\Excel\Concerns\FromCollection;
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

class DetailsProvisionExport implements FromQuery, ShouldAutoSize, WithCustomStartCell, WithMapping, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

    protected $tipo, $category, $startDate, $endDate, $categoria_nombre, $etapa_nombre;

    public function __construct($tipo, $category, $mes)
    {
        $this->tipo = $tipo;
        $this->category = $category;

        $cat = Category::whereKey($this->category)->first();

        $this->categoria_nombre = $cat->name;

        $first_day = Carbon::parse($mes)->firstOfMonth();
        $last_day = Carbon::parse($mes)->endOfMonth();

        $this->startDate = $first_day;
        $this->endDate = $last_day;

        // dd($tipo, $this->category, $this->etapa);
    }

    public function query()
    {
        $det = Detail::query()
                    ->where('summary_type', 'add')
                    ->where('type', $this->tipo)
                    ->whereBetween('date', [$this->startDate, $this->endDate])
                    ->where('category_id', $this->category)
                    ->select('id', 'date', 'category_id', 'description', 'student_id', 'currency_id', 'amount');
        return $det;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($detail) : array
    {
        return [
            Date::datetimeToExcel($detail->date),
            $detail->category->name,
            $detail->description,
            $detail->student_id ? $detail->student->full_name : 'S/N',
            $detail->currency_id ? $detail->currency->code : '',
            $detail->amount,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'mm/yyyy',
        ];
    }

    public function headings(): array
    {
        return [
            'MES',
            'CATEGORÍA',
            'DESCRIPCION',
            'ESTUDIANTE',
            'MONEDA',
            'MONTO',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Detalles');
        $sheet->mergeCells('A3:F3');
        $sheet->mergeCells('A4:F4');
        $sheet->getRowDimension('3')->setRowHeight(30);
        $sheet->getRowDimension('4')->setRowHeight(30);
        $sheet->getRowDimension('6')->setRowHeight(30);
        $sheet->getStyle('A3:F4')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => 'thick',
                ],
            ]
        ]);
        $sheet->setCellValue('A3', $this->categoria_nombre." - PENDIENTES DE PAGO");
        $sheet->setCellValue('A4', "Del mes " .Carbon::parse($this->startDate)->format('m/Y') . "");
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

        $sheet->setCellValue('F'. $sheet->getHighestRow() + 2, "Suma Total");
        $sheet->setCellValue('F'. $sheet->getHighestRow(), '=SUM(F7:F'. ($sheet->getHighestRow()-2) .')');


        $sheet->getStyle('A7')->applyFromArray([

        ]);

    }
}
