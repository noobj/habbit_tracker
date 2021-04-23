<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SummaryService;
use App\Services\TelegramService;
use Carbon\Carbon;

class NotifyDailyProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:NotifyDailyProgress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the weekly summary so far and notify users';

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
     */
    public function handle(SummaryService $summaryService, TelegramService $telegramService)
    {
        $today = Carbon::today()->endOfDay();
        $monday = Carbon::today()->startOfWeek()->toDateString();
        $project = 'meditation';

        $summary = $summaryService->getRangeSummary($project, $monday, $today);
        $total = $summaryService->convertRawDurationToFormat($summary);

        $todayDateString = $today->toDateString();
        $message = "[[$todayDateString]] You have 🧘 for *$total* so far this week! KEEP GOING!";
        $telegramService->send($message);
    }
}
