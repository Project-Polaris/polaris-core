<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bridge extends Model
{
    /** @use HasFactory<\Database\Factories\BridgeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * @phpstan-ignore property.phpDocType
     */
    protected $fillable = [
        'name',
        'provider',
        'provider_params',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'provider' => 'string',
            'provider_params' => 'collection',
        ];
    }
}
