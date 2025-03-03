<?php

namespace App\Http\Livewire\CierreMoviemientos;

use App\Models\SummaryClose;
use Carbon\Carbon;
use Livewire\Component;

class SummaryCloseComponent extends Component
{
    public $closes;

    public function mount()
    {
        $year = Carbon::now()->format('Y');
        $this->closes = SummaryClose::whereYear('to_date', $year)->get();
    }

    public function render()
    {
        return view('livewire.cierre-moviemientos.summary-close-component')->extends('adminlte::page');
    }
}
