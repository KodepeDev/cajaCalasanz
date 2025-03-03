<?php

namespace App\Http\Livewire\ReporteCC5;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Stage;
use App\Models\Stand;
use App\Models\Detail;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteIngresosGastos extends Component
{

    public $hasEtapa, $etapa, $hasUser, $user, $stand, $start_date, $end_date;
    public $users, $etapas, $url;
    public $detalles = [];

    public function mount()
    {
        $hoy = Carbon::now();
        $this->start_date = $hoy->format('Y-m-d');
        $this->end_date = $hoy->format('Y-m-d');
        $this->users = User::pluck('id', 'first_name');
        $this->etapas = Stage::pluck('id', 'name');
        $this->hasEtapa = false;
        $this->hasUser = false;
    }
    public function render()
    {
        return view('livewire.reporte-c-c5.reporte-ingresos-gastos')->extends('adminlte::page');
    }

    public function buscar()
    {

        // $data = Stage::with('stands')->when(($this->start_date && $this->end_date), function($q){
        //     $q->with(['stands.details' => function($data) {
        //         $data->whereBetween('date_paid', [$this->start_date, $this->end_date])->orderBy('date_paid', 'asc');
        //     }]);
        // })
        // ->when($this->hasEtapa, function($etapa){
        //     $etapa->where('id', $this->etapa);
        // })->when($this->stand, function($queryUser){
        //     $queryUser->whereHas('stands', function($subQuery){
        //         $subQuery->where('name', '=', $this->stand);
        //     });
        // })->when($this->hasUser, function($q){
        //     $q->whereHas('stands', function($subQuery){
        //         $subQuery->with('details.summary')->whereHas('details', function($subQuery2){
        //             $subQuery2->whereHas('summary', function($summary) {
        //                 $summary->where('user_id', $this->user);
        //             });
        //         });
        //     });
        // })->get();
        if($this->validarFechas())
        {
            $rules = [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:'.$this->start_date,
            ];
            $rules = [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:'.$this->start_date,
            ];
            try {
                $this->validate($rules);
                $this->url = "/admin/reporte/rpt/pdf?inicio=".$this->start_date."&fin=".$this->end_date."&etapa=".$this->etapa."&hasEtapa=".$this->hasEtapa."&hasUser=".$this->hasUser."&user=".$this->user."&stand=".$this->stand;
            } catch (\Throwable $th) {
                $this->emit('error', $th->getMessage());
            }
        }
        // dd($this->url);
        // $pdf = Pdf::loadView('pdf.reporte_2024.reporte-ingreso', compact('data'))->setPaper('a4', 'landscape')->output();
        // return response()->streamDownload(
        //     fn () => print($pdf),
        //     'Reporte_de_ingresos_'.$this->start_date.'_al_'.$this->end_date.'.pdf',
        // );

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
