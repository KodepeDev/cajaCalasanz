<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use App\Models\Summary;
use Livewire\Component;

class AnularMovimiento extends Component
{
    public $date, $motivo_anulacion, $codigo, $movimiento;
    protected $listeners = [
        'Anular' => 'AnularMovimiento'
    ];
    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }
    public function render()
    {
        return view('livewire.movimientos.anular-movimiento');
    }

    public function AnularMovimiento($id)
    {
        $this->movimiento = Summary::findOrFail($id);
        $this->codigo = $this->movimiento->recipt_series.'-'.$this->movimiento->recipt_number;
        $this->emit('show-modal-anular', 'mostrar modal');
    }
    public function confirmarAnulacion()
    {
        $rules = [
            'motivo_anulacion' => 'required|min:5',
        ];
        $messages = [
            'motivo_anulacion.required' => 'El motivo de anulacion es requerido.',
            'motivo_anulacion.min' => 'El motivo de anulacion debe tener como mínimo 5 caracteres.',
        ];

        $this->validate($rules, $messages);

        $this->movimiento->update([
            'status' => 'NULLED',
            'nulled_motive' => $this->motivo_anulacion,
            'updated_at' => $this->date,
        ]);

        $detalles = $this->movimiento->details;

        foreach ($detalles as $detalle) {
            $detalle->update([
                'amount' => 0,
            ]);
        }

        $this->emit('movimiento_anulado', 'El movimiento ha sido anulado con exito');
        $this->emit('render');

        $this->cancelar();
    }
    public function cancelar()
    {
        $this->codigo = '';
        $this->movimiento = '';
    }
}
