<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BalanceExport implements FromView
{
    public $mesTotals, $resumenTotals, $view;
    public $year, $totalYear, $ultimosCincoAnios;
    public function __construct($mesTotals, $resumenTotals, $view, $year, $totalYear, $ultimosCincoAnios)
    {
        $this->mesTotals = $mesTotals;
        $this->resumenTotals = $resumenTotals;
        $this->view = $view;
        $this->year = $year;
        $this->totalYear = $totalYear;
        $this->ultimosCincoAnios = $ultimosCincoAnios;

        // dd($this->mesTotals, $this->resumenTotals, $this->view);
    }
    public function view(): View
    {
        return view($this->view, [
            'mesTotals' => $this->mesTotals,
            'resumenTotals' => $this->resumenTotals,
            'year' => $this->year,
            'totalYear' => $this->totalYear,
            'ultimosCincoAnios' => $this->ultimosCincoAnios,
        ]);
    }
}
