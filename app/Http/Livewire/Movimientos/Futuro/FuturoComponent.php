<?php

namespace App\Http\Livewire\Movimientos\Futuro;

use Carbon\Carbon;
use App\Models\Tour;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class FuturoComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $categories, $categoria, $accounts, $divisa;

    public $totalIngresosTx, $totalEgresosTx, $totalFinal, $hoy;

    public  $categorias, $subcategorias;

    //filters data

    public $start, $finish, $cuenta_id, $tipo;
    public $start1, $finish1, $cuenta_id1, $dias1, $tipo1;

    // public $summaries;


    public function mount()
    {
        $this->accounts = Account::all();
        $this->divisa = 'Soles';

        $this->hoy = Carbon::now()->format('Y-m-d');

        $this->start1 = "";
        $this->finish1 = "";

    }

    public function render()
    {

        $summary = Summary::whereDate('date','>',$this->hoy);



        $filter = array();

        if($this->tipo != null) {

            $filter[] = array('type','=',$this->tipo);
            $summary = Summary::where($filter)->whereDate('date','>',$this->hoy)->where('future','=',2);

        }

        if($this->cuenta_id != null) {

            $filter[] = array('account_id','=',$this->cuenta_id);
            $summary = Summary::where($filter)->whereDate('date','>',$this->hoy)->where('future','=',2);

        }


        if($this->start && $this->finish){

            // dd($this->start1 && $this->finish1);

            $start = Carbon::parse($this->start)->format('Y-m-d');
            $finish = Carbon::parse($this->finish)->format('Y-m-d');


            $summary = Summary::whereBetween('date', [$start, $finish])->where($filter)->where('future','=',2);

        }else{
            // dd('hola');

            if($filter) {
                $summary = Summary::where('date','>',$this->hoy)->where('future','=',2)->where($filter);
            }else {
                $summary = Summary::where('date','>',$this->hoy)->where('future','=',2);
            }
        }

        $movim = $summary->get();

        $movimientos = $summary->paginate(10);

        // dd($movimientos, $this->cuenta_id1);

        // dd($movim);

        $sumaIngresos = $movim->where('type','=', 'add')->sum('amount');
        $sumaEgresos = $movim->where('type','=', 'out')->sum('amount');
        $sumaIngresosTx = $movim->where('type','=', 'add')->sum('tax');
        $sumaEgresosTx = $movim->where('type','=', 'out')->sum('tax');



        $this->totalFinal = $sumaIngresos - $sumaEgresos;
        $this->totalIngresosTx = $sumaIngresosTx;
        $this->totalEgresosTx = $sumaEgresosTx;


        // dd($summaries);

        return view('livewire.movimientos.futuro.futuro-component', ['summaries' => $movimientos])->extends('adminlte::page');
    }

    public function Filter()
    {
        $rules = [
            'start1' => 'date',
            'finish1' => 'date|after_or_equal:'.$this->start1,
        ];

        $messages = [
            'start1.date' => 'Debe introducir una fecha válida',
            'finish1.date' => 'Debe introducir una fecha válida',
            'finish1.after_or_equal' => 'La fecha final debe ser igual o mayor a la fecha inicial',
        ];

        $this->validate($rules, $messages);

        $this->start = $this->start1;
        $this->finish = $this->finish1;
        $this->cuenta_id = $this->cuenta_id1;
        $this->tipo = $this->tipo1;
        $this->resetPage();
    }
    public function clearFilter()
    {
        $this->start = '';
        $this->finish = '';
        $this->cuenta_id = null;
        $this->tipo = null;

        $this->start1 = '';
        $this->finish1 = '';
        $this->cuenta_id1 = null;
        $this->tipo1 = null;
        $this->resetPage();

        $this->resetValidation();
    }
}
