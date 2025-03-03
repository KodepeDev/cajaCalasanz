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

class DetailsConceptosExport implements FromQuery, ShouldAutoSize, WithCustomStartCell, WithMapping, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

    protected $status, $category, $startDate, $endDate, $etapa, $categoria_nombre;

    public function __construct($status, $mes, $category)
    {
        $this->status = $status;
        $this->category = $category;

        $cat = Category::whereKey($this->category)->first();

        $this->categoria_nombre = $cat->name;

        $first_day = Carbon::parse($mes)->firstOfMonth();
        $last_day = Carbon::parse($mes)->endOfMonth();

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
        return Detail::query()->with(['stand'])
                    ->where('details.status',$this->status)
                    ->where('details.category_id',$this->category)
                    ->whereBetween('details.date', [$this->startDate, $this->endDate])
                    ->where('details.summary_type', 'add')
                    ->orderByRaw('(SELECT name FROM stands WHERE stands.id = details.stand_id)')
                    ->select('details.id', 'details.date', 'details.date_paid', 'details.description', 'details.category_id', 'details.stand_id', 'details.partner_id', 'details.amount');
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($detail) : array
    {
        return [
            Date::datetimeToExcel($detail->date),
            $detail->description,
            $detail->date_paid ? "Pagado el ".$detail->date_paid->format('d/m/Y')."" : 'Pendiente de pago',
            $detail->stand ? $detail->stand->name." - ".$detail->stand->stage->name : 'S/N',
            $detail->partner ? $detail->partner->full_name : 'Varios',
            'PEN '.$detail->currency->id == 1 ? $detail->amount : 0,
            'USD '.$detail->currency->id == 2 ? ($detail->date_paid ? $detail->changed_amount : $detail->amount) : 0,
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
            'DESCRIPCION',
            'ESTADO',
            'STAND',
            'CLIENTE/SOCIO',
            'SOLES',
            'DOLAR',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Detalles');
        $sheet->mergeCells('A3:G4');
        $sheet->getRowDimension('3')->setRowHeight(30);
        $sheet->getRowDimension('6')->setRowHeight(30);
        $sheet->getStyle('A3:G4')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => 'thick',
                ],
            ]
        ]);
        $sheet->setCellValue('A3', $this->categoria_nombre." Del mes " .Carbon::parse($this->startDate)->format('m/Y') . "");
        $sheet->getStyle('A6:G6')->applyFromArray([
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
        $sheet->getStyle('A3:G4')->applyFromArray([
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

        $sheet->getStyle('A6:G'.$sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ]
            ]
        ]);

        $sheet->setCellValue('E'. $sheet->getHighestRow() + 2, "Suma Total");
        $sheet->setCellValue('F'. $sheet->getHighestRow(), '=SUM(F7:F'. ($sheet->getHighestRow()-2) .')');
        $sheet->setCellValue('G'. $sheet->getHighestRow(), '=SUM(G7:G'. ($sheet->getHighestRow()-2) .')');


        $sheet->getStyle('A7')->applyFromArray([

        ]);

    }
}
