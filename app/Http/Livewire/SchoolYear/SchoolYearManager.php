<?php

namespace App\Http\Livewire\SchoolYear;

use App\Models\SchoolYear;
use App\Models\Summary;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SchoolYearManager extends Component
{
    // Form fields
    public $year;
    public $start_date;
    public $end_date;

    // Editing state
    public $editingId = null;
    public $showForm = false;

    // Backfill
    public $backfillYearId;
    public $orphanCount = 0;

    // Flash messages
    public $successMessage;
    public $errorMessage;

    protected function rules(): array
    {
        return [
            'year'       => 'required|integer|min:2000|max:2100',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ];
    }

    protected $messages = [
        'year.required'             => 'El año es obligatorio.',
        'year.integer'              => 'El año debe ser un número entero.',
        'year.min'                  => 'El año mínimo permitido es 2000.',
        'year.max'                  => 'El año máximo permitido es 2100.',
        'end_date.after_or_equal'   => 'La fecha de fin debe ser posterior o igual a la de inicio.',
    ];

    public function mount()
    {
        $this->refreshOrphanCount();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingId = null;
    }

    public function openEdit($id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        $this->editingId  = $schoolYear->id;
        $this->year       = $schoolYear->year;
        $this->start_date = $schoolYear->start_date;
        $this->end_date   = $schoolYear->end_date;
        $this->showForm   = true;
    }

    public function cancelForm()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function save()
    {
        $this->validate();
        $this->clearMessages();

        if ($this->editingId) {
            $schoolYear = SchoolYear::findOrFail($this->editingId);

            if ($schoolYear->year != $this->year && SchoolYear::where('year', $this->year)->where('id', '!=', $this->editingId)->exists()) {
                $this->errorMessage = "Ya existe un año escolar registrado con el año {$this->year}.";
                return;
            }

            $schoolYear->update([
                'year'       => $this->year,
                'start_date' => $this->start_date ?: null,
                'end_date'   => $this->end_date ?: null,
            ]);
            $this->successMessage = "Año escolar {$this->year} actualizado correctamente.";
        } else {
            if (SchoolYear::where('year', $this->year)->exists()) {
                $this->errorMessage = "Ya existe un año escolar registrado con el año {$this->year}.";
                return;
            }

            SchoolYear::create([
                'year'       => $this->year,
                'start_date' => $this->start_date ?: null,
                'end_date'   => $this->end_date ?: null,
                'is_active'  => false,
            ]);
            $this->successMessage = "Año escolar {$this->year} creado correctamente.";
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function setActive($id)
    {
        $this->clearMessages();
        DB::transaction(function () use ($id) {
            SchoolYear::query()->update(['is_active' => false]);
            $schoolYear = SchoolYear::findOrFail($id);
            $schoolYear->update(['is_active' => true]);
            // Update global session for current user
            session(['current_school_year_id' => $schoolYear->id]);
        });
        $schoolYear = SchoolYear::findOrFail($id);
        $this->successMessage = "Año escolar {$schoolYear->year} marcado como activo.";
    }

    public function delete($id)
    {
        $this->clearMessages();
        $schoolYear = SchoolYear::findOrFail($id);

        if ($schoolYear->is_active) {
            $this->errorMessage = 'No se puede eliminar el año escolar activo.';
            return;
        }

        $count = Summary::withoutGlobalScopes()->where('school_year_id', $id)->count();
        if ($count > 0) {
            $this->errorMessage = "No se puede eliminar: hay {$count} movimiento(s) asignados a este año escolar.";
            return;
        }

        $schoolYear->delete();
        $this->successMessage = "Año escolar {$schoolYear->year} eliminado.";
    }

    public function backfillSummaries()
    {
        $this->clearMessages();

        if (!$this->backfillYearId) {
            $this->errorMessage = 'Selecciona el año escolar destino para la corrección.';
            return;
        }

        $schoolYear = SchoolYear::findOrFail($this->backfillYearId);
        $updated = Summary::withoutGlobalScopes()
            ->whereNull('school_year_id')
            ->update(['school_year_id' => $schoolYear->id]);

        $this->refreshOrphanCount();
        $this->successMessage = "{$updated} movimiento(s) corregido(s) y asignados al año escolar {$schoolYear->year}.";
    }

    public function render()
    {
        $this->refreshOrphanCount();
        return view('livewire.school-year.school-year-manager', [
            'schoolYears' => SchoolYear::orderByDesc('year')->get(),
        ]);
    }

    private function resetForm()
    {
        $this->year       = null;
        $this->start_date = null;
        $this->end_date   = null;
        $this->editingId  = null;
        $this->resetValidation();
    }

    private function refreshOrphanCount()
    {
        $this->orphanCount = Summary::withoutGlobalScopes()->whereNull('school_year_id')->count();
    }

    private function clearMessages()
    {
        $this->successMessage = null;
        $this->errorMessage   = null;
    }
}
