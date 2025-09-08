<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use PDF;
use App\Models\Tour;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Attached;
use App\Models\Category;
use App\Models\Customer;
use App\Models\AttrValue;
use Livewire\WithPagination;

class ListadoMovimiento extends Component
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

     protected $queryString = [
        'start1'  => ['except' => ''],
        'finish1'  => ['except' => ''],
        'categoria_id1'  => ['except' => ''],
        'cuenta_id1'  => ['except' => ''],
        'tipo1' => ['except' => ''],
        'documento1' => ['except' => ''],
        'page' => ['except' => 1, 'as' => 'p'],
    ];


    public function mount()
    {

        $this->categories = Category::all();
        $this->tours = Tour::all();
        $this->accounts = Account::all();
        $this->divisa = 'Soles';

        $this->hoy = Carbon::now()->format('Y-m-d');

        $this->start1 = $this->hoy;
        $this->finish1 = $this->hoy;

        $this->tipo = '';
        $this->cuenta_id = '';
        $this->categoria_id = '';
        $this->documento = '';
        $this->start = $this->start1;
        $this->finish = $this->finish1;

    }

    public function render()
    {

        $summary = Summary::where('future','=',1)->whereDate('date','<=',$this->hoy);



        $filter=array();

        if($this->tipo != null) {

            if($this->tipo==1){

            $filter[] = array('category_id','=',$this->tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$this->hoy)->where('future','=',1);

            }else{

            $filter[] = array('type','=',$this->tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$this->hoy)->where('future','=',1);
            }
        }
        if($this->cuenta_id != null) {

            $filter[] = array('account_id','=',$this->cuenta_id);
            $summary = Summary::where($filter)->whereDate('date','<=',$this->hoy)->where('future','=',1);

        }
        if($this->documento != null) {

            $customer = Customer::where('document','=',$this->documento)->first();
            // dd($customer);
            if($customer){

                $filter[] = array('customer_id','=',$customer->id);
                $summary = Summary::where($filter)->whereDate('date','<=',$this->hoy)->where('future','=',1);
            }else{
                $this->emit('error', "No existe ningun registro de un cliente o proveedor con el documento: $this->documento");
            }

        }

        if($this->categoria_id != null) {

            $filter[] = array('category_id','=',$this->categoria_id);
            $summary = Summary::where($filter)->whereDate('date','<=',$this->hoy)->where('future','=',1);

        }
        if(isset($subcategorias)) {

            $filter[] = array('id_attr','=',$subcategorias);
            $summary = Summary::where($filter)->whereDate('date','<=',$this->hoy)->where('future','=',1);

        }

        if($this->tf != null) {

        $filter[] = array('tours_id','=',$this->tf);
        $summary = Summary::where($filter)->whereDate('date','<=',$this->hoy)->where('future','=',1);

        }

        if(isset($subcatetours)) {

            $filter[] = array('id_attr_tours','=',$subcatetours);
            $summary = Summary::where($filter)->whereDate('date','<=',$this->hoy)->where('future','=',1);

        }


        if((isset($this->start)) and (isset($this->finish))){
            // dd(isset($this->start));

            $start = Carbon::parse($this->start)->format('Y-m-d');
            $finish = Carbon::parse($this->finish)->format('Y-m-d');


            $summary = Summary::whereBetween('date', [$start, $finish])->where($filter)->where('future','=',1);

        }else{

            if($filter) {
                $summary = Summary::where('date','=',$this->hoy)->where('future','=',1)->where($filter);
            }else {
                $summary = Summary::where('date','=',$this->hoy)->where('future','=',1);
            }
        }

        $movim = $summary->get();

        $movimientos = $summary->paginate(15);

        // dd($movim);

        $sumaIngresos = $movim->where('status', 'PAID')->where('type','=', 'add')->sum('amount');
        $sumaEgresos = $movim->where('status', 'PAID')->where('type','=', 'out')->sum('amount');
        $sumaIngresosTx = $movim->where('status', 'PAID')->where('type','=', 'add')->sum('tax');
        $sumaEgresosTx = $movim->where('status', 'PAID')->where('type','=', 'out')->sum('tax');



        $this->totalFinal = $sumaIngresos - $sumaEgresos;
        $this->totalIngresosTx = $sumaIngresosTx;
        $this->totalEgresosTx = $sumaEgresosTx;

        return view('livewire.movimientos.listado-movimiento', ['summaries' => $movimientos])->extends('adminlte::page');
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

    public function anular($id)
    {
        $this->emitTo('movimientos.anular-movimiento', 'Anular', $id);
    }
}
