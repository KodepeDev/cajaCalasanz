<?php

namespace App\Http\Livewire\SchoolYear;

use App\Models\SchoolYear;
use Livewire\Component;

class SchoolYearSwitcher extends Component
{
    public $schoolYear;
    public function mount()
    {
        $this->schoolYear = SchoolYear::current();
    }
    public function render()
    {
        return view("livewire.school-year.school-year-switcher");
    }
}
