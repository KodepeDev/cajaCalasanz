<?php

namespace App\Http\Livewire\Bitacora;

use App\Models\Bitacora;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class BitacoraComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $hoy = Carbon::now();
        $start = $hoy->subMonths(1);
        $bitacoras = Bitacora::whereBetween('created_at', [$start, now()])->orderBy('created_at', 'desc')
                 ->paginate(10);
                //  dd($start, $hoy);

        return view('livewire.bitacora.bitacora-component', compact('bitacoras'))->extends('adminlte::page');
    }
}
