<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Student;
use App\Models\Category;
use App\Models\Currency;
use App\Models\SchoolYear;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\DetailsProvisionExport;

class ProvisionFijas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Form fields
    public $unique_code;
    public $currency;
    public int $currency_id = 1;
    public $amount;
    public $date;
    public $description;
    public $date_paid;
    public $category_id;

    // Filters / UI state
    public string $search = '';
    public $student_id;
    public $meses;
    public $categorias;
    public $students;
    public $schoolYear;

    protected $listeners = ['resetUI'];

    // ─────────────────────────────────────────────────────────────
    // Lifecycle
    // ─────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->schoolYear = SchoolYear::current();
        $this->meses      = Carbon::now()->format('Y-m');
        $this->date       = $this->meses;
        $this->categorias = Category::where('id', '!=', 1)->whereType('add')->pluck('id', 'name');
        $this->currency   = Currency::pluck('id', 'name');
        $this->students   = Student::whereHas(
            'enrollments',
            fn($q) => $q->where('school_year_id', $this->schoolYear->id),
        )->where('is_active', true)->pluck('id', 'full_name');
    }

    public function render()
    {
        $firstDay = Carbon::parse($this->meses)->startOfMonth();
        $lastDay  = Carbon::parse($this->meses)->endOfMonth();

        // JOIN with students to allow filtering and ordering by name
        $baseQuery = Detail::with(['student', 'category', 'currency'])
            ->join('students', 'details.student_id', '=', 'students.id')
            ->whereStatus(false)
            ->whereBetween('details.date', [$firstDay, $lastDay])
            ->whereType(1)
            ->whereSummaryType('add')
            ->when($this->student_id, fn($q) => $q->where('details.student_id', $this->student_id))
            ->when($this->search, fn($q) => $q->where('students.full_name', 'like', "%{$this->search}%"))
            ->orderBy('students.full_name')
            ->select('details.*');

        $detalles   = (clone $baseQuery)->paginate(20);
        $total      = (clone $baseQuery)
            ->where(fn($q) => $q->where('details.currency_id', '!=', 2)->orWhereNull('details.currency_id'))
            ->sum('details.amount');
        $totalDolar = (clone $baseQuery)->where('details.currency_id', 2)->sum('details.amount');

        return view('livewire.movimientos.provisiones.fijas.provision-fijas', compact('detalles', 'total', 'totalDolar'))
            ->extends('adminlte::page');
    }

    // Sync $date when the month filter changes (avoid setting state inside render)
    public function updatedMeses(): void
    {
        $this->date = $this->meses;
        $this->resetPage();
    }

    // ─────────────────────────────────────────────────────────────
    // Actions
    // ─────────────────────────────────────────────────────────────

    public function limpiar(): void
    {
        $this->search      = '';
        $this->student_id  = null;
        $this->currency_id = 1;
        $this->meses       = Carbon::now()->format('Y-m');
        $this->date        = $this->meses;
        $this->resetPage();
    }

    public function generate(): void
    {
        $this->validate(
            [
                'date'        => 'required|date',
                'category_id' => 'required',
                'description' => 'required',
                'amount'      => 'required|numeric|min:1',
            ],
            [
                'date.required'        => 'El mes a generar es requerido.',
                'date.date'            => 'Debe elegir un mes válido.',
                'category_id.required' => 'La categoría es requerida.',
                'description.required' => 'La descripción es requerida.',
                'amount.required'      => 'El monto es requerido.',
                'amount.numeric'       => 'El monto debe ser un número válido.',
                'amount.min'           => 'El monto debe ser mayor a 0.',
            ],
        );

        // Load all students with their tutors in one query to avoid N+1
        $studentIds  = array_values(collect($this->students)->toArray());
        $studentsMap = Student::with('tutor')
            ->whereIn('id', $studentIds)
            ->get()
            ->keyBy('id');

        foreach ($studentIds as $id) {
            $uniqueCode = $this->date
                . str_pad($this->category_id, 4, '0', STR_PAD_LEFT)
                . str_pad($id, 6, '0', STR_PAD_LEFT);

            if (Detail::where('unique_code', $uniqueCode)->exists()) {
                continue;
            }

            Detail::create([
                'unique_code'      => $uniqueCode,
                'status'           => false,
                'description'      => $this->description,
                'summary_type'     => 'add',
                'type'             => 1,
                'amount'           => $this->amount,
                'date'             => $this->date,
                'date_paid'        => $this->date_paid,
                'category_id'      => $this->category_id,
                'student_id'       => $id,
                'student_tutor_id' => $studentsMap->get($id)?->tutor?->id,
                'currency_id'      => $this->currency_id,
            ]);
        }

        $this->emit('provision_agregado', 'Se registraron todas las provisiones para el mes seleccionado.');
        $this->resetUI();
    }

    public function EliminarMes(): void
    {
        $this->validate(
            [
                'date'        => 'required|date',
                'category_id' => 'required',
            ],
            [
                'date.required'        => 'El mes a generar es requerido.',
                'date.date'            => 'Debe elegir un mes válido.',
                'category_id.required' => 'La categoría es requerida.',
            ],
        );

        $studentIds = array_values(collect($this->students)->toArray());

        // Single DELETE query instead of N individual deletes
        Detail::whereStatus(false)
            ->whereType(1)
            ->whereSummaryType('add')
            ->whereDate('date', Carbon::parse($this->date))
            ->where('category_id', $this->category_id)
            ->where('description', $this->description)
            ->whereIn('student_id', $studentIds)
            ->delete();

        $this->emit('provision_eliminado', 'Se eliminaron todas las provisiones.');
        $this->resetUI();
        $this->resetPage();
    }

    public function exportVariableExcel()
    {
        $this->validate(
            ['date' => 'required|date', 'category_id' => 'required'],
            ['date.required' => 'El mes a generar es requerido.', 'category_id.required' => 'La categoría es requerida.'],
        );

        return (new DetailsProvisionExport(1, $this->category_id, $this->meses))
            ->download('Detalle_Prov_Fija.xlsx');
    }

    // ─────────────────────────────────────────────────────────────
    // Reset
    // ─────────────────────────────────────────────────────────────

    public function resetUI(): void
    {
        $this->amount      = 0;
        $this->currency_id = 1;
        $this->category_id = null;
        $this->date        = Carbon::now()->format('Y-m');
        $this->resetValidation();
    }
}
