<?php

namespace App\Interfaces;

use App\Models\User;
use App\Models\Server;
use App\Models\Prototypes\UserBillingStatus;
use App\Interfaces\IProtocol;

interface IBilling {
    public static function getInstance(): IBilling;
    public function getUserStatus(User $user): ?array;
    public function checkUserProtocolAccess(User $user, Server $server, IProtocol $protocol): bool;
}
