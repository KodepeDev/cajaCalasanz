<?php

namespace App\Http\Livewire\Reportes;

use DateTime;
use Carbon\Carbon;
use App\Models\Tour;
use App\Models\Detail;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Category;
use App\Models\Customer;
use Livewire\WithPagination;
use App\Exports\DetailsExport;
use App\Http\Controllers\ReportController;
use Psy\CodeCleaner\AssignThisVariablePass;

class ReporteDetalles extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $categories, $categoria, $id_attr, $tours, $accounts, $divisa;

    public $totalIngresosTx, $totalEgresosTx, $totalFinal, $hoy;

    public  $categorias, $subcategorias;

    public $dataReporte;


    //filters data

    public $start, $finish, $categoria_id, $cuenta_id, $dias, $tipo, $tf, $documento;
    public $start1, $finish1, $categoria_id1, $cuenta_id1, $dias1, $tipo1, $tf1, $documento1;

    // public $summaries;


    public function mount()
    {

        $this->categories = Category::where('id', '!=', 1)->pluck('id', 'name');
        $this->divisa = 'Soles';

        $this->hoy = Carbon::now()->format('Y-m-d');

        $this->start1 = $this->hoy;
        $this->finish1 = $this->hoy;

        $this->tipo = '';
        $this->categoria_id = '';
        $this->start = $this->start1;
        $this->finish = $this->finish1;

    }

    public function render()
    {

        $summary = Detail::whereStatus(1)->whereDate('date_paid', '<=', $this->hoy);

        $filter = [];

        if ($this->tipo != null) {
            $filter[] = ['summary_type', '=', $this->tipo];
        }

        if ($this->categoria_id != null) {
            $filter[] = ['category_id', '=', $this->categoria_id];
        }

        if (isset($this->start) && isset($this->finish)) {
            $start = Carbon::parse($this->start)->format('Y-m-d');
            $finish = Carbon::parse($this->finish)->format('Y-m-d');
            $summary = $summary->whereBetween('date_paid', [$start, $finish]);
        } else {
            $summary = $summary->where('date_paid', '=', $this->hoy);
        }

        if (!empty($filter)) {
            $summary = $summary->where($filter);
        }

        $movim = $summary->get();

        // Suma de ingresos y egresos en soles
        $sumaIngresos = $movim->filter(fn($item) => $item->summary_type === 'add')
                        ->sum(fn($item) => $item->currency_id == 2 ? $item->changed_amount : ($item->currency_id !== 2 || $item->currency_id === null ? $item->amount : 0));

        $sumaEgresos = $movim->where('summary_type', '=', 'out')->sum('amount');


        $this->totalFinal = $sumaIngresos - $sumaEgresos;

        $movimientos = $summary->paginate(10);

        return view('livewire.reportes.reporte-detalles', ['summaries' => $movimientos])->extends('adminlte::page');
    }


    public function Filter()
    {
        if($this->validarFechas()){
            $this->start = $this->start1;
            $this->finish = $this->finish1;
            $this->cuenta_id = $this->cuenta_id1;
            $this->categoria_id = $this->categoria_id1;
            $this->documento = $this->documento1;
            $this->tf = $this->tf;
            $this->dias = $this->dias1;
            $this->tipo = $this->tipo1;
            $this->resetPage();
        }
    }
    public function clearFilter()
    {
        $this->start = $this->hoy;
        $this->finish = $this->hoy;
        $this->start1 = $this->hoy;
        $this->finish1 = $this->hoy;
        $this->cuenta_id1 = "";
        $this->cuenta_id = "";
        $this->categoria_id = "";
        $this->categoria_id1 ="";
        $this->tf1 = "";
        $this->tf = "";
        $this->tipo1 = "";
        $this->tipo = "";
        $this->dias1 = "";
        $this->dias = "";
        $this->documento = "";
        $this->documento1 = "";
        $this->resetPage();
    }

    public function validarFechas()
    {
        $inicio = Carbon::parse($this->start1);
        $fin = Carbon::parse($this->finish1);

        $y1 = Carbon::parse($this->start1)->format('Y');
        $y2 = Carbon::parse($this->finish1)->format('Y');

        if (($inicio->month !== $fin->month) | ($y1 !== $y2)){
            $this->emit('error', 'El rango de fechas debe ser del mismo mes.');
            return false;
        }else {
            return true;
        }

        // if ($inicio->day !== 1) {
        //     $this->start1 = $inicio->firstOfMonth()->format('Y-m-d');
        // }

        // if ($fin->day !== $fin->daysInMonth) {
        //     $this->finish1 = $fin->lastOfMonth()->format('Y-m-d');
        // }
    }

    public function reportePdfGeneral()
    {
        // try {
        //     return (new ReportController())->reportePdfGeneral($this->tipo, $this->categoria_id, $this->start, $this->finish);
        // } catch (\Throwable $th) {
        //     $this->emit('error', $th);
        // }

        $export = new ReportController();
        $export->reportePdfGeneral($this->tipo, $this->start, $this->finish);
    }

    public function reporteExcelDetalles()
    {

        return (new DetailsExport($this->tipo, $this->categoria_id, $this->start, $this->finish))->download('Detalle_de_Moviemientos_CC5.xlsx');
        // return Excel::download(new DetailsExport($tipo, $categoria_id, $start, $finish), 'invoices.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }
}
