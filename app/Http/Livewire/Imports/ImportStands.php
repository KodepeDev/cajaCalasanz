<?php

namespace App\Http\Livewire\Imports;

use App\Imports\StandsImport;
use Livewire\Component;
use App\Models\Partner;
use App\Models\Stand;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportStands extends Component
{
    use WithFileUploads;

    public $file;
    public $data = [];

    public function render()
    {
        return view('livewire.imports.import-stands')->extends('adminlte::page');
    }

    public function validar()
    {
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:1024',
        ]);
        $import = new StandsImport();
        Excel::import($import, $this->file);

        $this->data = $import->data;

        // dd($this->data);

        $this->emit('want_save');
    }

    public function saveImport()
    {
        foreach ($this->data as $row) {

            $data = [
                'name' => $row['codigo'],
                'stage_id' => 1,
                'partner_id' => $row['partner_id'],
            ];
            // dd($data);

            $stand = Stand::create($data);
        }

        return redirect()->route('import.stands');
    }
}
