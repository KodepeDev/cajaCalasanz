<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use App\Models\Stage;
use App\Models\Detail;
use Livewire\Component;
use App\Models\Category;
use App\Models\Currency;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DetailsProvisionExport;
use App\Models\Student;

class ProvisionVariables extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $currentPage = 1;
    public $unique_code, $currency, $currency_id;

    public $status,$summary_type, $type, $amount, $description, $date, $date_paid, $category_id;

    public $categorias, $students, $meses, $selected_id, $search, $direcction, $student_id;

    public $provisions;

    protected $listeners = [
        'resetUI',
    ];

    public function limpiar()
    {
        $this->search = '';
        $this->currency_id = 1;
        $this->meses = Carbon::now()->format('Y-m');
        $this->student_id  = null;
        $this->resetPage();
    }

    public function sortDirecction()
    {
        if($this->direcction == 'asc')
        {
            $this->direcction = 'desc';
        }else {
            $this->direcction = 'asc';
        }
    }

    public function mount()
    {
        $this->status = false;
        $this->type = 3;
        $this->direcction = 'asc';
        $this->summary_type = 'add';
        $this->date = Carbon::now()->format('Y-m');
        $this->categorias = Category::where('id', '!=', 1)->whereType('add')->pluck('id', 'name');
        $this->selected_id = 0;
        $this->meses = Carbon::now()->format('Y-m');
        $this->students = Student::pluck('id', 'full_name');

        $this->currency = Currency::pluck('id', 'name');


    }
    public function render()
    {

        $first_day = Carbon::parse($this->meses)->firstOfMonth();
        $last_day = Carbon::parse($this->meses)->endOfMonth();

        // Crear consulta base para las condiciones comunes
        $baseQuery = Detail::whereStatus(false)
        ->whereBetween('date', [$first_day, $last_day])
        ->whereType(3)
        ->whereSummaryType($this->summary_type);

        // Agregar condiciones adicionales si existen `student_id` o `search`
        if ($this->student_id) {
            $baseQuery->whereHas('student', function ($query) {
                $query->where('student_id', $this->student_id);
            });
        } elseif ($this->search) {
            $baseQuery->whereHas('student', function ($query) {
                $query->where('full_name', 'like', '%'.$this->search.'%');
            });
        }

        // Clonar y modificar la consulta para `$total`
        $total = (clone $baseQuery)
            ->where(function ($query) {
                $query->where('currency_id', '!=', 2)
                    ->orWhereNull('currency_id'); // Incluir currency_id NULL
            })
            ->sum('amount');

        // Clonar y modificar la consulta para `$totalDolar`
        $totalDolar = (clone $baseQuery)
        ->where('currency_id', 2)
        ->sum('amount');

        // Condición para `$detalles`
        if ($this->student_id || $this->search) {
            // Para cuando `student_id` o `search` están definidos
            $detalles = (clone $baseQuery)->paginate(20);
        } else {
            // Cuando no están definidos `student_id` ni `search`, usamos un join y ordenamos
            $detalles = Detail::join('students', 'details.student_id', '=', 'students.id')
                ->orderBy('students.full_name', $this->direcction)
                ->where('details.status', false)
                ->whereBetween('details.date', [$first_day, $last_day])
                ->where('details.type', 3)
                ->where('details.summary_type', $this->summary_type)
                ->select(
                    'details.id',
                    'details.date',
                    'details.description',
                    'details.category_id',
                    'students.full_name',
                    'details.student_id',
                    'details.amount',
                    'details.currency_id'
                )
                ->paginate(20);
        }
        // dd($detalles);
        // $this->provisions = $detalles;

        return view('livewire.movimientos.provisiones.variables.provision-variables', compact('detalles', 'total', 'totalDolar'))->extends('adminlte::page');
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
            'description.required' => 'La descripción es requerida',
            'amount.required' => 'El monto es requerido',
            'amount.numeric' => 'El monto debe ser un número válido',
            'amount.min' => 'El monto debe ser mayor a 0',
        ];

        $this->validate($rules, $messages);

        foreach ($this->students as $name => $id) {
            $this->unique_code = strval($this->date.$this->category_id.$id);
            $detail = Detail::where('unique_code', $this->unique_code)->first();
            $student_tutor = Student::find($id)->first()->id;
            if(!$detail){
                $detail = new Detail();
                $detail->unique_code = $this->unique_code;
                $detail->status = $this->status;
                $detail->summary_type = $this->summary_type;
                $detail->description = $this->description;
                $detail->type = $this->type;
                $detail->amount = $this->amount;
                $detail->date = $this->date;
                $detail->date_paid = $this->date_paid;
                $detail->category_id = $this->category_id;
                $detail->student_id = $id;
                $detail->student_tutor_id = $student_tutor;
                $detail->currency_id = $this->currency_id;
                $detail->save();
            }
        }

        $this->emit('provision_agregado', 'Se registraron todas las provisiones para el mes seleccionado');
        $this->resetUI();

    }

    // public function Add()
    // {
    //     $this->provisions->push(new Detail());
    // }

    public function edit($id)
    {
        $this->resetValidation();

        $this->selected_id = $id;
        $edit = Detail::find($id);

        // dd($id);
        $this->amount = $edit->amount;
        $this->description = $edit->description;
    }

    public function update()
    {
        $det = Detail::find($this->selected_id);
        // dd($this->amount);
        $rules = [
            'amount' => 'required|numeric|min:1',
        ];

        $messages = [
            'amount.required' => 'El monto es requerido',
            'amount.numeric' => 'El monto debe ser un número válido',
            'amount.min' => 'El monto debe ser mayor a 0',
        ];

        $this->validate($rules, $messages);
        $det->update([
            'description' => $this->description,
            'amount' => $this->amount
        ]);
        $det->save();

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

        $detalles = Detail::whereStatus(false)->whereType(3)->whereSummaryType($this->summary_type)->where('date', '=', Carbon::parse($this->date))->where('category_id', $this->category_id);
        // dd($detalles);
        $detalles->delete();

        $this->emit('provision_eliminado', 'Se eliminaron todas las provisiones');

        $this->resetUI();
        $this->resetPage();
    }

    public function deleteRow($row)
    {
        $detail = Detail::findOrFail($row);
        $detail->delete();
    }


    public function exportVariablePdf()
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
            'stage_id.required' => 'El área es requerido',
        ];

        $this->validate($rules, $messages);

        $categoria = Category::whereKey($this->category_id)->first();
        $mes = Carbon::parse($this->date)->format('m/Y');

        try {
            $detalles = Detail::whereHas('stand', function($query){
                $query->where('stage_id', $this->stage_id);
            })->whereStatus(false)->whereType(3)->whereSummaryType($this->summary_type)->where('date', '=', Carbon::parse($this->date))->where('category_id', $this->category_id);

            $totalFinal = $detalles->sum('amount');
            $data = $detalles->get();

            $pdf = PDF::loadView('pdf.exports.export-variables', compact('data', 'mes', 'categoria', 'etapa', 'totalFinal'));
            return $pdf->download('Reporte-de-deudores.pdf');
        } catch (\Throwable $th) {
            //throw $th;
            $this->emit('error', $th->getMessage());
        }
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
            'stage_id.required' => 'El área es requerido',
        ];

        $this->validate($rules, $messages);

        return (new DetailsProvisionExport(3, $this->category_id, $this->meses, $this->stage_id))->download('Detalle_de_deudores.xlsx');
    }

    public function resetUI()
    {
        $this->selected_id = 0;
        $this->status = false;
        $this->type = 3;
        $this->description = '';
        $this->amount = 0;
        $this->currency_id = 1;
        $this->category_id = null;
        $this->date = Carbon::now()->format('Y-m');
        $this->resetValidation();
    }
}
