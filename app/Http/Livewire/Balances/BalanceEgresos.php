<?php

namespace App\Http\Livewire\Balances;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Summary;
use App\Exports\BalanceExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class BalanceEgresos extends Component
{
    public int $year;
    public $ultimosCincoAnios;

    public array $mesTotals    = [];
    public array $resumenTotals = [];
    public float $totalYear    = 0;

    public function mount(): void
    {
        $this->year              = (int) Carbon::now()->format('Y');
        $this->ultimosCincoAnios = collect(range(date('Y'), date('Y') - 4));
    }

    public function render()
    {
        $this->totalYear     = Summary::whereStatus('PAID')->where('type', 'out')->whereYear('date', $this->year)->sum('amount');
        $this->resumenTotals = $this->buildResumenTotals();
        $this->mesTotals     = $this->buildMesTotals();

        return view('livewire.balances.balance-egresos')->extends('adminlte::page');
    }

    public function exportExcelData()
    {
        $totalYear     = Summary::whereStatus('PAID')->where('type', 'out')->whereYear('date', $this->year)->sum('amount');
        $resumenTotals = $this->buildResumenTotals();
        $mesTotals     = $this->buildMesTotals();

        return Excel::download(
            new BalanceExport($mesTotals, $resumenTotals, 'livewire.balances.balance-egresos', $this->year, $totalYear, $this->ultimosCincoAnios),
            'BalanceGastos.xlsx',
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Private query builders
    // ─────────────────────────────────────────────────────────────

    private function buildResumenTotals(): array
    {
        return Detail::whereStatus(1)
            ->where('summary_type', 'out')
            ->where('category_id', '!=', 1)
            ->whereYear('date_paid', $this->year)
            ->groupBy('category_id')
            ->selectRaw("category_id, {$this->monthlyPivotSql()}")
            ->get()
            ->toArray();
    }

    private function buildMesTotals(): array
    {
        return Detail::whereStatus(1)
            ->where('summary_type', 'out')
            ->where('category_id', '!=', 1)
            ->whereYear('date_paid', $this->year)
            ->selectRaw($this->monthlyTotalSql())
            ->get()
            ->toArray();
    }

    /**
     * Generates 12 per-month SUM expressions aliased as jan_total … dic_total.
     * December alias kept as "dic_total" to match the existing view and export.
     */
    private function monthlyPivotSql(): string
    {
        $aliases = ['jan_total','feb_total','mar_total','apr_total','may_total','jun_total',
                    'jul_total','ago_total','sep_total','oct_total','nov_total','dic_total'];

        return implode(",\n", array_map(
            fn(int $m, string $alias) =>
                "SUM(CASE WHEN MONTH(date_paid) = {$m} " .
                "THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END " .
                "ELSE 0 END) AS {$alias}",
            range(1, 12),
            $aliases,
        ));
    }

    private function monthlyTotalSql(): string
    {
        return implode(",\n", array_map(
            fn(int $m) =>
                "SUM(CASE WHEN MONTH(date_paid) = {$m} " .
                "THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END " .
                "ELSE 0 END) AS total" . str_pad($m, 2, '0', STR_PAD_LEFT),
            range(1, 12),
        ));
    }
}
