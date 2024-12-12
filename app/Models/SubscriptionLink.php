<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionLink extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionLinkFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * @phpstan-ignore property.phpDocType
     */
    protected $fillable = [
        'name',
        'order_id',
        'provider',
        'provider_params',
    ];

}
