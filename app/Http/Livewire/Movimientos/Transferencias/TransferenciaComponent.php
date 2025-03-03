<?php

namespace App\Http\Livewire\Movimientos\Transferencias;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;

class TransferenciaComponent extends Component
{
    public $cuenta1, $cuenta2, $cuentas, $saldoCuenta1, $saldoCuenta2, $hoy, $validezFecha;

    public $date, $amount, $future, $customer;

    public function mount()
    {
        $this->hoy = Carbon::now()->format('Y-m-d');
        $this->cuentas = Account::pluck('account_name', 'id');
        $this->validezFecha = true;
        $this->cuenta1 = -1;
        $this->cuenta2 = -1;
        $this->customer = Customer::where('document', '=', '99999999')->first();
    }
    public function render()
    {
        if($this->cuenta1 > 0){
            $this->saldoCuenta1 = Summary::where('account_id', '=', $this->cuenta1)->where('type', 'add')->where('date', '<=', $this->hoy)->sum('amount') - Summary::where('account_id', '=', $this->cuenta1)->where('type', 'out')->where('date', '<=', $this->hoy)->sum('amount');
        }else {
            $this->saldoCuenta1 = 0;
        }
        if($this->cuenta2 > 0){
            $this->saldoCuenta2 = Summary::where('account_id', '=', $this->cuenta2)->where('type', 'add')->where('date', '<=', $this->hoy)->sum('amount') - Summary::where('account_id', '=', $this->cuenta2)->where('type', 'out')->where('date', '<=', $this->hoy)->sum('amount');
        }else{
            $this->saldoCuenta2 = 0;
        }


        return view('livewire.movimientos.transferencias.transferencia-component')->extends('adminlte::page');
    }

    public function crearTransferencia()
    {
        $this->validarFechas();

        if ($this->date <= $this->hoy) {

            $this->future = 1;

        } else {

            $this->future = 2;

        }


        $rules = [
            'date' => 'required|date|after_or_equal:'.$this->hoy,
            'amount' => 'required|numeric|min:0',
            'cuenta1' => 'required|not_in:-1',
            'cuenta2' => 'required|not_in:-1|different:cuenta1',
        ];

        $messages = [
            'date.required' => 'La fecha es requerida',
            'date.date' => 'La fecha debe ser una fecha válida',
            'date.after_or_equal' => 'La fecha para esta transaccion no puede ser menor a la fecha de hoy',
            'amount.required' => 'El monto es requerido',
            'amount.numeric' => 'El monto debe ser un valor numerico',
            'amount.min' => 'El monto deberia ser como mínimo 0',
            'cuenta1.required' => 'La cuenta de origen es requerida',
            'cuenta1.not_in' => 'La cuenta de origen es requerida',
            'cuenta2.required' => 'La cuenta de destino es requerida',
            'cuenta2.not_in' => 'La cuenta de destino es requerida',
            'cuenta2.different' => 'La cuenta de origen y la cuenta de destino deben ser diferentes',
        ];

        $this->validate($rules, $messages);

        $account1 = Account::find($this->cuenta1);
        $account2 = Account::find($this->cuenta2);

        $destino = Summary::create([
            'date' =>  $this->date,
            'concept' => "Transferencia Recibida de: " .$account1->account_name,
            'type' => 'add',
            'future' => $this->future,
            'amount' => $this->amount,
            'user_id'=>Auth::id(),
            'account_id' => $this->cuenta2,
            'category_id' => 1,
            'customer_id' => $this->customer->id,
        ]);

        $origen = Summary::create([
            'date' =>  $this->date,
            'concept' => "Transferencia Enviada a: " .$account2->account_name,
            'type' => 'out',
            'future' => $this->future,
            'amount' => $this->amount,
            'user_id'=>Auth::id(),
            'account_id' => $this->cuenta1,
            'category_id' => 1,
            'customer_id' => $this->customer->id,
        ]);

        $transfer = Transfer::create([
            'id_add' => $destino->id,
            'id_out' => $origen->id,
        ]);

        $summary = Summary::find($destino->id);
        $summary->id_transfer = $transfer->id;
        $summary->save();

        $summary = Summary::find($origen->id);
        $summary->id_transfer = $transfer->id;
        $summary->save();

        $this->emit('added', 'Transferencia exitosa');

        return redirect()->route('movimientos.listado');

    }

    public function validarFechas()
    {
        $hoy = Carbon::now();

        if($this->date < $hoy->format('Y-m-d')){

            $this->emit('error_fecha', 'La fecha para esta transaccion no puede ser menor a la fecha de hoy');
            $this->validezFecha = false;

        }else{

           $this->validezFecha = true;
        }
    }
}
