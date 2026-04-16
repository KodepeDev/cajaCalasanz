<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Summary;
use Livewire\Component;

class VerMovimiento extends Component
{
    public $movimiento;
    public $setting;

    public string $date = '';
    public string $hour = '';
    public ?float $tc   = null;

    public function mount(int $id): void
    {
        $this->movimiento = Summary::with([
            'customer',
            'account',
            'paymentMethod',
            'user',
            'student',
            'tutor.students',
            'details.category',
            'details.currency',
            'nulledDetails',
        ])->findOrFail($id);

        $this->date     = $this->movimiento->date->format('d/m/Y');
        $this->hour     = Carbon::parse($this->movimiento->created_at)->format('h:i A');
        $this->tc       = $this->movimiento->tipo_cambio;
        $this->setting  = Setting::first();
    }

    public function render()
    {
        return view('livewire.movimientos.ver-movimiento')
            ->extends('adminlte::page');
    }
}
