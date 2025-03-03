<?php

namespace App\Charts;

use Carbon\Carbon;
use App\Models\Summary;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlySummariesChart
{
    protected $chart;
    public $selectedYear;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\barChart
    {
        $this->selectedYear = Carbon::now()->format('Y');

        $incomesMesTotal = Summary::whereStatus('PAID')->where('type', 'add')->selectRaw('
                    SUM(CASE WHEN MONTH(date) = 1 THEN amount ELSE 0 END) AS in_01,
                    SUM(CASE WHEN MONTH(date) = 2 THEN amount ELSE 0 END) AS in_02,
                    SUM(CASE WHEN MONTH(date) = 3 THEN amount ELSE 0 END) AS in_03,
                    SUM(CASE WHEN MONTH(date) = 4 THEN amount ELSE 0 END) AS in_04,
                    SUM(CASE WHEN MONTH(date) = 5 THEN amount ELSE 0 END) AS in_05,
                    SUM(CASE WHEN MONTH(date) = 6 THEN amount ELSE 0 END) AS in_06,
                    SUM(CASE WHEN MONTH(date) = 7 THEN amount ELSE 0 END) AS in_07,
                    SUM(CASE WHEN MONTH(date) = 8 THEN amount ELSE 0 END) AS in_08,
                    SUM(CASE WHEN MONTH(date) = 9 THEN amount ELSE 0 END) AS in_09,
                    SUM(CASE WHEN MONTH(date) = 10 THEN amount ELSE 0 END) AS in_10,
                    SUM(CASE WHEN MONTH(date) = 11 THEN amount ELSE 0 END) AS in_11,
                    SUM(CASE WHEN MONTH(date) = 12 THEN amount ELSE 0 END) AS in_12')
            ->whereYear('date', '=', $this->selectedYear)
            ->get();
        $ingresosPorMes = $incomesMesTotal->toArray();


        $expensesMesTotal = Summary::whereStatus('PAID')->where('type', 'out')->selectRaw('
                    SUM(CASE WHEN MONTH(date) = 1 THEN amount ELSE 0 END) AS total01,
                    SUM(CASE WHEN MONTH(date) = 2 THEN amount ELSE 0 END) AS total02,
                    SUM(CASE WHEN MONTH(date) = 3 THEN amount ELSE 0 END) AS total03,
                    SUM(CASE WHEN MONTH(date) = 4 THEN amount ELSE 0 END) AS total04,
                    SUM(CASE WHEN MONTH(date) = 5 THEN amount ELSE 0 END) AS total05,
                    SUM(CASE WHEN MONTH(date) = 6 THEN amount ELSE 0 END) AS total06,
                    SUM(CASE WHEN MONTH(date) = 7 THEN amount ELSE 0 END) AS total07,
                    SUM(CASE WHEN MONTH(date) = 8 THEN amount ELSE 0 END) AS total08,
                    SUM(CASE WHEN MONTH(date) = 9 THEN amount ELSE 0 END) AS total09,
                    SUM(CASE WHEN MONTH(date) = 10 THEN amount ELSE 0 END) AS total10,
                    SUM(CASE WHEN MONTH(date) = 11 THEN amount ELSE 0 END) AS total11,
                    SUM(CASE WHEN MONTH(date) = 12 THEN amount ELSE 0 END) AS total12')
            ->whereYear('date', '=', $this->selectedYear)
            ->get();

        $gastosPorMes = $expensesMesTotal->toArray();

        $ingresos = array_values(array_column($ingresosPorMes[0], null));
        $gastos = array_values(array_column($gastosPorMes[0], null));


        return $this->chart->barChart()
            ->setTitle('Ingreos y Gastos')
            ->setSubtitle('Ingreos y Gastos por Mes del Año Actual')
            ->addData('Ingresos', [round($ingresos[0], 2), round($ingresos[1], 2), round($ingresos[2], 2), round($ingresos[3], 2), round($ingresos[4], 2), round($ingresos[5], 2), round($ingresos[6], 2), round($ingresos[7], 2),round($ingresos[8], 2), round($ingresos[9], 2), round($ingresos[10], 2), round($ingresos[11], 2)])
            ->addData('Gastos', [round($gastos[0], 2), round($gastos[1], 2), round($gastos[2], 2), round($gastos[3], 2), round($gastos[4], 2), round($gastos[5], 2), round($gastos[6], 2), round($gastos[7], 2),round($gastos[8], 2), round($gastos[9], 2), round($gastos[10], 2), round($gastos[11], 2)])
            ->setColors(['#02de48', '#ff6384'])
            ->setFontColor('#deb902')
            ->setXAxis(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'])
            ->setGrid('#3F51B5', 0.1)
            ->setMarkers(['#02de48', '#ff6384'], 7, 10);
    }
}
