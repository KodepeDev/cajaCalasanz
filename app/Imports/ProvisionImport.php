<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Stand;
use App\Models\Detail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProvisionImport implements ToCollection, WithHeadingRow
{
    public $data = [];

    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {
                if($row['stand'] !== null){
                    $stand = Stand::where('name', $row['stand'])->first();
                    $periodo = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['periodo'])->format('Y-m');

                    // dd($stand, $periodo);
                    $this->data[] = [
                        'type' => 2,
                        'description' => $row['concepto'],
                        'periodo' => $periodo,
                        'ammount' => $row['monto'],
                        'category_id' =>  $row['categoria'],
                        'stand_id' =>  $stand->id,
                        'partner_id' =>  $stand->partner_id,
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
