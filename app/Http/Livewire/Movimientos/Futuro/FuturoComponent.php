<?php

namespace App\Http\Livewire\Movimientos\Futuro;

use App\Models\Account;
use App\Models\Summary;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class FuturoComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $start     = '';
    public $finish    = '';
    public $cuenta_id = null;
    public $tipo      = null;

    public $start1    = '';
    public $finish1   = '';
    public $cuenta_id1 = null;
    public $tipo1     = null;

    public function render()
    {
        $today = Carbon::today()->toDateString();

        $query = Summary::with(['account', 'category'])->where('future', 2);

        if ($this->tipo) {
            $query->where('type', $this->tipo);
        }
        if ($this->cuenta_id) {
            $query->where('account_id', $this->cuenta_id);
        }
        if ($this->start && $this->finish) {
            $query->whereBetween('date', [$this->start, $this->finish]);
        } else {
            $query->where('date', '>', $today);
        }

        $summaries  = (clone $query)->orderByDesc('date')->orderByDesc('id')->paginate(15);
        $totalAdd   = (clone $query)->where('type', 'add')->sum('amount');
        $totalOut   = (clone $query)->where('type', 'out')->sum('amount');
        $totalFinal = $totalAdd - $totalOut;
        $totalAddTax = (clone $query)->where('type', 'add')->sum('tax');
        $totalOutTax = (clone $query)->where('type', 'out')->sum('tax');

        return view('livewire.movimientos.futuro.futuro-component', [
            'summaries'   => $summaries,
            'accounts'    => Account::all(),
            'totalAdd'    => $totalAdd,
            'totalOut'    => $totalOut,
            'totalFinal'  => $totalFinal,
            'totalAddTax' => $totalAddTax,
            'totalOutTax' => $totalOutTax,
        ])->extends('adminlte::page');
    }

    public function filter(): void
    {
        $this->validate([
            'start1'  => 'nullable|date',
            'finish1' => 'nullable|date|after_or_equal:start1',
        ], [
            'finish1.after_or_equal' => 'La fecha final debe ser igual o mayor a la fecha inicial.',
        ]);

        $this->start     = $this->start1;
        $this->finish    = $this->finish1;
        $this->cuenta_id = $this->cuenta_id1;
        $this->tipo      = $this->tipo1;
        $this->resetPage();
    }

    public function clearFilter(): void
    {
        $this->reset(['start', 'finish', 'cuenta_id', 'tipo', 'start1', 'finish1', 'cuenta_id1', 'tipo1']);
        $this->resetValidation();
        $this->resetPage();
    }
}
