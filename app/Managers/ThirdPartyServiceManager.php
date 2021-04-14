<?php

namespace App\Managers;

use App\Services\TogglService;
use Illuminate\Support\Manager;

class ThirdPartyServiceManager extends Manager {

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->services->time_record;
    }

    public function createTogglDriver(): TogglService
    {
        return new TogglService();
    }
}