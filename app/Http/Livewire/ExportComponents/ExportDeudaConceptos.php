<?php

namespace App\Http\Livewire\ExportComponents;

use App\Exports\DetailsPendienteConceptosExport;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class ExportDeudaConceptos extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $total_prov, $total_prov_dolar, $status, $summary_type, $date, $meses, $category_id;
    public $categorias;
    public $startDate, $endDate, $rangCheck;



    public function mount()
    {
        $hoy = Carbon::now();
        $this->status = 0;
        $this->rangCheck = false;
        $this->total_prov = 0;
        $this->total_prov_dolar = 0;
        $this->summary_type = 'add';
        $this->date = Carbon::now()->format('Y-m');
        $this->endDate = $hoy->format('Y-m');
        $this->startDate = $hoy->startOfYear()->format('Y-m');
        $this->categorias = Category::where('id', '!=', 1)->whereType($this->summary_type)->pluck('id', 'name');
        $this->meses = Carbon::now()->format('Y-m');

    }

    public function render()
    {
        // $first_day = Carbon::parse($this->meses)->firstOfMonth();
        // $last_day = Carbon::parse($this->meses)->endOfMonth();

        // $query = Category::with(['details'])->where('id', '!=', 1);
        $query = Category::with(['details'])->where('id', '!=', 1)->whereType($this->summary_type);

        if ($this->category_id) {
            $query->where('id', $this->category_id);
        }

        $cats = $query->get();

        // dd($cats);

        // dd($cats);
        return view('livewire.export-components.export-deuda-conceptos', compact('cats'))->extends('adminlte::page');
    }

    public function exportarExcel()
    {
        try {
            $rules = [
                'endDate' => 'required|date',
                'startDate' => 'required|date|before_or_equal:'.$this->endDate,
                'category_id' => 'required',
                'status' => 'required',
            ];

            $messages = [
                'startDate.required' => 'El mes a generar es requerido',
                'endDate.required' => 'El mes a generar es requerido',
                'startDate.date' => 'Debe elegir un mes válido',
                'endDate.date' => 'Debe elegir un mes válido',
                'endDate.before_or_equal' => 'Debe elegir un rango de mes válido',
                'category_id.required' => 'Seleccione un concepto/categoría para exportar',
                'status.required' => 'El estado es requerido',
            ];

            $this->validate($rules, $messages);

            return (new DetailsPendienteConceptosExport($this->status, $this->rangCheck, $this->startDate, $this->endDate, $this->category_id))->download('Detalle_de_Conceptos_Pendientes_'.$this->meses.'.xlsx');
        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }
}
