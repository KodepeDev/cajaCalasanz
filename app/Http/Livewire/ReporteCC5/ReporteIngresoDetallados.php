<?php

namespace App\Http\Livewire\ReporteCC5;

use App\Models\Detail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Stage;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteIngresoDetallados extends Component
{
    public $hasEtapa, $etapa, $hasUser, $user, $stand, $start_date, $end_date;
    public $users, $etapas, $url;
    public $detalles = [];

    public function mount()
    {
        $hoy = Carbon::now();
        $this->start_date = $hoy->format('Y-m-d');
        $this->end_date = $hoy->format('Y-m-d');
        $this->users = User::where('id', '!=', 1)->pluck('id', 'first_name');
        $this->etapas = Stage::pluck('id', 'name');
        $this->hasEtapa = false;
        $this->hasUser = false;
    }
    public function render()
    {
        return view('livewire.reporte-c-c5.reporte-ingreso-detallados')->extends('adminlte::page');
    }

    public function buscar()
    {
        if($this->validarFechas())
        {
            $rules = [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:'.$this->start_date,
            ];
            try {
                $this->validate($rules);
                $this->url = "/admin/reporte/cc5/pdf-get?inicio=".$this->start_date."&fin=".$this->end_date."&etapa=".$this->etapa."&hasEtapa=".$this->hasEtapa."&hasUser=".$this->hasUser."&user=".$this->user."&stand=".$this->stand;
            } catch (\Throwable $th) {
                $this->emit('error', $th->getMessage());
            }
        }
    }

    public function validarFechas()
    {
        $inicio = Carbon::parse($this->start_date);
        $fin = Carbon::parse($this->end_date);

        $y1 = Carbon::parse($this->start_date)->format('Y');
        $y2 = Carbon::parse($this->end_date)->format('Y');

        if (($inicio->month !== $fin->month) | ($y1 !== $y2)){
            $this->emit('error', 'El rango de fechas debe ser del mismo mes.');
            return false;
        }else {
            return true;
        }
    }

    public function limpiar()
    {
        $hoy = Carbon::now();
        $this->start_date = $hoy->format('Y-m-d');
        $this->end_date = $hoy->format('Y-m-d');
        $this->hasEtapa = false;
        $this->hasUser = false;
        $this->user = null;
        $this->etapa = null;
        $this->stand = null;
        $this->buscar();
    }
}
