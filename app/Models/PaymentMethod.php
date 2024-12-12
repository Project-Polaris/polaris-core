<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentMethodFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * @phpstan-ignore property.phpDocType
     */
    protected $fillable = [
        'name',
        'icon',
        'provider',
        'provider_params',
        'fee_percentage',
        'fee_fixed',
        'active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'provider_params' => 'collection',
            'fee_percentage' => 'float',
            'fee_fixed' => 'float',
            'active' => 'bool',
        ];
    }
}
