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
        $socios = Student::where('is_active', true)->count();

        $saldo_actual = Summary::whereType('add')->whereStatus('PAID')->whereDate('date', '<=', Carbon::now())->sum('amount') - Summary::whereType('out')->whereStatus('PAID')->whereDate('date', '<=', Carbon::now())->sum('amount');

        $ingresos = Summary::whereType('add')->whereStatus('PAID')->whereYear('date', '=', Carbon::now()->format('Y'))->sum('amount');
        // $ingresos = Summary::whereType('add')->whereStatus('PAID')->whereDate('date', '<=', Carbon::now())->sum('amount');

        $gastos = Summary::whereType('out')->whereStatus('PAID')->whereYear('date', '=', Carbon::now()->format('Y'))->sum('amount');
        // $gastos = Summary::whereType('out')->whereStatus('PAID')->whereDate('date', '<=', Carbon::now())->sum('amount');
        return view('admin.dashboard', compact('socios', 'ingresos', 'gastos', 'saldo_actual'));
    }
}
