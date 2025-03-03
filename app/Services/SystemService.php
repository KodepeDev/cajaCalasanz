<?php
namespace App\Services;

use App\Models\Setting;

class SystemService {
    protected $company;

    public function __construct()
    {
        // Obtiene el primer registro de la empresa. Cambia esto según tus necesidades.
        $this->company = Setting::first();
    }

    public function getCompanyLogo()
    {
        return $this->company->foto;
    }

    public function getCompanyName()
    {
        return $this->company->company_name;
    }
    public function getCompanyRuc()
    {
        return $this->company->company_ruc;
    }
}
