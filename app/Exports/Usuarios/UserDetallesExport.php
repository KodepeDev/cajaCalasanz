<?php

namespace App\Exports\Usuarios;

use App\Models\Account;
use Carbon\Carbon;
use App\Models\Stage;
use App\Models\Detail;
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

class UserDetallesExport implements FromQuery, WithCustomStartCell, WithMapping, WithColumnFormatting, WithHeadings, WithStyles
{
    use Exportable;

    protected $type, $startDate, $endDate, $user, $account, $cuenta;

    public function __construct($startDate, $endDate, $type, $account)
    {
        $this->type = $type;
        $this->account = $account;
        $this->startDate = Carbon::parse($startDate)->format('Y-m-d');
        $this->endDate = Carbon::parse($endDate)->format('Y-m-d');

        $this->user = Auth::user()->first_name;
        if ($this->account) {
            $this->cuenta = Account::where('id', $this->account)->first()->account_name;
            // dd($this->cuenta);
        } else {
            $this->cuenta = "";
        }

    }

    public function query()
    {
        return Detail::query()->with(['summary', 'stand'])
                    ->whereHas('summary', function ($q){
                        $q->where('user_id', '=', Auth::user()->id);
                        if($this->account){
                            $q->where('account_id', '=', $this->account);
                        }
                    })
                    ->when($this->type, function ($q){
                        $q->where('details.summary_type', '=', $this->type);
                    })
                    ->where('details.status', true)
                    ->whereBetween('details.date_paid', [$this->startDate, $this->endDate])
                    ->orderBy('details.date_paid', 'ASC')
                    ->select('details.id', 'details.date', 'details.summary_type', 'details.date_paid', 'details.description', 'details.category_id', 'details.stand_id', 'details.partner_id', 'details.amount', 'details.summary_id');
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($detail) : array
    {
        return [
            Date::datetimeToExcel($detail->date_paid),
            $detail->summary->recipt_series,
            $detail->summary->recipt_number,
            Date::datetimeToExcel($detail->date),
            $detail->description,
            $detail->stand ? $detail->stand->name." - ".$detail->stand->stage->name : 'S/N',
            $detail->partner ? $detail->partner->full_name : $detail->summary->customer->full_name,
            $detail->summary_type == 'add' ?  +$detail->amount : -$detail->amount,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => 'dd/mm/yyyy',
            'D' => 'mm/yyyy',
            'H' => '0.00" PEN"',
        ];
    }

    public function headings(): array
    {
        return [
            'FECHA DE PAGO',
            'SERIE',
            'NUMERO',
            'MES',
            'DESCRIPCION',
            'STAND',
            'CLIENTE O PROVEEDOR',
            'MONTO',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('DETALLES DE - ' .$this->user);
        $sheet->mergeCells('A3:H4');
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(13);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(15);

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
        $sheet->setCellValue('A3', "DETALLES DE COBRANZA - ".$this->cuenta." - ".$this->user." - DEL " .Carbon::parse($this->startDate)->format('d/m/Y') . " AL " .Carbon::parse($this->endDate)->format('d/m/Y') . "");
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

        $sheet->setCellValue('G'. $sheet->getHighestRow() + 2, "Suma Total");
        $sheet->setCellValue('H'. $sheet->getHighestRow(), '=SUM(H7:H'. ($sheet->getHighestRow()-2) .')');


        $sheet->getStyle('A7')->applyFromArray([

        ]);

    }
}
