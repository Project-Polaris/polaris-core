<?php 

namespace App\Interfaces;

use App\Models\Server;
use App\Models\User;

interface IProtocol {
    public static function getInstance(): IProtocol;
    public static function getName(): string;
    public static function getShortName(): string;
    public function getByServer(Server $server): ?array;
    public function addToServer(Server $server);
    public function deleteFromServer(Server $server);
    public function getByServerUser(Server $server, User $user);
}
