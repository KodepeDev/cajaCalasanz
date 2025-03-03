<?php

namespace App\Http\Livewire\Balances;

use App\Exports\BalanceExport;
use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BalanceGlobal extends Component
{
    public $year, $totalYear, $ultimosCincoAnios;

    public $mesTotals, $resumenTotals;

    public function mount()
    {
        $this->year = Carbon::now()->format('Y');

        $this->ultimosCincoAnios = collect(range(date('Y'), date('Y') - 4));

    }

    public function render()
    {
        $this->totalYear = Summary::whereStatus('PAID')->where('type', 'add')->sum('amount') - Summary::whereStatus('PAID')->where('type', 'out')->whereYear('date', '=', $this->year)->sum('amount');

        // $mesTotals = Detail::whereStatus(1)->where('category_id', '!=', 1)->selectRaw('
        //             SUM(CASE WHEN MONTH(date_paid) = 1 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes01,
        //             SUM(CASE WHEN MONTH(date_paid) = 2 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes02,
        //             SUM(CASE WHEN MONTH(date_paid) = 3 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes03,
        //             SUM(CASE WHEN MONTH(date_paid) = 4 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes04,
        //             SUM(CASE WHEN MONTH(date_paid) = 5 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes05,
        //             SUM(CASE WHEN MONTH(date_paid) = 6 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes06,
        //             SUM(CASE WHEN MONTH(date_paid) = 7 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes07,
        //             SUM(CASE WHEN MONTH(date_paid) = 8 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes08,
        //             SUM(CASE WHEN MONTH(date_paid) = 9 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes09,
        //             SUM(CASE WHEN MONTH(date_paid) = 10 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes10,
        //             SUM(CASE WHEN MONTH(date_paid) = 11 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes11,
        //             SUM(CASE WHEN MONTH(date_paid) = 12 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total_mes12')
        //     ->whereYear('date_paid', '=', $this->year)
        //     ->get();

        $this->mesTotals = Detail::join('summaries', 'details.summary_id', '=', 'summaries.id')
            ->where('details.status', 1)
            ->where('details.category_id', '!=', 1)
            ->selectRaw('
                SUM(CASE WHEN MONTH(details.date_paid) = 1 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes01,
                SUM(CASE WHEN MONTH(details.date_paid) = 2 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes02,
                SUM(CASE WHEN MONTH(details.date_paid) = 3 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes03,
                SUM(CASE WHEN MONTH(details.date_paid) = 4 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes04,
                SUM(CASE WHEN MONTH(details.date_paid) = 5 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes05,
                SUM(CASE WHEN MONTH(details.date_paid) = 6 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes06,
                SUM(CASE WHEN MONTH(details.date_paid) = 7 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes07,
                SUM(CASE WHEN MONTH(details.date_paid) = 8 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes08,
                SUM(CASE WHEN MONTH(details.date_paid) = 9 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes09,
                SUM(CASE WHEN MONTH(details.date_paid) = 10 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes10,
                SUM(CASE WHEN MONTH(details.date_paid) = 11 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes11,
                SUM(CASE WHEN MONTH(details.date_paid) = 12 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total_mes12
            ')
            ->whereYear('details.date_paid', '=', $this->year)
            ->get()
            ->toArray();

        // $resumenTotals = Detail::whereStatus(1)->where('category_id', '!=', 1)->selectRaw('category_id,
        //                 SUM(CASE WHEN MONTH(date_paid) = 1 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total01,
        //                 SUM(CASE WHEN MONTH(date_paid) = 2 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total02,
        //                 SUM(CASE WHEN MONTH(date_paid) = 3 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total03,
        //                 SUM(CASE WHEN MONTH(date_paid) = 4 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total04,
        //                 SUM(CASE WHEN MONTH(date_paid) = 5 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total05,
        //                 SUM(CASE WHEN MONTH(date_paid) = 6 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total06,
        //                 SUM(CASE WHEN MONTH(date_paid) = 7 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total07,
        //                 SUM(CASE WHEN MONTH(date_paid) = 8 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total08,
        //                 SUM(CASE WHEN MONTH(date_paid) = 9 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total09,
        //                 SUM(CASE WHEN MONTH(date_paid) = 10 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total10,
        //                 SUM(CASE WHEN MONTH(date_paid) = 11 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total11,
        //                 SUM(CASE WHEN MONTH(date_paid) = 12 THEN CASE WHEN(summary_type)="add" THEN amount ELSE -amount END ELSE 0 END) AS total12')
        //         ->whereYear('date_paid', '=', $this->year)
        //         ->groupBy('category_id')
        //         ->get();

        $this->resumenTotals = Detail::join('summaries', 'details.summary_id', '=', 'summaries.id')
            ->where('details.status', 1)
            ->where('details.category_id', '!=', 1)
            ->selectRaw('category_id,
                SUM(CASE WHEN MONTH(details.date_paid) = 1 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total01,
                SUM(CASE WHEN MONTH(details.date_paid) = 2 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total02,
                SUM(CASE WHEN MONTH(details.date_paid) = 3 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total03,
                SUM(CASE WHEN MONTH(details.date_paid) = 4 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total04,
                SUM(CASE WHEN MONTH(details.date_paid) = 5 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total05,
                SUM(CASE WHEN MONTH(details.date_paid) = 6 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total06,
                SUM(CASE WHEN MONTH(details.date_paid) = 7 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total07,
                SUM(CASE WHEN MONTH(details.date_paid) = 8 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total08,
                SUM(CASE WHEN MONTH(details.date_paid) = 9 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total09,
                SUM(CASE WHEN MONTH(details.date_paid) = 10 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total10,
                SUM(CASE WHEN MONTH(details.date_paid) = 11 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total11,
                SUM(CASE WHEN MONTH(details.date_paid) = 12 THEN CASE WHEN(details.summary_type) = "add" THEN CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END ELSE -CASE WHEN details.currency_id = 2 THEN details.changed_amount ELSE details.amount END END ELSE 0 END) AS total12
            ')
            ->whereYear('details.date_paid', '=', $this->year)
            ->groupBy('category_id')
            ->get()
            ->toArray();

            // dd($this->mesTotals, $this->resumenTotals);

        // return view('livewire.balances.balance-global', compact('mesTotals', 'resumenTotals'))->extends('adminlte::page');
        return view('livewire.balances.balance-global',)->extends('adminlte::page');
    }

    public function exportExcelData()
    {
        // dd($this->mesTotals, $this->resumenTotals);
        return Excel::download(new BalanceExport($this->mesTotals, $this->resumenTotals, 'livewire.balances.balance-global', $this->year, $this->totalYear, $this->ultimosCincoAnios), 'BalanceGlobal.xlsx');
    }
}
