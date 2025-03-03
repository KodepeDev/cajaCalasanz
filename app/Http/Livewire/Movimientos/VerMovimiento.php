<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use App\Models\Summary;
use App\Services\TipoCambioService;
use Livewire\Component;

class VerMovimiento extends Component
{
    public $cuentas, $paymentMethods, $categorias, $subcategorias, $componentName, $selected_id, $movimiento;

    public $date, $hour, $concept, $type, $amount, $tax, $recipt_series,$puesto, $recipt_number, $future, $account_id, $category_id, $subcategoria_id, $payment_method, $numero_operacion, $user_id, $customer_id, $partner_id, $sublet_id;

    public $tc;

    public function mount($id)
    {
        $this->selected_id = $id;
        $summary = Summary::find($id);
        $this->movimiento = $summary;
        $this->hour = Carbon::parse($summary->created_at)->format('h:i A');
        $this->date = $summary->date->format('d/m/Y');
        $this->concept = $summary->concept;
        $this->type = $summary->type;
        $this->amount = $summary->amount;
        $this->tax = $summary->tax;
        $this->recipt_series = $summary->recipt_series;
        $this->recipt_number = $summary->recipt_number;
        $this->numero_operacion = $summary->numero_operacion;
        $this->tc = $summary->tipo_cambio;

        // $this->calcularTipoCambio();
    }
    public function render()
    {
        return view('livewire.movimientos.ver-movimiento')->extends('adminlte::page');
    }


    // public function calcularTipoCambio()
    // {
    //     $tipoCambio = new TipoCambioService;
    //     $this->tc = $tipoCambio->getValue(Carbon::parse($this->date)->format('Y-m-d'));
    // }
}
