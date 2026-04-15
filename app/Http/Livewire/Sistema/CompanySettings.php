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

    // Company info
    public string $companyName    = '';
    public string $companyRuc     = '';
    public string $companyEmail   = '';
    public string $companyPhone   = '';
    public string $companyAddress = '';
    public string $companyWebsite = '';

    // Logo
    public $logo;
    public string $logoActual = '';

    // Financial config
    public $defaultCurrency;
    public $currencies;
    public string $receiptType = '';
    public string $reportType  = '';

    // Date config
    public bool $activeBackDateOnAdd = false;
    public int  $daysOnAdd          = 0;
    public bool $activeBackDateOut  = false;
    public int  $daysOut            = 0;

    public function mount(): void
    {
        $this->currencies = Currency::pluck('id', 'code');
        $company = Setting::first();

        $this->companyName    = $company->company_name    ?? '';
        $this->companyRuc     = $company->company_ruc     ?? '';
        $this->companyEmail   = $company->email           ?? '';
        $this->companyPhone   = $company->phone           ?? '';
        $this->companyAddress = $company->address         ?? '';
        $this->companyWebsite = $company->website         ?? '';
        $this->logoActual     = $company->foto;

        $this->defaultCurrency    = $company->default_currency;
        $this->receiptType        = $company->receipt_type    ?? '';
        $this->reportType         = $company->report_type     ?? '';

        $this->activeBackDateOnAdd = (bool) $company->before_date_add;
        $this->daysOnAdd           = (int) $company->number_of_days_add;
        $this->activeBackDateOut   = (bool) $company->before_date_out;
        $this->daysOut             = (int) $company->number_of_days_out;
    }

    public function updatedLogo(): void
    {
        $this->validateOnly('logo', [
            'logo' => 'image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);
    }

    public function render()
    {
        return view('livewire.sistema.company-settings')->extends('adminlte::page');
    }

    public function save(): void
    {
        $this->validate([
            'companyName'        => 'required|min:5|max:120',
            'companyRuc'         => 'required|min:8|max:11',
            'companyEmail'       => 'nullable|email|max:100',
            'companyPhone'       => 'nullable|max:20',
            'companyAddress'     => 'nullable|max:255',
            'companyWebsite'     => 'nullable|url|max:255',
            'activeBackDateOnAdd' => 'boolean',
            'daysOnAdd'          => 'integer|min:0|max:365',
            'activeBackDateOut'  => 'boolean',
            'daysOut'            => 'integer|min:0|max:365',
            'reportType'         => 'nullable|max:50',
            'receiptType'        => 'nullable|max:50',
            'defaultCurrency'    => 'nullable|exists:currencies,id',
            'logo'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ]);

        $company = Setting::first();

        $company->update([
            'company_name'       => $this->companyName,
            'company_ruc'        => $this->companyRuc,
            'email'              => $this->companyEmail,
            'phone'              => $this->companyPhone,
            'address'            => $this->companyAddress,
            'website'            => $this->companyWebsite,
            'before_date_add'    => $this->activeBackDateOnAdd,
            'number_of_days_add' => $this->daysOnAdd,
            'before_date_out'    => $this->activeBackDateOut,
            'number_of_days_out' => $this->daysOut,
            'report_type'        => $this->reportType,
            'receipt_type'       => $this->receiptType,
            'default_currency'   => $this->defaultCurrency,
        ]);

        if ($this->logo) {
            $oldFile = $company->logo;

            $filename = uniqid() . '_.' . $this->logo->extension();
            $this->logo->storeAs('public/system/', $filename);

            $company->logo = $filename;
            $company->save();

            if ($oldFile && file_exists(public_path("storage/system/{$oldFile}"))) {
                @unlink(public_path("storage/system/{$oldFile}"));
            }

            Config::set('kodepe.logo', "storage/system/{$filename}");
            $this->logoActual = $company->fresh()->foto;
            $this->logo = null;
            $this->dispatchBrowserEvent('reset-file-input');
        }

        $this->emit('companyUpdated', 'Configuración actualizada correctamente.');
    }
}
