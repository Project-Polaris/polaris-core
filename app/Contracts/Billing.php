<?php

namespace App\Contracts;

use App\Models\User;
use App\Models\Server;
use App\Models\Prototypes\UserBillingStatus;

interface Billing {
    public function getUserStatus(User $user): ?array;
    public function checkUserProtocolAccess(User $user, Server $server, IProtocol $protocol): bool;
}
