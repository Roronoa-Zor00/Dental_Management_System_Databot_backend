<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, Uuids;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'guid',
        'title',
        'body',
        'url_action',
        'user_id',
        'is_read',
        'is_read_admin',
        'created_by',
        'is_read_case_submission',
        'is_admin_id'
    ];

}
