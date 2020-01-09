<?php

namespace App;

use Illuminate\Support\ServiceProvider;

abstract class PluginBase extends ServiceProvider {
    abstract public function getName(): string;
    abstract public function getDescription(): string;
    abstract public function getVersion(): string;
    abstract public function getPackageRepositoryUrl(): string;
    abstract public function getVendorName(): string;
}
