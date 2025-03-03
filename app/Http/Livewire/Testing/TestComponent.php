<?php

namespace App\Http\Livewire\Testing;

use App\Models\Detail;
use Livewire\Component;

class TestComponent extends Component
{
    public $selected_id;
    public function render()
    {

        $detales = Detail::whereHas('stand', function ($query) {
            $query->where('stage_id', 1)->orderBy('name', 'desc');
        })->whereStatus(false)->where('category_id', 15)->whereType(2)->whereSummaryType('add');

        return view('livewire.testing.test-component', compact('detales'))->extends('adminlte::page');
    }

    public function deleteRow($row)
    {
        $detail = Detail::findOrFail($row);
        $detail->delete();
    }
}
