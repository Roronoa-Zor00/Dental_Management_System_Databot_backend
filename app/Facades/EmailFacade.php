<?php

    namespace App\Facades;

    /**
     * @see EmailLibrary
     */

    use Illuminate\Support\Facades\Facade;
    use App\Libraries\EmailLibrary;

    class EmailFacade extends Facade
    {
        protected static function getFacadeAccessor(): string
        {
            return 'email';
        }
    }