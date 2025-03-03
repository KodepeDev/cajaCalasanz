<?php

namespace App\Http\Livewire\ReporteCC5;

use Carbon\Carbon;
use App\Models\Summary;
use Livewire\Component;
use App\Models\NulledDetail;
use Illuminate\Support\Facades\Auth;

class BuscarReciboComponent extends Component
{
    public $summary, $serie, $numero, $showA4, $showCc5, $idSummary;
    public $motivo_anulacion, $date, $summaryDate;
    public $limitDate;//para anulacion de recibo
    public $iframeId;
    public function mount()
    {
        $this->showA4 = false;
        $this->showCc5 = false;
        $this->serie = 'I001';
        $this->idSummary = null;

        $this->iframeId = "I001";

        $hoy = Carbon::now();
        $this->limitDate = $hoy->format('Y-m-d');

        $this->serie = session('serie', $this->serie);
        $this->numero = session('numero', $this->numero);
    }

    protected $listeners = [
        'anularRecibo' => 'confirmarAnulacion',
    ];

    public function render()
    {
        return view('livewire.reporte-c-c5.buscar-recibo-component')->extends('adminlte::page');
    }

    public function getReceipt()
    {
        if($this->serie && $this->numero){
            $this->summary = Summary::where('recipt_series', $this->serie)->where('recipt_number', $this->numero)->first();
            if($this->summary){
                $this->showA4 = true;
                $this->idSummary = $this->summary->id;
            }else {
                $this->emit('error', 'No se pudo encontrar el recibo con los datos ingresados');
                $this->showA4 = false;
                $this->idSummary = null;
            }
        }else {
            $this->emit('error', 'Ingrese la serie y el numero del recibo');
            $this->showA4 = false;
            $this->idSummary = null;
        }

        session(['serie' => $this->serie]);
        session(['numero' => $this->numero]);
    }

    public function changeType($type)
    {
        if ($type == 1)
        {
            $this->showA4 = true;
            $this->showCc5 = false;
        }else {
            $this->showCc5 = true;
            $this->showA4 = false;
        }
    }
    public function showAnularModal()
    {
        $this->emit('showAnularModal');
        $this->date = Carbon::now()->format('Y-m-d');
    }
    public function cancelar()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }
    public function confirmarAnulacion()
    {
        $this->summaryDate = $this->summary->date->format('Y-m-d');
        $rules = [
            'summaryDate' => 'date|after_or_equal:'.$this->limitDate,
            'idSummary' => 'required',
            'motivo_anulacion' => 'required',
        ];
        try {
            $this->validate($rules);
            $summary = $this->summary;
            $details = $summary->details;
            // dd($summary, $details);
            $summary->update([
                'status' => 'NULLED',
                'nulled_motive' => $this->motivo_anulacion .' - '. Auth::user()->first_name
            ]);
            foreach ($details as $detail){

                $hasStudent = $detail->student ? true : false;

                $nulleds = NulledDetail::create([
                    'summary_type' => $detail->summary_type,
                    'type' => $detail->type,
                    'description' => $detail->description,
                    'amount' => $detail->amount,
                    'date' => $detail->date,
                    'date_paid' => $detail->date_paid,
                    'category' => $detail->category->name,
                    'student' => $detail->student ? $detail->student->full_name : '',
                    'summary_id' => $summary->id,
                ]);

                if ($hasStudent) {
                    $detail->update([
                        'status' => 0,
                        'date_paid' => null,
                        'summary_id' => null,
                    ]);
                } else {
                    $detail->delete();
                }
            }

            $this->emit('closeModalAnular', 'Anulacion completadad con éxito');
            $this->iframeId = "RI012";
            $this->getReceipt();

        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }
}
