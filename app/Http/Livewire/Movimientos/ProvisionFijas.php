<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use App\Models\Stage;
use App\Models\Stand;
use App\Models\Detail;
use App\Models\Partner;
use Livewire\Component;
use App\Models\Category;
use App\Models\Currency;
use Livewire\WithPagination;
use App\Exports\DetailsProvisionExport;
use App\Models\Student;

class ProvisionFijas extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $unique_code, $currency, $currency_id;

    public $status,$summary_type, $type, $amount, $date, $description, $date_paid, $category_id, $stand_id, $stage_id;

    public $categorias, $meses, $students, $search, $student_id;

    protected $listeners = [
        'resetUI',
    ];

    public function limpiar()
    {
        $this->search = '';
        $this->student_id  = null;
        $this->currency_id = 1;
        $this->meses = Carbon::now()->format('Y-m');
        $this->resetPage();
    }

    public function mount()
    {
        $this->status = false;
        $this->type = 1;
        $this->summary_type = 'add';
        $this->search = '';
        $this->date = Carbon::now()->format('Y-m');
        $this->meses = Carbon::now()->format('Y-m');
        $this->categorias = Category::where('id', '!=', 1)->whereType('add')->pluck('id', 'name');
        $this->students = Student::where('is_active', true)->pluck('id', 'full_name');

        $this->currency = Currency::pluck('id', 'name');

    }
    public function render()
    {
        $first_day = Carbon::parse($this->meses)->firstOfMonth();
        $last_day = Carbon::parse($this->meses)->endOfMonth();

        // Definir la consulta base con las condiciones comunes
        $baseQuery = Detail::whereHas('student', function ($query) {
            $query->where('full_name', 'like', '%'.$this->search.'%')
                ->orderBy('full_name', 'asc');
        })
        ->whereStatus(false)
        ->whereBetween('date', [$first_day, $last_day])
        ->whereType(1)
        ->whereSummaryType('add');

        // Agregar condición extra si `$partner_id` está definido
        if ($this->student_id) {
            $baseQuery->whereHas('student', function ($query) {
                $query->where('student_id', $this->student_id);
            });
        }
        // Obtener los detalles con paginación
        $detalles = (clone $baseQuery)->paginate(20);

        // Calcular `$total`, excluyendo `currency_id` igual a 2
        $total = (clone $baseQuery)
        ->where(function ($query) {
            $query->where('currency_id', '!=', 2)
                ->orWhereNull('currency_id'); // Incluir registros con currency_id NULL
        })
        ->sum('amount');

        // Calcular `$totalDolar`, solo con `currency_id` igual a 2
        $totalDolar = (clone $baseQuery)->where('currency_id', 2)->sum('amount');

        // dd($total, $totalDolar, $detalles);

        return view('livewire.movimientos.provisiones.fijas.provision-fijas', compact('detalles', 'total', 'totalDolar'))->extends('adminlte::page');
    }

    public function generate()
    {
        $rules = [
            'date' => 'required|date',
            'category_id' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric|min:1',
        ];

        $messages = [
            'date.required' => 'El mes a generar es requerido',
            'date.date' => 'Debe elegir un mes válido',
            'category_id.required' => 'La categoria es requerido',
            'description.required' => 'La descripción es requerido',
            'amount.required' => 'El monto es requerido',
            'amount.numeric' => 'El monto debe ser un número válido',
            'amount.min' => 'El monto debe ser mayor a 0',
        ];

        $this->validate($rules, $messages);


        foreach ($this->students as $name => $id) {
            $this->unique_code = strval($this->date.$this->category_id.$id);
            $detail = Detail::where('unique_code', $this->unique_code)->first();
            // dd($student);
            if (!$detail)
            {
                Detail::create([
                    'unique_code' =>$this->unique_code,
                    'status' =>$this->status,
                    'description' => $this->description,
                    'summary_type' =>$this->summary_type,
                    'type' =>$this->type,
                    'amount' =>$this->amount,
                    'date' =>$this->date,
                    'date_paid' =>$this->date_paid,
                    'category_id' =>$this->category_id,
                    'student_id' =>$id,
                    'currency_id' =>$this->currency_id,
                ]);
            }
        }

        $this->emit('provision_agregado', 'Se registraron todas las provisiones para el mes seleccionado');
        $this->resetUI();

    }

    public function EliminarMes()
    {
        $rules = [
            'date' => 'required|date',
            'category_id' => 'required',
        ];

        $messages = [
            'date.required' => 'El mes a generar es requerido',
            'date.date' => 'Debe elegir un mes válido',
            'category_id.required' => 'La categoria es requerido',
        ];

        $this->validate($rules, $messages);

        $detalles = Detail::whereStatus(false)->whereType(1)->whereSummaryType('add')->where('date', '=', Carbon::parse($this->date))->where('category_id', $this->category_id);

        foreach ($this->students as $name => $id) {
            // dd($categoria->name);
            $detalles = Detail::whereStatus(false)->whereType(1)->whereSummaryType('add')->where('date', '=', Carbon::parse($this->date))->where('category_id', $this->category_id)->where('description', '=', $this->description)->where('student_id', $id);
            // dd($detalles->get());
            $detalles->delete();
        }




        // dd($detalles);
        // $detalles->delete();

        $this->emit('provision_eliminado', 'Se eliminaron todas las provisiones');

        $this->resetUI();
        $this->resetPage();
    }

    public function exportVariableExcel()
    {
        $rules = [
            'date' => 'required|date',
            'category_id' => 'required',
            'stage_id' => 'required',
        ];

        $messages = [
            'date.required' => 'El mes a generar es requerido',
            'date.date' => 'Debe elegir un mes válido',
            'category_id.required' => 'La categoria es requerido',
        ];

        $this->validate($rules, $messages);

        return (new DetailsProvisionExport($this->type, $this->category_id, $this->meses, $this->stage_id))->download('Detalle_de_deudores.xlsx');
    }


    public function resetUI()
    {
        $this->status = false;
        $this->summary_type = 'add';
        $this->type = 1;
        $this->amount = 0;
        $this->currency_id = 1;
        $this->category_id = null;
        $this->date = Carbon::now()->format('Y-m');
        $this->resetValidation();
    }
}
