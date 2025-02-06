<?php

namespace App\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;

class CompanyScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $user = auth('insurer')->user();
        if(!$user)
            $user = auth('insurer-api')->user();
        else if(!$user)
            $user = auth('customer-api')->user();

        if (isset($user->company_id)) {
            $builder->where('company_id', '=', $user->company_id);
        }
    }

}
