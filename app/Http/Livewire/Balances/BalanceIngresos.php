<?php

namespace App\Http\Livewire\Balances;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Category;
use App\Exports\BalanceExport;
use Maatwebsite\Excel\Facades\Excel;

class BalanceIngresos extends Component
{
    public $years = [];
    public $year, $totalYear, $ultimosCincoAnios;
    public $mesTotals, $resumenTotals;

    public function mount()
    {
        $this->year = Carbon::now()->format('Y');

        $this->ultimosCincoAnios = collect(range(date('Y'), date('Y') - 4));

        for ($i = 0; $i < 5; $i++) {
            $this->years[$this->year - $i] = $this->year - $i;
        }

    }
    public function render()
    {
        $summaries = Summary::whereStatus('PAID')->where('type', 'add')->whereYear('date', '=', $this->year)->get();
        $this->totalYear = $summaries->sum('amount');

        $this->resumenTotals = Detail::where('details.status', 1)
            ->where('summary_type', 'add')
            ->join('summaries', 'details.summary_id', '=', 'summaries.id') // Realiza el join con la tabla summaries
            ->selectRaw('category_id,
                SUM(CASE WHEN MONTH(date_paid) = 1 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS jan_total,
                SUM(CASE WHEN MONTH(date_paid) = 2 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS feb_total,
                SUM(CASE WHEN MONTH(date_paid) = 3 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS mar_total,
                SUM(CASE WHEN MONTH(date_paid) = 4 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS apr_total,
                SUM(CASE WHEN MONTH(date_paid) = 5 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS may_total,
                SUM(CASE WHEN MONTH(date_paid) = 6 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS jun_total,
                SUM(CASE WHEN MONTH(date_paid) = 7 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS jul_total,
                SUM(CASE WHEN MONTH(date_paid) = 8 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS ago_total,
                SUM(CASE WHEN MONTH(date_paid) = 9 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS sep_total,
                SUM(CASE WHEN MONTH(date_paid) = 10 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS oct_total,
                SUM(CASE WHEN MONTH(date_paid) = 11 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS nov_total,
                SUM(CASE WHEN MONTH(date_paid) = 12 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS dec_total'
            )
            ->whereYear('date_paid', '=', $this->year)
            ->groupBy('category_id')
            ->get()
            ->toArray();
        // $incomesMesTotal = Detail::whereStatus(1)->where('summary_type', 'add')->selectRaw('
        //             SUM(CASE WHEN MONTH(date_paid) = 1 THEN amount ELSE 0 END) AS total01,
        //             SUM(CASE WHEN MONTH(date_paid) = 2 THEN amount ELSE 0 END) AS total02,
        //             SUM(CASE WHEN MONTH(date_paid) = 3 THEN amount ELSE 0 END) AS total03,
        //             SUM(CASE WHEN MONTH(date_paid) = 4 THEN amount ELSE 0 END) AS total04,
        //             SUM(CASE WHEN MONTH(date_paid) = 5 THEN amount ELSE 0 END) AS total05,
        //             SUM(CASE WHEN MONTH(date_paid) = 6 THEN amount ELSE 0 END) AS total06,
        //             SUM(CASE WHEN MONTH(date_paid) = 7 THEN amount ELSE 0 END) AS total07,
        //             SUM(CASE WHEN MONTH(date_paid) = 8 THEN amount ELSE 0 END) AS total08,
        //             SUM(CASE WHEN MONTH(date_paid) = 9 THEN amount ELSE 0 END) AS total09,
        //             SUM(CASE WHEN MONTH(date_paid) = 10 THEN amount ELSE 0 END) AS total10,
        //             SUM(CASE WHEN MONTH(date_paid) = 11 THEN amount ELSE 0 END) AS total11,
        //             SUM(CASE WHEN MONTH(date_paid) = 12 THEN amount ELSE 0 END) AS total12')
        //     ->whereYear('date_paid', '=', $this->year)
        //     ->get();
        $this->mesTotals = Detail::where('details.status', 1)
            ->where('summary_type', 'add')
            ->join('summaries', 'details.summary_id', '=', 'summaries.id') // Realiza el join con la tabla summaries
            ->selectRaw('
                SUM(CASE WHEN MONTH(date_paid) = 1 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total01,
                SUM(CASE WHEN MONTH(date_paid) = 2 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total02,
                SUM(CASE WHEN MONTH(date_paid) = 3 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total03,
                SUM(CASE WHEN MONTH(date_paid) = 4 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total04,
                SUM(CASE WHEN MONTH(date_paid) = 5 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total05,
                SUM(CASE WHEN MONTH(date_paid) = 6 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total06,
                SUM(CASE WHEN MONTH(date_paid) = 7 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total07,
                SUM(CASE WHEN MONTH(date_paid) = 8 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total08,
                SUM(CASE WHEN MONTH(date_paid) = 9 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total09,
                SUM(CASE WHEN MONTH(date_paid) = 10 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total10,
                SUM(CASE WHEN MONTH(date_paid) = 11 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total11,
                SUM(CASE WHEN MONTH(date_paid) = 12 THEN CASE WHEN currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE 0 END) AS total12'
            )
            ->whereYear('date_paid', '=', $this->year)
            ->get()
            ->toArray();
            // dd($incomesMesTotal);

            // dd($this->mesTotals, $this->resumenTotals);
        return view('livewire.balances.balance-ingresos')->extends('adminlte::page');
    }

    public function exportExcelData()
    {
        // dd($this->mesTotals, $this->resumenTotals);
        return Excel::download(new BalanceExport($this->mesTotals, $this->resumenTotals, 'livewire.balances.balance-ingresos', $this->year, $this->totalYear, $this->ultimosCincoAnios), 'BalanceIngresos.xlsx');
    }
}
