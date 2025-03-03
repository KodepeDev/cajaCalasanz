<?php

namespace App\Http\Livewire\ExportComponents;

use App\Exports\DetailsConceptosExport;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class ExportConceptos extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $total_prov, $status, $summary_type, $date, $meses, $category_id;
    public $categorias;
    public $startDate, $endDate;



    public function mount()
    {
        $hoy = Carbon::now();
        $this->status = 1;
        $this->total_prov = 10;
        $this->summary_type = 'add';
        $this->date = Carbon::now()->format('Y-m');
        $this->startDate = $hoy->startOfYear()->format('Y-m');
        $this->endDate = $hoy->format('Y-m');
        $this->categorias = Category::where('id', '!=', 1)->whereType($this->summary_type)->pluck('id', 'name');
        $this->meses = Carbon::now()->format('Y-m');

    }

    public function render()
    {
        // $first_day = Carbon::parse($this->meses)->firstOfMonth();
        // $last_day = Carbon::parse($this->meses)->endOfMonth();

        $query = Category::with(['details'])->where('id', '!=', 1)->whereType($this->summary_type);

        if ($this->category_id) {
            $query->where('id', $this->category_id);
        }

        $cats = $query->get();

        // dd($cats);

        return view('livewire.export-components.export-conceptos', compact('cats'))->extends('adminlte::page');
    }

    public function exportarExcel()
    {
        try {
            $rules = [
                'meses' => 'required|date',
                'category_id' => 'required',
                'status' => 'required',
            ];

            $messages = [
                'meses.required' => 'El mes a generar es requerido',
                'meses.date' => 'Debe elegir un mes válido',
                'category_id.required' => 'Seleccione un concepto/categoría para exportar',
                'status.required' => 'El estado es requerido',
            ];

            $this->validate($rules, $messages);

            return (new DetailsConceptosExport($this->status, $this->meses, $this->category_id))->download('Detalle_de_Conceptps.xlsx');
        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }
}
