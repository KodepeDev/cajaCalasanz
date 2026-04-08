<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SchoolYearScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $schoolYearId = session('current_school_year_id');
        if ($schoolYearId) {
            $builder->where($model->getTable() . '.school_year_id', $schoolYearId);
        }
    }
}
