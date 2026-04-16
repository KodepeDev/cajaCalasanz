<?php

namespace App\Http\Livewire\Movimientos\Transferencias;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Summary;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransferenciaComponent extends Component
{
    public $cuenta1   = -1;
    public $cuenta2   = -1;
    public $amount;
    public $date;

    public function render()
    {
        $today = Carbon::today()->toDateString();

        $balanceOf = function (int $accountId) use ($today): float {
            if ($accountId < 1) {
                return 0.0;
            }
            return (float) Summary::where('status', 'PAID')
                ->where('account_id', $accountId)
                ->where('date', '<=', $today)
                ->selectRaw("SUM(CASE WHEN type='add' THEN amount ELSE -amount END) as net")
                ->value('net');
        };

        return view('livewire.movimientos.transferencias.transferencia-component', [
            'cuentas'       => Account::pluck('account_name', 'id'),
            'saldoCuenta1'  => $balanceOf($this->cuenta1),
            'saldoCuenta2'  => $balanceOf($this->cuenta2),
        ])->extends('adminlte::page');
    }

    public function crearTransferencia(): void
    {
        $today = Carbon::today()->toDateString();

        $this->validate([
            'date'    => 'required|date|after_or_equal:' . $today,
            'amount'  => 'required|numeric|min:0.01',
            'cuenta1' => 'required|not_in:-1',
            'cuenta2' => 'required|not_in:-1|different:cuenta1',
        ], [
            'date.required'       => 'La fecha es requerida.',
            'date.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
            'amount.required'     => 'El monto es requerido.',
            'amount.numeric'      => 'El monto debe ser un valor numérico.',
            'amount.min'          => 'El monto debe ser mayor a 0.',
            'cuenta1.not_in'      => 'Seleccione la cuenta de origen.',
            'cuenta2.not_in'      => 'Seleccione la cuenta de destino.',
            'cuenta2.different'   => 'La cuenta de origen y destino deben ser diferentes.',
        ]);

        $future   = $this->date <= $today ? 1 : 2;
        $customer = Customer::where('document', '99999999')->first();

        $account1 = Account::findOrFail($this->cuenta1);
        $account2 = Account::findOrFail($this->cuenta2);

        $destino = Summary::create([
            'date'        => $this->date,
            'concept'     => 'Transferencia recibida de: ' . $account1->account_name,
            'type'        => 'add',
            'future'      => $future,
            'amount'      => $this->amount,
            'user_id'     => Auth::id(),
            'account_id'  => $this->cuenta2,
            'category_id' => 1,
            'customer_id' => $customer->id,
        ]);

        $origen = Summary::create([
            'date'        => $this->date,
            'concept'     => 'Transferencia enviada a: ' . $account2->account_name,
            'type'        => 'out',
            'future'      => $future,
            'amount'      => $this->amount,
            'user_id'     => Auth::id(),
            'account_id'  => $this->cuenta1,
            'category_id' => 1,
            'customer_id' => $customer->id,
        ]);

        $transfer = Transfer::create([
            'id_add' => $destino->id,
            'id_out' => $origen->id,
        ]);

        $destino->update(['id_transfer' => $transfer->id]);
        $origen->update(['id_transfer'  => $transfer->id]);

        redirect()->route('movimientos.listado');
    }
}
