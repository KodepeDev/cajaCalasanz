<?php

namespace App\Http\Livewire\Movimientos\Saldos;

use App\Models\Account;
use App\Models\Summary;
use Carbon\Carbon;
use Livewire\Component;

class SaldoComponent extends Component
{
    public $totalSaldo, $abonosFuturos, $retirosFuturos, $hoy, $liquidezMes1, $liquidezMes3, $liquidezMes6;

    public function mount()
    {
        $this->hoy = Carbon::now()->format('Y-m-d');
        $this->abonosFuturos = Summary::whereStatus('PAID')->where('date','>',$this->hoy)->where('type','add')->sum('amount');
        $this->retirosFuturos = Summary::whereStatus('PAID')->where('date','>',$this->hoy)->where('type','out')->sum('amount');

        // dd(Carbon::now()->addMonths(3)->endOfMonth());

        $this->liquidezMes1 = Summary::whereStatus('PAID')->where('date','<=',Carbon::now()->addMonth()->endOfMonth())->where('type','add')->sum('amount') - Summary::whereStatus('PAID')->where('date','<=',Carbon::now()->addMonths(2)->endOfMonth())->where('type','out')->sum('amount');
        $this->liquidezMes3 = Summary::whereStatus('PAID')->where('date','<=',Carbon::now()->addMonths(3)->endOfMonth())->where('type','add')->sum('amount') - Summary::whereStatus('PAID')->where('date','<=',Carbon::now()->addMonths(2)->endOfMonth())->where('type','out')->sum('amount');
        $this->liquidezMes6 = Summary::whereStatus('PAID')->where('date','<=',Carbon::now()->addMonths(6)->endOfMonth())->where('type','add')->sum('amount') - Summary::whereStatus('PAID')->where('date','<=',Carbon::now()->addMonths(2)->endOfMonth())->where('type','out')->sum('amount');
    }

    public function render()
    {
        // $hoy = Carbon::now()->format('Y-m-d');
        $cuentas = Account::all();
        $ingresos = Summary::whereStatus('PAID')->where('date','<=',$this->hoy)->where('type','add')->sum('amount');
        $egresos = Summary::whereStatus('PAID')->where('date','<=',$this->hoy)->where('type','out')->sum('amount');
        $this->totalSaldo = $ingresos - $egresos;
        $divisa = 'Soles';
        return view('livewire.movimientos.saldos.saldo-component', [
            'divisa'=>$divisa,
            'cuentas' => $cuentas,
        ])->extends('adminlte::page');
    }
}
