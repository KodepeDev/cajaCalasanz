<?php

namespace App\Http\Livewire\Imports;

use App\Imports\ProvisionImport;
use App\Models\Detail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportProvisions extends Component
{
    use WithFileUploads;

    public $full_name, $document, $file;
    public $data = [];

    public function render()
    {
        return view('livewire.imports.import-provisions')->extends('adminlte::page');;
    }
    public function validar()
    {
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:1024',
        ]);
        $import = new ProvisionImport();
        Excel::import($import, $this->file);

        $this->data = $import->data;

        // dd($this->data);

        $this->emit('want_save');
    }

    public function saveImport()
    {
        foreach ($this->data as $row) {

            $data = [
                'status' => 0,
                'summary_type' => 'add',
                'type' => $row['type'],
                'description' => $row['description'],
                'amount' => $row['ammount'],
                'date' => $row['periodo'],
                'date_paid' => null,
                'category_id' => $row['category_id'],
                'stand_id' => $row['stand_id'],
                'partner_id' => $row['partner_id'],
                'summary_id' => null,
            ];
            // dd($data);

            $provision = Detail::create($data);
        }

        return redirect()->route('import.socios');
    }
}
