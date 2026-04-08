<?php

namespace App\Http\Livewire\SchoolYear;

use App\Models\SchoolYear;
use Livewire\Component;

class SchoolYearSwitcher extends Component
{
    public $currentSchoolYear;
    public $schoolYears;

    public function mount()
    {
        $this->schoolYears = SchoolYear::orderBy("year", "desc")->get();
        $id = session("current_school_year_id");
        $this->currentSchoolYear = $id
            ? SchoolYear::find($id)
            : SchoolYear::current();
    }

    public function switchYear($id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        session(["current_school_year_id" => $schoolYear->id]);
        $this->currentSchoolYear = $schoolYear;

        // Full page reload so every component re-runs its queries with the new session value
        return redirect(request()->header("Referer"));
    }

    public function render()
    {
        return view("livewire.school-year.school-year-switcher");
    }
}
