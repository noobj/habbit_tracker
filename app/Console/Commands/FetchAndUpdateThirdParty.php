<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminated\Console\Loggable;
use App\Contracts\ThirdPartyFetchingService;

class FetchAndUpdateThirdParty extends Command
{
    use Loggable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:FetchAndUpdateThirdParty {days=1 : Day interval to fetch from third party (including Today), default is today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch from third party And update database';

    protected $thirdPartyService;

    /**
     * Create a new command instance.
     *
     * @param ThirdPartyFetchingService $thirdPartyService
     * @return void
     */
    public function __construct(ThirdPartyFetchingService $thirdPartyService)
    {
        parent::__construct();

        $this->thirdPartyService = $thirdPartyService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $daySince = $this->argument('days');
        for ($i = 0; $i < $daySince ; $i++) {
            $date = Carbon::today()->sub($i, 'day')->toDateString();
            $summary = $this->thirdPartyService->fetchDailySummaryFromThirdParty($date);
            if ($summary == null) continue;

            $updateInfo = $this->thirdPartyService->updateDailySummary($summary, $date);
            $this->logInfo("====================$date====================");
            $this->logInfo($updateInfo);
            $this->logInfo("==================================================");
        }
    }
}
