<?php

namespace App\Http\Livewire\Socios;

use App\Models\Detail;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Exports\DeudoresExport;
use App\Exports\DeudorDataExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Deudores extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public int    $year;

    // Modal state
    public ?int    $selected_id = null;
    public ?string $socio_name  = null;
    public array   $detalles    = [];

    public function mount(): void
    {
        $this->year = (int) (SchoolYear::find(session('current_school_year_id'))->year ?? now()->year);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $socios = Student::where('is_active', 1)
            ->whereHas('details', fn($q) => $q->whereYear('date', $this->year)->where('status', 0))
            ->with([
                'tutor',
                'details' => fn($q) => $q->whereYear('date', $this->year)->where('status', 0),
            ])
            ->when($this->search, fn($q) =>
                $q->where(fn($sq) =>
                    $sq->where('full_name', 'like', "%{$this->search}%")
                       ->orWhere('document', 'like', "%{$this->search}%")
                )
            )
            ->orderBy('full_name')
            ->paginate(15, ['id', 'document', 'full_name', 'student_tutor_id']);

        [$totalSoles, $totalDolares] = $this->computeTotals();

        return view('livewire.socios.deudores', compact('socios', 'totalSoles', 'totalDolares'))
            ->extends('adminlte::page');
    }

    public function showModalDetail(int $id): void
    {
        $this->selected_id = $id;
        $this->socio_name  = Student::where('id', $id)->value('full_name');
        $this->detalles    = Detail::with('category')
            ->whereYear('date', $this->year)
            ->whereStatus(0)
            ->where('student_id', $id)
            ->orderBy('date')
            ->get()
            ->toArray();

        $this->emit('showModalDetails');
    }

    public function closeModal(): void
    {
        $this->selected_id = null;
        $this->socio_name  = null;
        $this->detalles    = [];
    }

    public function exportData()
    {
        abort_if(! $this->selected_id, 422, 'No hay estudiante seleccionado.');

        return Excel::download(
            new DeudorDataExport($this->selected_id, $this->socio_name),
            'Detalle_deudas_' . $this->socio_name . '.xlsx',
        );
    }

    public function exportDatas()
    {
        return Excel::download(
            new DeudoresExport($this->year),
            'Detalle_deudores_' . $this->year . '.xlsx',
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────

    private function computeTotals(): array
    {
        $base = Detail::whereYear('date', $this->year)
            ->whereStatus(0)
            ->whereHas('student', fn($q) => $q->where('is_active', 1));

        $totalSoles   = (clone $base)->where('currency_id', '!=', 2)->sum('amount');
        $totalDolares = (clone $base)->where('currency_id', 2)->sum('amount');

        return [$totalSoles, $totalDolares];
    }
}
