<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory, Uuids;

    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'guid',
        'user_id',
        'user_type',
        'entity',
        'action',
        'post_words',
        'route_name',
        'route_id',
        'url',
        'module',
        'comment_id',
        'insurance_type',
        'request_data',
        'show_team_activity',
    ];
}
