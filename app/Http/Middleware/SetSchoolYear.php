<?php

namespace App\Http\Middleware;

use App\Models\SchoolYear;
use Closure;
use Illuminate\Http\Request;

class SetSchoolYear
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('current_school_year_id')) {
            $schoolYear = SchoolYear::where('is_active', true)->first();
            if ($schoolYear) {
                session(['current_school_year_id' => $schoolYear->id]);
            }
        } else {
            // Validate stored ID still exists (guards against session tampering or deleted records)
            if (!SchoolYear::find(session('current_school_year_id'))) {
                session()->forget('current_school_year_id');
                $schoolYear = SchoolYear::where('is_active', true)->first();
                if ($schoolYear) {
                    session(['current_school_year_id' => $schoolYear->id]);
                }
            }
        }

        return $next($request);
    }
}
