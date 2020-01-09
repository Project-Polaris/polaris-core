<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name', 'description', 
        'ip', 'balancer_index', 'group_id', 
        'flow_limit', 'flow_limit_reset_day',
        'enabled', 'last_active',
    ];

    protected $casts = [
        'last_active' => 'datetime',
    ];

    public function server_group() {
        return $this->belongsTo(ServerGroup::class);
    }
}
