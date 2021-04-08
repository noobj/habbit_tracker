<?php


namespace App\Contracts;


interface ThirdPartyFetchingService
{
    public function fetchDailySummaryFromThirdParty(string $date) : array;

    public function updateDailySummary(array $summary, string $date);
}
