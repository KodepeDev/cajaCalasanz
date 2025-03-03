<?php

namespace App\Http\Livewire\Movimientos\Components;

use App\Charts\MonthlySummariesChart;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class GraficaMovimiento extends Component
{

    public $years = [];
    public $selectedYear;

    public function mount()
    {
        $this->years = DB::table('summaries')
            ->select(DB::raw('YEAR(date) as year'))
            ->groupBy('year')
            ->pluck('year');

        $this->selectedYear = date('Y');
    }
    public function render(MonthlySummariesChart $chart)
    {

        return view('livewire.movimientos.components.grafica-movimiento', [
            'chartData' => $chart->build(),
            'years' => $this->years,
        ]);
    }
}
