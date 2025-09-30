<?php

namespace App\Http\Livewire\Socios;

use App\Models\Detail;
use App\Models\Partner;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\DeudoresExport;
use App\Exports\DeudorDataExport;
use App\Models\Student;
use App\Models\StudentTutor;

class Deudores extends Component
{
    use WithPagination;

    public $search;
    public $category;
    public $selected_id;
    public $socio_name;
    public $detalles;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {

    }

    public function render()
    {
        $partnersQuery = Student::where('is_active', 1)
        ->whereHas('details', function($q) {
            $q->where('status', 0);
        })
        ->with(['details' => function($q) {
            $q->where('status', 0);
        }]);

        // Calcular totales por tipo de moneda
        $totalSoles = Detail::whereStatus(0)
            ->where(function ($query) {
                $query->where('currency_id', '!=', 2)
                    ->orWhereNull('currency_id'); // Incluir currency_id NULL
            })
            ->sum('amount');

        // dd($totalSoles);

        $totalDolares = Detail::whereStatus(0)
            ->where('currency_id', 2)
            ->sum('amount');

        // Paginación de socios
        $socios = $partnersQuery->paginate(15, ['id', 'document', 'full_name', 'student_tutor_id']);

        return view('livewire.socios.deudores', ['socios' => $socios, 'totalSoles' => $totalSoles, 'totalDolares' => $totalDolares])
            ->extends('adminlte::page');
    }

    public function showModalDetail($id)
    {
        $this->selected_id = $id;
        $this->socio_name = Student::find($id)->full_name;
        $this->detalles = Detail::whereStatus(0)->where('student_id', $id)->get();
        $this->emit('showModalDetails', 'mostrar modal');
    }

    public function closeModal()
    {
        $this->socio_name = null;
        $this->detalles = null;
        $this->selected_id = null;
    }

    public function exportData()
    {
        return (new DeudorDataExport($this->selected_id, $this->socio_name))->download('Detalle_deudas_'.$this->socio_name.'.xlsx');
    }

    public function exportDatas()
    {
        return (new DeudoresExport())->download('Detalle_deudores_'.now().'.xlsx');
    }
}
