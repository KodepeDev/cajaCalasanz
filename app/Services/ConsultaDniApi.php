<?php
namespace App\Services;

use App\Http\Controllers\ApiConsultasController;

class ConsultaDniApi {
    protected $data;
    public function search($dni)
    {
        $this->data = (new ApiConsultasController)->apiDni($dni);
        return $this->data;
    }
}
