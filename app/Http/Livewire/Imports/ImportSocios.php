<?php

namespace App\Http\Livewire\Imports;

use Livewire\Component;
use App\Imports\PartnersImport;
use App\Models\Customer;
use App\Models\Partner;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportSocios extends Component
{
    use WithFileUploads;

    public $full_name, $document, $file;
    public $data = [];

    public function render()
    {
        return view('livewire.imports.import-socios')->extends('adminlte::page');
    }

    public function validar()
    {
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:1024',
        ]);
        $import = new PartnersImport();
        Excel::import($import, $this->file);

        $this->data = $import->data;

        // dd($this->data);

        $this->emit('want_save');
    }

    public function saveImport()
    {
        foreach ($this->data as $row) {

            $data = [
                'full_name' => $row['nombres'],
                'document_type' => 1,
                'document' => $row['dni'],
                'is_active' => true,
                'is_client' => true,
            ];
            // dd($data);

            $socio = Partner::create($data);

            $data2 = [
                'full_name' => $row['nombres'],
                'document_type' => 1,
                'document' => $row['dni'],
                'is_active' => true,
                'is_client' => true,
                'partner_id' => $socio->id,
            ];

            Customer::create($data2);
        }

        return redirect()->route('import.socios');
    }
}
