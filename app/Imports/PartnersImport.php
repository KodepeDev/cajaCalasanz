<?php

namespace App\Imports;

use App\Models\Partner;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PartnersImport implements ToCollection, WithHeadingRow
{
    public $data = [];

    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {
                if($row['dni'] !== null){

                    $this->data[] = [
                        'dni' => str_pad($row['dni'], 8, '0', STR_PAD_LEFT),
                        'nombres' =>  $row['nombre'],
                    ];
                }
            }
            // dd($this->data);
            // return $this->data;
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
