<?php

namespace App\Http\Livewire\Movimientos\Saldos;

use App\Models\Account;
use App\Models\Summary;
use Carbon\Carbon;
use Livewire\Component;

class SaldoComponent extends Component
{
    public function render()
    {
        $today = Carbon::today()->toDateString();

        // Per-account current balance — single GROUP BY query (no N+1)
        $balancesByAccount = Summary::where('status', 'PAID')
            ->where('date', '<=', $today)
            ->selectRaw("account_id, SUM(CASE WHEN type='add' THEN amount ELSE -amount END) as net")
            ->groupBy('account_id')
            ->pluck('net', 'account_id');

        $accounts = Account::all()->each(function (Account $account) use ($balancesByAccount) {
            $account->setAttribute('balance', (float) ($balancesByAccount[$account->id] ?? 0));
        });

        $totalSaldo = $accounts->sum('balance');

        // Future movements — 2 aggregate queries
        $futureBase = Summary::where('status', 'PAID')->where('date', '>', $today);
        $futureAdd  = (clone $futureBase)->where('type', 'add')->sum('amount');
        $futureOut  = (clone $futureBase)->where('type', 'out')->sum('amount');
        $futureNet  = $futureAdd - $futureOut;

        // Liquidity projections — cumulative net (add − out) up to each horizon
        $liquidityAt = fn (Carbon $date): float => (float) Summary::where('status', 'PAID')
            ->where('date', '<=', $date->toDateString())
            ->selectRaw("SUM(CASE WHEN type='add' THEN amount ELSE -amount END) as net")
            ->value('net');

        $horizons = [
            ['label' => '1 mes',   'date' => Carbon::today()->addMonth()->endOfMonth()],
            ['label' => '3 meses', 'date' => Carbon::today()->addMonths(3)->endOfMonth()],
            ['label' => '6 meses', 'date' => Carbon::today()->addMonths(6)->endOfMonth()],
        ];

        foreach ($horizons as &$h) {
            $h['value'] = $liquidityAt($h['date']);
        }
        unset($h);

        return view('livewire.movimientos.saldos.saldo-component', [
            'accounts'   => $accounts,
            'totalSaldo' => $totalSaldo,
            'futureAdd'  => $futureAdd,
            'futureOut'  => $futureOut,
            'futureNet'  => $futureNet,
            'horizons'   => $horizons,
            'today'      => $today,
        ])->extends('adminlte::page');
    }
}
