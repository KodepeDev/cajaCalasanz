<?php

namespace App\Imports;

use App\Models\Partner;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StandsImport implements ToCollection, WithHeadingRow
{
    public $data = [];

    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {
                $socioDoc = str_pad($row['dni'], 8, '0', STR_PAD_LEFT);
                $socioId = Partner::where('document', $socioDoc)->first()->id;
                $stand = strval($row['stand']);

                // dd($socioId, $stand);

                if($socioId !== null){

                    $this->data[] = [
                        'codigo' => $stand,
                        'partner_id' =>  $socioId,
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
