<?php

namespace App\Http\Livewire\Socios\Detalles;

use App\Models\Detail;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Summary;
use Livewire\Component;
use Livewire\WithPagination;

class StudentDetails extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $student, $url;
    public $start1, $finish1, $start, $finish, $selected_id;

    public $detalle;

    protected $listeners = [
        'render',
        'eliminarDetalle' => 'delete',
    ];

    public function mount($id)
    {
        $hoy = Carbon::now();
        $this->selected_id = $id;
        $this->student = Student::find($id);

        $this->finish = $this->finish1 = $hoy->format('Y-m-d');
        $this->start = $this->start1 = $hoy->firstOfMonth()->format('Y-m-d');

    }

    public function render()
    {

        $start  = Carbon::parse($this->start)->format('Y-m-d');
        $finish  = Carbon::parse($this->finish)->format('Y-m-d');

        $suma2023 = Summary::where('student_id', $this->selected_id)->whereStatus('PAID')->whereType('add')->whereYear('date', date('Y'))->sum('amount') - Summary::where('student_id', $this->selected_id)->whereStatus('PAID')->whereType('out')->whereYear('date', date('Y'))->sum('amount');

        $suma2022 = Summary::where('student_id', $this->selected_id)->whereStatus('PAID')->whereType('add')->whereYear('date', date('Y')-1)->sum('amount') - Summary::where('student_id', $this->selected_id)->whereStatus('PAID')->whereType('out')->whereYear('date', date('Y')-1)->sum('amount');

        // Suma de soles con status = 1
        $sumaTotal = Detail::where('student_id', $this->selected_id)
            ->where(function ($query) {
                $query->where('currency_id', '!=', 2)
                    ->orWhereNull('currency_id'); // Incluir currency_id NULL
            })
            ->where('status', 1)
            ->whereBetween('date', [$start, $finish])
            ->selectRaw("SUM(CASE WHEN summary_type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN summary_type = 'out' THEN amount ELSE 0 END) as total")
            ->value('total');

        // Suma de dólares con status = 1
        $sumaTotalDolar = Detail::where('student_id', $this->selected_id)
            ->where('currency_id', 2)
            ->where('status', 1)
            ->whereBetween('date', [$start, $finish])
            ->selectRaw("SUM(CASE WHEN summary_type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN summary_type = 'out' THEN amount ELSE 0 END) as total")
            ->value('total');

        // Suma de soles pendientes con status = 0
        $sumaTotalPendiente = Detail::where('student_id', $this->selected_id)
            ->where(function ($query) {
                $query->where('currency_id', '!=', 2)
                    ->orWhereNull('currency_id'); // Incluir currency_id NULL
            })
            ->where('status', 0)
            ->whereBetween('date', [$start, $finish])
            ->selectRaw("SUM(CASE WHEN summary_type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN summary_type = 'out' THEN amount ELSE 0 END) as total")
            ->value('total');

        // Suma de dólares pendientes con status = 0
        $sumaTotalPendienteDolar = Detail::where('student_id', $this->selected_id)
            ->where('currency_id', 2)
            ->where('status', 0)
            ->whereBetween('date', [$start, $finish])
            ->selectRaw("SUM(CASE WHEN summary_type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN summary_type = 'out' THEN amount ELSE 0 END) as total")
            ->value('total');

        $movimientos = Detail::where('student_id', $this->selected_id)->whereBetween('date', [$start, $finish])->orderBy('date_paid', 'desc')->paginate(15);

        if($this->validarFechas()){
            $this->url = '/admin/socios/reportePDF/'. '?socio=' . $this->student->id . '&start=' . $this->start1 . '&finish=' . $this->finish1 . '';
        }else{
            $this->url = null;
        }
        return view('livewire.socios.detalles.student-details', compact('movimientos', 'sumaTotal', 'sumaTotalDolar', 'sumaTotalPendiente', 'sumaTotalPendienteDolar', 'suma2023', 'suma2022'))->extends('adminlte::page');
    }

    public function delete($id)
    {
        // dd('holi');
        $detail = Detail::findOrFail($id);
        $detail->delete();
        $this->emit('eliminado', 'Registro eliminado satisfactoriamente');
    }

    public function Filter()
    {
        if($this->validarFechas()){

            $this->start = $this->start1;
            $this->finish = $this->finish1;

            $this->resetPage();
        }
    }
    public function clearFilter()
    {
        $hoy = Carbon::now();
        $this->finish = $this->finish1 = $hoy->format('Y-m-d');
        $this->start = $this->start1 = $hoy->firstOfYear()->format('Y-m-d');
        $this->resetPage();
    }

    public function validarFechas()
    {
        $inicio = Carbon::parse($this->start1);
        $fin = Carbon::parse($this->finish1);

        if ($inicio->year !== $fin->year){
            $this->emit('error', 'El rango de consulta esta limitado a un año');
            return false;
        }else {
            return true;
        }

        // if ($inicio->day !== 1) {
        //     $this->start1 = $inicio->firstOfMonth()->format('Y-m-d');
        // }

        // if ($fin->day !== $fin->daysInMonth) {
        //     $this->finish1 = $fin->lastOfMonth()->format('Y-m-d');
        // }
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
}
