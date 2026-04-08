<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Summary;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard()
    {
        $now = Carbon::now();
        $currentYear = $now->format('Y');
        $lastMonth = $now->copy()->subMonth();

        $socios = Student::where('is_active', true)->count();

        $saldo_actual = Summary::whereType('add')->whereStatus('PAID')->whereDate('date', '<=', $now)->sum('amount')
            - Summary::whereType('out')->whereStatus('PAID')->whereDate('date', '<=', $now)->sum('amount');

        $ingresos = Summary::whereType('add')->whereStatus('PAID')->whereYear('date', $currentYear)->sum('amount');
        $gastos = Summary::whereType('out')->whereStatus('PAID')->whereYear('date', $currentYear)->sum('amount');

        // Month-over-month comparisons
        $ingresos_mes = Summary::whereType('add')->whereStatus('PAID')
            ->whereYear('date', $now->year)->whereMonth('date', $now->month)->sum('amount');
        $ingresos_mes_anterior = Summary::whereType('add')->whereStatus('PAID')
            ->whereYear('date', $lastMonth->year)->whereMonth('date', $lastMonth->month)->sum('amount');

        $gastos_mes = Summary::whereType('out')->whereStatus('PAID')
            ->whereYear('date', $now->year)->whereMonth('date', $now->month)->sum('amount');
        $gastos_mes_anterior = Summary::whereType('out')->whereStatus('PAID')
            ->whereYear('date', $lastMonth->year)->whereMonth('date', $lastMonth->month)->sum('amount');

        // Pending payments count
        $pendientes = Summary::whereStatus('PENDING')->count();

        // Recent movements (last 8)
        $recientes = Summary::with(['student', 'user'])
            ->whereStatus('PAID')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        // Net balance of current year
        $balance_anual = $ingresos - $gastos;

        return view('admin.dashboard', compact(
            'socios', 'ingresos', 'gastos', 'saldo_actual',
            'ingresos_mes', 'ingresos_mes_anterior',
            'gastos_mes', 'gastos_mes_anterior',
            'pendientes', 'recientes', 'balance_anual'
        ));
    }
}
