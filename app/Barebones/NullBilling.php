<?php

namespace App\Barebones;

use App\Interfaces\IBilling;
use App\Models\User;
use App\Models\Server;
use App\Interfaces\IProtocol;

class NullBilling implements IBilling {
    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new NullBilling;
        }
        return self::$instance;
    }

    public function getUserStatus(User $user) {
        return [];
    }

    public function checkUserProtocolAccess(User $user, Server $server, IProtocol $protocol) {
        return true;
    }
}
