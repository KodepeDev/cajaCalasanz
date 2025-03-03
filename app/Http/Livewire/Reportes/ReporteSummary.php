<?php

namespace App\Http\Livewire\Reportes;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class ReporteSummary extends Component
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

        $filter=array();
        $filter_detail=array();

        $summary = Summary::with(['details' => function ($query)  use ($filter_detail) {
            $query->where($filter_detail);
        }])->whereStatus('PAID')->whereDate('date','<=',$this->hoy)->get();

        // dd($summary);


        if($this->tipo != null) {

            if($this->tipo=='add'){

            $filter[] = array('type','=',$this->tipo);
            $summary = Summary::with(['details' => function ($query)  use ($filter_detail) {
                $query->where($filter_detail);
            }])->whereStatus('PAID')->where($filter)->whereDate('date','<=',$this->hoy);

            }else{

            $filter[] = array('type','=',$this->tipo);
            $summary = Summary::with(['details' => function ($query)  use ($filter_detail) {
                $query->where($filter_detail);
            }])->whereStatus('PAID')->where($filter)->whereDate('date','<=',$this->hoy);
            }
        }

        if($this->categoria_id != null) {

            $filter_detail[] = array('category_id','=',$this->categoria_id);
            $summary = Summary::with(['details' => function ($query)  use ($filter_detail) {
                $query->where($filter_detail);
            }])->whereStatus('PAID')->where($filter)->whereDate('date','<=',$this->hoy);

            // dd($summary);

        }


        if((isset($this->start)) and (isset($this->finish))){
            // dd(isset($this->start));

            $start = Carbon::parse($this->start)->format('Y-m-d');
            $finish = Carbon::parse($this->finish)->format('Y-m-d');


            $summary = Summary::with(['details' => function ($query)  use ($filter_detail) {
                $query->where($filter_detail);
            }])->whereStatus('PAID')->whereBetween('date', [$start, $finish])->where($filter);

        }else{

            if($filter) {
                $summary = Summary::with(['details' => function ($query)  use ($filter_detail) {
                    $query->where($filter_detail);
                }])->whereStatus('PAID')->where('date','=',$this->hoy)->where($filter);
            }else {
                $summary = Summary::with(['details' => function ($query)  use ($filter_detail) {
                    $query->where($filter_detail);
                }])->whereStatus('PAID')->where('date','=',$this->hoy);
            }
        }


        $movim = $summary->get();
        $this->dataReporte = $summary->get();

        $movimientos = $summary->get();

        // dd($movim);

        $sumaIngresos = $movim->where('type','=', 'add')->sum('amount');
        $sumaEgresos = $movim->where('type','=', 'out')->sum('amount');



        $this->totalFinal = $sumaIngresos - $sumaEgresos;

        return view('livewire.reportes.reporte-summary', ['summaries' => $movimientos])->extends('adminlte::page');
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
}
