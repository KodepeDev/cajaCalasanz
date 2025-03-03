<?php

namespace App\Http\Livewire\Movimientos\Provisiones\PorSocio;

use Carbon\Carbon;
use App\Models\Stage;
use App\Models\Detail;
use App\Models\Partner;
use Livewire\Component;
use App\Models\Category;
use App\Models\Currency;
use Livewire\WithPagination;

class PorSocioComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $currentPage = 1;

    public $status,$summary_type, $type, $amount, $description, $date, $date_paid, $category_id, $stand_id, $stage_id;

    public $categorias, $stands, $stages, $meses, $selected_id, $socios, $search, $partner_id;

    public $provisions;

    public $currency, $currency_id;

    protected $listeners = [
        'resetUI',
    ];

    public function limpiar()
    {
        $this->search = '';
        $this->partner_id  = null;
        $this->currency_id = 1;
        $this->meses = Carbon::now()->format('Y-m');
        $this->resetPage();
    }

    public function mount()
    {
        $this->status = false;
        $this->type = 3;
        $this->currency_id = 1;
        $this->summary_type = 'add';
        $this->meses = Carbon::now()->format('Y-m');
        $this->date = Carbon::now()->format('Y-m');
        $this->categorias = Category::where('id', '!=', 1)->whereType('add')->pluck('id', 'name');
        $this->stages = Stage::pluck('id', 'name');
        $this->selected_id = 0;
        $this->socios = Partner::pluck('id', 'full_name');
        $this->currency = Currency::pluck('id', 'name');
    }

    public function render()
    {
        $first_day = Carbon::parse($this->meses)->firstOfMonth();
        $last_day = Carbon::parse($this->meses)->endOfMonth();

        // Crear consulta base para condiciones comunes
        $baseQuery = Detail::whereStatus(false)
        ->where('stand_id', null)
        ->whereSummaryType($this->summary_type)
        ->whereBetween('date', [$first_day, $last_day]);

        // Agregar condición de partner_id si está definido
        if ($this->partner_id) {
        $baseQuery->where('partner_id', $this->partner_id);
        }

        // Clonar y modificar la consulta para `$total`, incluyendo currency_id NULL
        $total = (clone $baseQuery)
        ->where(function ($query) {
            $query->where('currency_id', '!=', 2)
                ->orWhereNull('currency_id'); // Incluir currency_id NULL
        })
        ->sum('amount');

        // Clonar y modificar la consulta para `$totalDolar`, con currency_id = 2
        $totalDolar = (clone $baseQuery)
        ->where('currency_id', 2)
        ->sum('amount');

        // Obtener detalles con paginación
        $detalles = (clone $baseQuery)->paginate($this->partner_id ? 30 : 20);

        return view('livewire.movimientos.provisiones.por-socio.por-socio-component', compact('detalles', 'total', 'totalDolar'))->extends('adminlte::page');
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

        // dd($this->date_paid);

        foreach ($this->socios as $full_name => $id) {
            $socio = Partner::where('id', '=', $id)->first();

            $unique_code = strval($this->date.$this->category_id.$socio->id);
            $detail = Detail::where('unique_code', $unique_code)->first();

            if (!$detail) {
                $detail = new Detail();
                $detail->unique_code = $unique_code;
                $detail->status = $this->status;
                $detail->summary_type = $this->summary_type;
                $detail->description = $this->description;
                $detail->type = $this->type;
                $detail->amount = $this->amount;
                $detail->date = $this->date;
                $detail->date_paid = $this->date_paid;
                $detail->category_id = $this->category_id;
                $detail->stand_id = null;
                $detail->partner_id = $socio->id;
                $detail->currency_id = $this->currency_id;
                $detail->save();
            }
        }


        $this->emit('provision_agregado', 'Se registraron todas las provisiones para el mes seleccionado');
        $this->resetUI();

    }

    public function Add()
    {
        $this->provisions->push(new Detail());
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->selected_id = $id;
        $edit = Detail::find($id);
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
            'description' => 'required',
        ];

        $messages = [
            'date.required' => 'El mes a generar es requerido',
            'date.date' => 'Debe elegir un mes válido',
            'category_id.required' => 'La categoria es requerido',
        ];

        $this->validate($rules, $messages);

        $startDate = Carbon::parse($this->date)->firstOfMonth();
        $endDate = Carbon::parse($this->date)->endOfMonth();

        $detalles = Detail::whereStatus(false)->where('stand_id', null)->whereSummaryType($this->summary_type)->whereBetween('date', [$startDate, $endDate])->where('category_id', $this->category_id)->where('description', '=', $this->description);
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


    public function resetUI()
    {
        $this->selected_id = 0;
        $this->status = false;
        $this->type = 2;
        $this->description = '';
        $this->amount = 0;
        $this->category_id = null;
        $this->date = Carbon::now()->format('Y-m');
        $this->currency_id = 1;
        $this->resetValidation();
    }
}
