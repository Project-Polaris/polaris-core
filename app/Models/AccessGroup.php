<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AccessGroup extends Model {
    /** @use HasFactory<\Database\Factories\AccessGroupFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * @phpstan-ignore property.phpDocType
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the ingresses associated with the access group.
     *
     * @return BelongsToMany<Ingress, $this>
     */
    public function ingresses(): BelongsToMany {
        return $this->belongsToMany(Ingress::class);
    }

    /**
     * Get the packages associated with the access group.
     *
     * @return BelongsToMany<Package, $this>
     */
    public function packages(): BelongsToMany {
        return $this->belongsToMany(Package::class);
    }
}
