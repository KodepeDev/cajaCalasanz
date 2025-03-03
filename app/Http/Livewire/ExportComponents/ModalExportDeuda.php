<?php

namespace App\Http\Livewire\ExportComponents;

use App\Models\Stand;
use App\Models\Detail;
use Livewire\Component;

class ModalExportDeuda extends Component
{
    public $stand, $total_prov, $total_prov_dolar, $socio;
    public $provision_detalles = [];

    public function mount()
    {
        $this->total_prov = 0;
        $this->total_prov_dolar = 0;
    }

    public function render()
    {
        return view('livewire.export-components.modal-export-deuda');
    }

    public function BuscarDeuda()
    {
        $this->provision_detalles = Detail::whereHas('stand', function ($query) {
            $query->where('name', '=', $this->stand);
        })->whereStatus(false)->orderBy('date', 'desc')->get();

        $sum = Detail::whereHas('stand', function ($query) {
            $query->where('name', '=', $this->stand);
        })->whereStatus(false)->get();

        $this->total_prov = $sum->where('currency_id', '!=', 2)->sum('amount');
        $this->total_prov_dolar = $sum->where('currency_id', 2)->sum('amount');

        $stand = Stand::where('name', '=', $this->stand)->first();

        if ($stand){
            $this->socio = $stand->partner->full_name;
        }
    }
}
