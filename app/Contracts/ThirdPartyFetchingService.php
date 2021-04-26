<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ThirdPartyFetchingService
{
    public function fetch(string $startDate, string $endDate);

    public function save(array $summaries);
}
