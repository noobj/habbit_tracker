<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminated\Console\Loggable;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Illuminated\Console\Loggable\FileChannel\MonologFormatter;

class FetchAndUpdateThirdParty extends Command
{
    use Loggable;

    /**
     * Initialize error handling.
     *
     * @return void
     */
    private function initializeErrorHandling()
    {
    }

    /**
     * Override the default get handler method in Loggable/FileChannel
     *
     * @return \Monolog\Handler\RotatingFileHandler
     */
    protected function getFileChannelHandler()
    {
        $config = $this->laravel['config']["logging.channels.single"];
        $handler = new StreamHandler(
            'php://stderr', Logger::INFO,
            $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false
        );

        $handler->setFormatter(new MonologFormatter);

        return $handler;
    }

    /**
     * Disable logging useless header info
     *
     * @return void
     */
    private function logIterationHeaderInformation()
    {
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:FetchAndUpdateThirdParty {days=1 : Day interval to fetch from third party (including Today), default is today} {project? : projectname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch from third party And update database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $manager = $this->laravel['third_party_service'];
        $daySince = $this->argument('days');
        $project = $this->argument('project') ?? 'meditation';

        $startDate = Carbon::today()->sub($daySince, 'day')->toDateString();
        $endDate = Carbon::tomorrow()->toDateString(); // include today
        $summaries = $manager->fetch($startDate, $endDate, $project);

        if ($summaries == null) {
            $this->logInfo("No data in this period");
            exit;
        }

        $updateInfo = $manager->save($summaries);
        $this->logInfo("=============$startDate ~ $endDate=============");
        $this->logInfo($updateInfo);
        $this->logInfo("==================================================");
    }
}
