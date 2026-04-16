<?php

namespace App\Http\Livewire\Account;

use App\Models\Account;
use App\Models\Summary;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class CuentaDetalle extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $account_id;
    public $start;
    public $finish;
    public $start1;
    public $finish1;

    public function mount(int $id): void
    {
        $this->account_id = $id;
        $this->applyCurrentMonth();
    }

    public function render()
    {
        $cuenta = Account::findOrFail($this->account_id);

        $baseQuery = Summary::where('status', 'PAID')
            ->where('account_id', $this->account_id)
            ->whereBetween('date', [$this->start, $this->finish]);

        $summary = (clone $baseQuery)->orderByDesc('date')->orderByDesc('id')->paginate(15);
        $ingreso = (clone $baseQuery)->where('type', 'add')->sum('amount');
        $egreso  = (clone $baseQuery)->where('type', 'out')->sum('amount');
        $totalf  = $ingreso - $egreso;

        return view('livewire.account.cuenta-detalle', [
            'cuenta'  => $cuenta,
            'summary' => $summary,
            'ingreso' => $ingreso,
            'egreso'  => $egreso,
            'totalf'  => $totalf,
        ])->extends('adminlte::page');
    }

    public function filter(): void
    {
        if ($this->validarFechas()) {
            $this->start  = $this->start1;
            $this->finish = $this->finish1;
            $this->resetPage();
        }
    }

    public function clearFilter(): void
    {
        $this->applyCurrentMonth();
        $this->resetPage();
    }

    private function validarFechas(): bool
    {
        $inicio = Carbon::parse($this->start1);
        $fin    = Carbon::parse($this->finish1);

        if ($inicio->month !== $fin->month || $inicio->year !== $fin->year) {
            $this->emit('error', 'El rango de fechas debe ser del mismo mes.');
            return false;
        }

        return true;
    }

    private function applyCurrentMonth(): void
    {
        $this->start  = $this->start1  = Carbon::today()->startOfMonth()->toDateString();
        $this->finish = $this->finish1 = Carbon::today()->endOfMonth()->toDateString();
    }
}
