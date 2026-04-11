<?php

namespace App\Http\Livewire\Movimientos\Components;

use App\Charts\MonthlySummariesChart;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\SchoolYear;

class GraficaMovimiento extends Component
{
    public $years = [];
    public $selectedYear;

    public function mount()
    {
        $this->years = DB::table("summaries")
            ->select(DB::raw("YEAR(date) as year"))
            ->groupBy("year")
            ->pluck("year");

        $this->selectedYear = SchoolYear::find(
            session("current_school_year_id"),
        )->year;
    }
    public function render(MonthlySummariesChart $chart)
    {
        return view("livewire.movimientos.components.grafica-movimiento", [
            "chartData" => $chart->build(),
            "years" => $this->years,
        ]);
    }
}
