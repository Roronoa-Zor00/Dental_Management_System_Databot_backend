<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class ApiController extends Controller
{
    public $orderBy;
    public $orderSort;
    public $total;
    public $paginate;
    public $perPage;
    public $currentPage;
    public $filter;


    public function __construct()
    {


    }

    public function loggedIn_user()
    {
        return  $this->guard()->user();
    }
}

