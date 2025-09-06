<?php

namespace App\Services;

use App\Models\Setting;

class LimitDateService
{
    public $company;
    public $numberDays = 0;
    public function __construct()
    {
        $this->company = Setting::first();
    }

    private function canUseLimiteDateIncome()
    {
        return $this->company->before_date_add;
    }

    private function canUseLimiteDateExpense()
    {
        return $this->company->before_date_out;
    }

    public function getIncomeNumberDays()
    {
        if($this->canUseLimiteDateIncome()){
            $numberDays = $this->company->number_of_days_add;
            $this->numberDays = $numberDays;
        }
        return $this->numberDays;
    }
    public function getExpenseNumberDays()
    {
        if($this->canUseLimiteDateExpense()){
            $numberDays = $this->company->number_of_days_out;
            $this->numberDays = $numberDays;
        }
        return $this->numberDays;     
    }
}