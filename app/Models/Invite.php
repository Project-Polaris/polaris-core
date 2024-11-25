<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Invite.
 * 
 * This invite is somewhat different than the "advertisement" type invite.
 * In this instance, that invite is called "promotion".
 * This invite is a redeemable one-time token enabled for 
 * limited registration.
 * 
 * @package App\Models
 */
class Invite extends Model
{
    /** @use HasFactory<\Database\Factories\InviteFactory> */
    use HasFactory;
}
