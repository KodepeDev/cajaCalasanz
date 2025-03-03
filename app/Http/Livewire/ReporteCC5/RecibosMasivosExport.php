<?php

namespace App\Http\Livewire\ReporteCC5;

use Carbon\Carbon;
use App\Models\Summary;
use Livewire\Component;

class RecibosMasivosExport extends Component
{
    public $summarys, $serie, $numero1, $numero2, $idSummary;
    public $motivo_anulacion, $date, $summaryDate;
    public $limitDate;//para anulacion de recibo
    public $iframeId;
    public $url;
    public $limit = 50;

    public function mount()
    {
        $this->serie = 'I001';
        $this->idSummary = null;

        $this->iframeId = "I001";

        $hoy = Carbon::now();
        $this->limitDate = $hoy->format('Y-m-d');

        $this->serie = session('serie', $this->serie);
        $this->numero1 = session('numero1', $this->numero1);
        $this->numero2 = session('numero2', $this->numero2);
    }

    public function render()
    {
        return view('livewire.reporte-c-c5.recibos-masivos-export')->extends('adminlte::page');
    }

    public function getReceipts()
    {
        if((intval($this->numero2) - intval($this->numero1)) <= $this->limit){
            if($this->serie && ($this->numero1 < $this->numero2)){
                $this->summarys = Summary::where('type', 'add')->where('recipt_series', $this->serie)->whereBetween('recipt_number', [$this->numero1, $this->numero2])->get();
                if($this->summarys){
                    $this->idSummary = rand(5, 15);
                }else {
                    $this->emit('error', 'No se pudo encontrar los recibos con los datos ingresados');
                    $this->idSummary = null;
                }
            }else {
                $this->emit('error', 'Ingrese la serie y el rango correcto');
                $this->idSummary = null;
            }
            $this->url = '/admin/movimientos/rpts/masivosPdf/'. '?serie=' . $this->serie . '&numero1=' . $this->numero1 . '&numero2=' . $this->numero2 . '';
        }else {
            $this->emit('error', 'El limite de consulta es de 50 recibos');
            $this->url = '';
            $this->idSummary = rand(5, 15);
        }

        session(['serie' => $this->serie]);
        session(['numero1' => $this->numero1]);
        session(['numero2' => $this->numero2]);



        // dd($this->summarys);
    }
}
