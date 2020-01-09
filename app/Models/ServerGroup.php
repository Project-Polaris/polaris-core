<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerGroup extends Model
{
    protected $fillable = [
        'id', 'name',
    ];

    public function servers() {
        return $this->hasMany(Server::class);
    }
}
