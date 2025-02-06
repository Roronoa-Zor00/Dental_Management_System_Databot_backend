<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Software extends Model
{
    use HasFactory, Uuids;

    protected $table = 'softwares';
    protected $primaryKey = 'id';

    protected $fillable = [
        'guid',
        'name'
    ];
}
