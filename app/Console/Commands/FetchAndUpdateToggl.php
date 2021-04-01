<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use App\Services\TogglService;
use Illuminated\Console\Loggable;

class FetchAndUpdateToggl extends Command
{
    use Loggable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:FetchAndUpdateToggl {days=1 : Day interval to fetch from Toggl (including Today), default is today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch from Toggl And update database';

    protected $togglService;

    /**
     * Create a new command instance.
     *
     * @param TogglService $togglService
     * @return void
     */
    public function __construct(TogglService $togglService)
    {
        parent::__construct();

        $this->togglService = $togglService;
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
            $summary = $this->togglService->fetchDailySummaryFromToggl($date);
            if ($summary == null) continue;

            $updateInfo = $this->togglService->updateDailySummary($summary, $date);
            $this->logInfo("====================$date====================");
            $this->logInfo($updateInfo);
            $this->logInfo("==================================================");
        }
    }
}
