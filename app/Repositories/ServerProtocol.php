<?php

namespace App\Repositories;

trait ServerProtocol {
    public function getProtocols() {
        return collect(config('polaris.protocols'))->map(function ($p) {

        });
    }
}
