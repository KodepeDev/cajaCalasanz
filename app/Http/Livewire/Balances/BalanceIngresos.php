<?php

namespace App\Http\Livewire\Balances;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Summary;
use App\Models\Category;
use App\Exports\BalanceExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class BalanceIngresos extends Component
{
    public int $year;
    public $ultimosCincoAnios;

    // Kept public so exportExcelData() can access values set during the last render.
    // These are recomputed in exportExcelData() as well to avoid stale data.
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
        $this->totalYear     = Summary::whereStatus('PAID')->where('type', 'add')->whereYear('date', $this->year)->sum('amount');
        $this->resumenTotals = $this->buildResumenTotals();
        $this->mesTotals     = $this->buildMesTotals();

        return view('livewire.balances.balance-ingresos')->extends('adminlte::page');
    }

    public function exportExcelData()
    {
        $totalYear    = Summary::whereStatus('PAID')->where('type', 'add')->whereYear('date', $this->year)->sum('amount');
        $resumenTotals = $this->buildResumenTotals();
        $mesTotals    = $this->buildMesTotals();

        return Excel::download(
            new BalanceExport($mesTotals, $resumenTotals, 'livewire.balances.balance-ingresos', $this->year, $totalYear, $this->ultimosCincoAnios),
            'BalanceIngresos.xlsx',
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Private query builders
    // ─────────────────────────────────────────────────────────────

    private function buildResumenTotals(): array
    {
        return Detail::where('details.status', 1)
            ->where('details.summary_type', 'add')
            ->where('details.category_id', '!=', 1)
            ->join('summaries', 'details.summary_id', '=', 'summaries.id')
            ->whereYear('details.date_paid', $this->year)
            ->groupBy('details.category_id')
            ->selectRaw("details.category_id, {$this->monthlyPivotSql()}")
            ->get()
            ->toArray();
    }

    private function buildMesTotals(): array
    {
        return Detail::where('details.status', 1)
            ->where('details.summary_type', 'add')
            ->where('details.category_id', '!=', 1)
            ->join('summaries', 'details.summary_id', '=', 'summaries.id')
            ->whereYear('details.date_paid', $this->year)
            ->selectRaw($this->monthlyTotalSql())
            ->get()
            ->toArray();
    }

    /**
     * Generates 12 per-month SUM expressions aliased as jan_total … dec_total.
     * Handles USD → soles conversion via changed_amount.
     */
    private function monthlyPivotSql(): string
    {
        $aliases = ['jan_total','feb_total','mar_total','apr_total','may_total','jun_total',
                    'jul_total','ago_total','sep_total','oct_total','nov_total','dec_total'];

        return implode(",\n", array_map(
            fn(int $m, string $alias) =>
                "SUM(CASE WHEN MONTH(details.date_paid) = {$m} " .
                "THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END " .
                "ELSE 0 END) AS {$alias}",
            range(1, 12),
            $aliases,
        ));
    }

    /**
     * Generates 12 per-month SUM expressions aliased as total01 … total12.
     */
    private function monthlyTotalSql(): string
    {
        return implode(",\n", array_map(
            fn(int $m) =>
                "SUM(CASE WHEN MONTH(details.date_paid) = {$m} " .
                "THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END " .
                "ELSE 0 END) AS total" . str_pad($m, 2, '0', STR_PAD_LEFT),
            range(1, 12),
        ));
    }
}
