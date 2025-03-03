<?php

namespace App\Http\Livewire\Stands;

use App\Models\Detail;
use App\Models\Stage;
use App\Models\Stand;
use App\Models\Partner;
use Livewire\Component;
use Livewire\WithPagination;

class StandComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'resetUI',
    ];

    public $selected_id, $componentName, $stages, $partners, $name, $stage_id, $partner_id, $stand;

    public function updatedPartnerId()
    {
        $this->emit('select2Refresh');
    }

    public function mount()
    {
        $this->selected_id = 0;
        $this->componentName = 'Stands';
        $this->stages = Stage::all();
        $this->partners = Partner::where('is_active', 1)->pluck('id', 'full_name');
    }

    public function render()
    {
        if ($this->stand) {
            $stands = Stand::where('name', 'like', '%'.$this->stand.'%')->paginate(20);
        }else {
            $stands = Stand::paginate(20);
        }
        return view('livewire.stands.stand-component', compact('stands'))->extends('adminlte::page');
    }

    public function create()
    {
        $rules = [
            'name' => 'required|min:3|max:10|unique:stands',
            'stage_id' => 'required',
        ];

        $messages = [
            'name.required' => 'El código del stand es requerido.',
            'name.min' => 'El código del stand debe tener al menos 3 caracteres.',
            'name.max' => 'El código del stand debe tener menos de 10 caracteres.',
            'name.unique' => 'El código del stand ya se encuentra registrado.',
            'stage_id' => 'La etapa relacionada es requerido.',
        ];

        $this->validate($rules, $messages);

        $stand = Stand::create([
            'name' => $this->name,
            'stage_id' => $this->stage_id,
            'partner_id' => $this->partner_id,
        ]);

        $this->resetUI();
        $this->emit('new_stand', 'El stand sea ha registrado con exito');
    }

    public function edit(Stand $stand)
    {
        $this->selected_id = $stand->id;
        $this->name = $stand->name;
        $this->stage_id = $stand->stage_id;
        $this->partner_id = $stand->partner_id;

        $this->emit('mostrar_modal', 'mostrar_modal');
    }

    public function update()
    {
        $rules = [
            'name' => "required|min:3|max:10|unique:stands,name,$this->selected_id",
            'stage_id' => 'required',
            'partner_id' => 'nullable',
        ];

        $messages = [
            'name.required' => 'El código del stand es requerido.',
            'name.min' => 'El código del stand debe tener al menos 3 caracteres.',
            'name.max' => 'El código del stand debe tener menos de 10 caracteres.',
            'name.unique' => 'El código del stand ya se encuentra registrado.',
            'stage_id' => 'La etapa relacionada es requerido.',
        ];

        $this->validate($rules, $messages);

        $stand = Stand::find($this->selected_id);

        $stand->update([
            'name' => $this->name,
            'stage_id' => $this->stage_id,
            'partner_id' => $this->partner_id,
        ]);

        $deudas = Detail::where('stand_id', $this->selected_id)->where('status', false)->get();

        foreach ($deudas as $deuda) {
            $deuda->update([
                'partner_id' => $this->partner_id,
            ]);
        }

        $this->resetUI();
        $this->emit('new_stand', 'El stand sea ha Actualizado con exito');
    }

    public function resetUI()
    {
        $this->selected_id = 0;
        $this->name = '';
        $this->stage_id = '';
        $this->partner_id = '';

        $this->resetPage();
        $this->resetValidation();

        $this->emit('select2Refresh');
    }

}
