<?php

namespace App\Http\Livewire\Socios\Detalles;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Student;
use App\Models\Summary;
use App\Models\SchoolYear;
use Livewire\Component;
use Livewire\WithPagination;

class StudentDetails extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'render',
        'eliminarDetalle' => 'delete',
    ];

    public $student;
    public $url;
    public $selected_id;
    public $year;
    public $detalle;

    // Filter inputs (1 = bound to query, without suffix = input binding)
    public $start;
    public $finish;
    public $start1;
    public $finish1;

    // ─────────────────────────────────────────────────────────────
    // Lifecycle
    // ─────────────────────────────────────────────────────────────

    public function mount($id)
    {
        $hoy = Carbon::now();

        $this->selected_id = $id;
        $this->student     = Student::find($id);
        $this->year        = SchoolYear::find(session('current_school_year_id'))->year ?? date('Y');

        $this->finish = $this->finish1 = $hoy->format('Y-m-d');
        $this->start  = $this->start1  = $hoy->firstOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $start  = Carbon::parse($this->start)->format('Y-m-d');
        $finish = Carbon::parse($this->finish)->format('Y-m-d');

        $this->url = $this->validarFechas()
            ? '/admin/students/reportePDF/?student=' . $this->student->id
                . '&start='  . $this->start1
                . '&finish=' . $this->finish1
                . '&year='   . $this->year
            : null;

        return view('livewire.socios.detalles.student-details', [
            'movimientos'             => $this->queryMovimientos($start, $finish),
            'suma2023'                => $this->sumaSummary($this->year),
            'suma2022'                => $this->sumaSummary($this->year - 1),
            'sumaTotal'               => $this->sumaDetalles($start, $finish, status: 1, dolar: false),
            'sumaTotalDolar'          => $this->sumaDetalles($start, $finish, status: 1, dolar: true),
            'sumaTotalPendiente'      => $this->sumaDetalles($start, $finish, status: 0, dolar: false),
            'sumaTotalPendienteDolar' => $this->sumaDetalles($start, $finish, status: 0, dolar: true),
        ])->extends('adminlte::page');
    }

    // ─────────────────────────────────────────────────────────────
    // Actions
    // ─────────────────────────────────────────────────────────────

    public function delete($id)
    {
        Detail::findOrFail($id)->delete();
        $this->emit('eliminado', 'Registro eliminado satisfactoriamente');
    }

    public function Filter()
    {
        if ($this->validarFechas()) {
            $this->start  = $this->start1;
            $this->finish = $this->finish1;
            $this->resetPage();
        }
    }

    public function clearFilter()
    {
        $hoy = Carbon::now();

        $this->finish = $this->finish1 = $hoy->format('Y-m-d');
        $this->start  = $this->start1  = $hoy->firstOfYear()->format('Y-m-d');
        $this->resetPage();
    }

    public function showDetail($id)
    {
        $this->detalle = Detail::find($id);
        $this->emit('showDetail', 'mostrar detalle');
    }

    public function resetDetail()
    {
        $this->detalle = [];
        $this->emit('close_modal', 'cerrar detalle');
    }

    // ─────────────────────────────────────────────────────────────
    // Validation
    // ─────────────────────────────────────────────────────────────

    public function validarFechas(): bool
    {
        $inicio = Carbon::parse($this->start1);
        $fin    = Carbon::parse($this->finish1);

        if ($inicio->year !== $fin->year) {
            $this->emit('error', 'El rango de consulta esta limitado a un año');
            return false;
        }

        return true;
    }

    // ─────────────────────────────────────────────────────────────
    // Private query helpers
    // ─────────────────────────────────────────────────────────────

    private function queryMovimientos(string $start, string $finish)
    {
        return Detail::whereYear('date', $this->year)
            ->where('student_id', $this->selected_id)
            ->whereBetween('date', [$start, $finish])
            ->orderBy('date_paid', 'desc')
            ->paginate(15);
    }

    private function sumaSummary(int $year): float
    {
        $base = fn (string $type) => Summary::where('student_id', $this->selected_id)
            ->whereStatus('PAID')
            ->whereType($type)
            ->whereYear('date', $year)
            ->sum('amount');

        return $base('add') - $base('out');
    }

    private function sumaDetalles(string $start, string $finish, int $status, bool $dolar): ?float
    {
        $query = Detail::whereYear('date', $this->year)
            ->where('student_id', $this->selected_id)
            ->where('status', $status)
            ->whereBetween('date', [$start, $finish]);

        if ($dolar) {
            $query->where('currency_id', 2);
        } else {
            $query->where(function ($q) {
                $q->where('currency_id', '!=', 2)->orWhereNull('currency_id');
            });
        }

        return $query->selectRaw(
            "SUM(CASE WHEN summary_type = 'add' THEN amount ELSE 0 END)
           - SUM(CASE WHEN summary_type = 'out' THEN amount ELSE 0 END) as total"
        )->value('total');
    }
}
