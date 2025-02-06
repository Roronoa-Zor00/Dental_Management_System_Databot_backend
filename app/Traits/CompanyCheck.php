<?php

namespace App\Traits;

use App\Scope\CompanyScope;

trait CompanyCheck
{
    public static function bootCompanyCheck()
    {
        static::addGlobalScope(new CompanyScope);
    }

}
