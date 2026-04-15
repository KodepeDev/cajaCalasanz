<?php

namespace App\Http\Livewire\Balances;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Summary;
use App\Exports\BalanceExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class BalanceGlobal extends Component
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
        $this->totalYear     = $this->computeTotalYear();
        $this->resumenTotals = $this->buildResumenTotals();
        $this->mesTotals     = $this->buildMesTotals();

        return view('livewire.balances.balance-global')->extends('adminlte::page');
    }

    public function exportExcelData()
    {
        $totalYear     = $this->computeTotalYear();
        $resumenTotals = $this->buildResumenTotals();
        $mesTotals     = $this->buildMesTotals();

        return Excel::download(
            new BalanceExport($mesTotals, $resumenTotals, 'livewire.balances.balance-global', $this->year, $totalYear, $this->ultimosCincoAnios),
            'BalanceGlobal.xlsx',
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────

    private function computeTotalYear(): float
    {
        // Single aggregate query: income - expenses for the selected year
        $result = Summary::whereStatus('PAID')
            ->whereYear('date', $this->year)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'add' THEN amount ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END), 0) AS balance
            ")
            ->value('balance');

        return (float) ($result ?? 0);
    }

    private function buildResumenTotals(): array
    {
        return Detail::join('summaries', 'details.summary_id', '=', 'summaries.id')
            ->where('details.status', 1)
            ->where('details.category_id', '!=', 1)
            ->where('summaries.school_year_id', session('current_school_year_id'))
            ->whereYear('details.date_paid', $this->year)
            ->groupBy('details.category_id')
            ->selectRaw("details.category_id, {$this->monthlyPivotSql()}")
            ->get()
            ->toArray();
    }

    private function buildMesTotals(): array
    {
        return Detail::join('summaries', 'details.summary_id', '=', 'summaries.id')
            ->where('details.status', 1)
            ->where('details.category_id', '!=', 1)
            ->where('summaries.school_year_id', session('current_school_year_id'))
            ->whereYear('details.date_paid', $this->year)
            ->selectRaw($this->monthlyTotalSql())
            ->get()
            ->toArray();
    }

    /**
     * Generates signed per-month totals: income adds, expenses subtract.
     * Aliases: total01 … total12 (for resumenTotals, grouped by category).
     */
    private function monthlyPivotSql(): string
    {
        $expr = "CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END";

        return implode(",\n", array_map(
            fn(int $m) =>
                "SUM(CASE WHEN MONTH(details.date_paid) = {$m} " .
                "THEN CASE WHEN details.summary_type = 'add' THEN {$expr} ELSE -{$expr} END " .
                "ELSE 0 END) AS total" . str_pad($m, 2, '0', STR_PAD_LEFT),
            range(1, 12),
        ));
    }

    /**
     * Same logic but aliased as total_mes01 … total_mes12 (for mesTotals footer row).
     */
    private function monthlyTotalSql(): string
    {
        $expr = "CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END";

        return implode(",\n", array_map(
            fn(int $m) =>
                "SUM(CASE WHEN MONTH(details.date_paid) = {$m} " .
                "THEN CASE WHEN details.summary_type = 'add' THEN {$expr} ELSE -{$expr} END " .
                "ELSE 0 END) AS total_mes" . str_pad($m, 2, '0', STR_PAD_LEFT),
            range(1, 12),
        ));
    }
}
