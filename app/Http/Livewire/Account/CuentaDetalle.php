<?php

namespace App\Http\Livewire\Account;

use App\Models\Account;
use Carbon\Carbon;
use App\Models\Summary;
use Livewire\Component;
use Livewire\WithPagination;

class CuentaDetalle extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $hoy, $start, $finish, $account_id, $totalf;
    public $start1, $finish1;

    public function mount($id)
    {
        $this->hoy = Carbon::now()->format('Y-m-d');
        $this->account_id = $id;
        $this->fechaMes();
        $this->start1 = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $this->finish1 = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $cuenta = Account::findOrFail($this->account_id);


        // Crear consulta base para condiciones comunes
        $baseQuery = Summary::whereStatus('PAID')
        ->where('account_id', $this->account_id);

        // Agregar condiciones de fechas si están definidas
        if ($this->start && $this->finish) {
            $baseQuery->whereBetween('date', [$this->start, $this->finish]);
        } else {
            $baseQuery->where('date', '<=', $this->hoy);
        }

        // Obtener movimientos paginados
        $summary = (clone $baseQuery)->paginate(10);

        // Calcular ingresos y egresos
        $ingreso = (clone $baseQuery)->where('type', 'add')->sum('amount');
        $egreso = (clone $baseQuery)->where('type', 'out')->sum('amount');

        // Calcular total final
        $this->totalf = $ingreso - $egreso;

        return view('livewire.account.cuenta-detalle', compact('summary', 'cuenta'))->extends('adminlte::page');
    }

    public function Filter()
    {
        if($this->validarFechas()){
            $this->start = $this->start1;
            $this->finish = $this->finish1;
            $this->resetPage();
        }
    }
    public function clearFilter()
    {
        $this->fechaMes();
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

    public function fechaMes()
    {
        $inicio = Carbon::parse($this->hoy);
        $fin = Carbon::parse($this->hoy);
        $this->start = $inicio->firstOfMonth()->format('Y-m-d');
        $this->start1 = $inicio->firstOfMonth()->format('Y-m-d');
        $this->finish = $fin->endOfMonth()->format('Y-m-d');
        $this->finish1 = $fin->endOfMonth()->format('Y-m-d');
    }
}
