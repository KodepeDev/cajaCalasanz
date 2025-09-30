<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DetailsPendienteConceptosExport implements FromQuery, ShouldAutoSize, WithCustomStartCell, WithMapping, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

    protected $status, $category, $check, $startDate, $endDate, $etapa, $categoria_nombre;

    public function __construct($status, $check, $start_date, $end_date, $category)
    {
        $this->status = $status;
        $this->category = $category;
        $check = $check;

        $cat = Category::whereKey($this->category)->first();

        $this->categoria_nombre = $cat->name;

        $first_day = Carbon::parse($start_date)->firstOfMonth()->format('Y-m-d');
        $last_day = Carbon::parse($end_date)->endOfMonth()->format('Y-m-d');
        
        $this->startDate = $first_day;
        $this->endDate = $last_day;

        // $detalle = Detail::with([
        //     'stand' => fn ($query) => $query->orderBy('name', 'asc')
        // ])
        // ->where('details.status',$this->status)
        // ->where('details.category_id',$this->category)
        // ->whereBetween('details.date', [$this->startDate, $this->endDate])
        // ->where('details.summary_type', 'add')
        // ->select('details.id', 'details.date', 'details.date_paid', 'details.description', 'details.category_id', 'details.stand_id', 'details.partner_id', 'details.amount')->get();
        // dd($detalle);
        // dd($tipo, $this->category, $this->etapa);
    }

    public function query()
    {
        return Detail::query()->with(['student'])
                    ->where('details.status',$this->status)
                    ->where('details.category_id',$this->category)
                    ->when($this->check, function($q){
                        $q->whereBetween('details.date', [$this->startDate, $this->endDate]);
                    })
                    ->where('details.summary_type', 'add')
                    ->select('details.id', 'details.date', 'details.date_paid', 'details.description', 'details.category_id', 'details.student_id', 'details.student_tutor_id', 'details.amount');
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($detail) : array
    {
        $student_tutor = $detail->student ? $detail->student->tutor?->full_name : 'N/A';
        return [
            Date::datetimeToExcel($detail->date),
            $detail->description,
            $detail->date_paid ? "Pagado el ".$detail->date_paid->format('d/m/Y')."" : 'Pendiente de pago',
            $detail->student ? $detail->student->full_name : 'N/A',
            $student_tutor,
            $detail->amount,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'mm/yyyy',
            'F' => '0.00" PEN"',
        ];
    }

    public function headings(): array
    {
        return [
            'MES',
            'DESCRIPCION',
            'ESTADO',
            'ESTUDIANTE',
            'PADRE/MADRE O TUTOR',
            'MONTO',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Detalles');
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
        $sheet->setCellValue('A3', $this->categoria_nombre." - PENDIENTES DE PAGO " );
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

        $sheet->setCellValue('E'. $sheet->getHighestRow() + 2, "Suma Total");
        $sheet->setCellValue('F'. $sheet->getHighestRow(), '=SUM(F7:F'. ($sheet->getHighestRow()-2) .')');


        $sheet->getStyle('A7')->applyFromArray([

        ]);

    }
}
