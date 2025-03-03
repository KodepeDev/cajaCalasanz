<?php

namespace App\Http\Livewire\Sistema;

use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanySettings extends Component
{
    use WithFileUploads;

    public $currencies, $defaultCurrency, $companyName, $companyRuc, $companyEmail, $companyPhone, $activeBackDateOnAdd, $daysOnAdd, $activeBackDateOut, $daysOut, $reportType, $receiptType;
    public $logo, $logoActual, $logoId;

    public function mount()
    {
        $this->currencies = Currency::pluck('id', 'code');
        $company = Setting::first();
        $this->defaultCurrency = $company->default_currency;
        $this->activeBackDateOnAdd = $company->before_date_add;
        $this->daysOnAdd = $company->number_of_days_add;
        $this->activeBackDateOut = $company->before_date_out;
        $this->daysOut = $company->number_of_days_out;
        $this->reportType = $company->report_type;
        $this->receiptType = $company->receipt_type;
        $this->companyName = $company->company_name;
        $this->companyRuc = $company->company_ruc;
        $this->companyEmail = $company->email;
        $this->companyPhone = $company->phone;
        $this->logoActual = $company->foto;
        Config::set('kodepe.logo', 'storage/system/'.$company->logo);

        // dd(config('kodepe.logo'));
        // dd($this->logoActual);

        $this->logoId = rand(10, 12);
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'image|max:1024',
        ]);
        // $this->logoId = rand(10, 12);
    }

    public function render()
    {
        return view('livewire.sistema.company-settings')->extends('adminlte::page');
    }

    public function save()
    {
        $rules = [
            'companyName' => 'required|max:120|min:5',
            'companyRuc' => 'required|max:11|min:8',
            'companyEmail' => 'nullable|email',
            'companyPhone' => 'nullable|max:20',
            'activeBackDateOnAdd' => 'nullable',
            'daysOnAdd' => 'nullable',
            'activeBackDateOut' => 'nullable',
            'daysOut' => 'nullable',
            'reportType' => 'nullable',
            'receiptType' => 'nullable',
            'defaultCurrency' => 'nullable',
        ];

        $this->validate($rules);

        $data = [
            'company_name' => $this->companyName,
            'company_ruc' => $this->companyRuc,
            'email' => $this->companyEmail,
            'phone' => $this->companyPhone,
            'before_date_add' => $this->activeBackDateOnAdd,
            'number_of_days_add' => $this->daysOnAdd ? $this->daysOnAdd : 0,
            'before_date_out' => $this->activeBackDateOut,
            'number_of_days_out' => $this->daysOut ? $this->daysOut : 0,
            'report_type' => $this->reportType,
            'receipt_type' => $this->receiptType,
            'default_currency' => $this->defaultCurrency,
        ];

        // dd($data);

        $company = Setting::first();

        $imagenAntigua = $company->foto;

        $company->update($data);

        if($this->logo)
        {
            $customFileName = uniqid(). '_.' .$this->logo->extension();
            $this->logo->storeAs('public/system/', $customFileName);

            $company->logo = $customFileName;
            $company->save();

            if ($imagenAntigua != null) {

                if (file_exists('storage/system/' .$imagenAntigua)) {

                    unlink('storage/system/' .$imagenAntigua);
                }
            }
        }

        $this->emit('companyUpdated', 'Los datos han sido actualizados de forma exitosa');
    }

}
