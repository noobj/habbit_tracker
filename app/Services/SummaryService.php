<?php


namespace App\Services;


use App\Models\DailySummaries;
use App\Models\Projects;
use Carbon\Carbon;
use Exception;

class SummaryService
{
    const MAX_DURATION_LEVEL_INDEX = 5;
    const MAX_DURATION_LEVEL = 4;

    /**
     * Used for mapping the minute range to color level for frontend displaying
     * Rules as:
     *  0~30m => 1
     *  30m ~ 1h => 2
     *  1h ~ 2h => 3
     *  2h ~ => 4
     *
     * @var array
     */
    private $durationLevelMap = [
        0 => 1,
        1 => 2,
        2 => 3,
        3 => 3,
        4 => 3,
        5 => 3,
    ];

    /**
     * Get the project_id bt its name
     *
     * @param string $name
     * @return integer
     */
    public static function getProjectIdByName(string $name): ?int {
        $project = Projects::where('name', $name)->first();

        if (!$project) {
            throw new Exception('Project not found.');
        }

        return $project->id;
    }

    /**
     * Get the daily summary between given date range
     *
     * @param string $project
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getRangeDailySummary(string $project, string $startDate, string $endDate): array
    {
        $projectId = static::getProjectIdByName($project);

        return DailySummaries::whereBetween('date', [$startDate, $endDate])
            ->where('project_id', $projectId)
            ->select('id', 'date', 'duration')
            ->get()->toArray();
    }

    /**
     * Convert minute to hours:minutes format
     *
     * @param integer $minuteRaw
     * @return string
     */
    private function convertToHoursMins(int $minuteRaw, $format = '%dh%dm'): string
    {
        if ($minuteRaw < 1) {
            return '1m';
        }

        $hours = floor($minuteRaw / 60);
        $minutes = ($minuteRaw % 60);

        if ($hours == 0) return sprintf('%dm', $minutes);

        return sprintf($format, $hours, $minutes);
    }

    /**
     * Format milliseconds type
     *
     * @param string $rawDuration
     * @return void
     */
    public function convertRawDurationToFormat($rawDuration, $format = '%dh%dm') {
        $durationInMinute = $rawDuration / 1000 / 60;

        return $this->convertToHoursMins($durationInMinute, $format);
    }

    /**
     * Process the raw data format fetch from database
     *
     * @param array $rawData
     * @return array
     */
    public function processTheRawSummaries(array $rawData): array
    {
        $result = array_map(function($entry) {
            // Divide milliseconds duration into multiplier of 30 mins
            $levelIndex = $entry['duration'] / 1000 / 60 / 30;
            $level = $levelIndex > SELF::MAX_DURATION_LEVEL_INDEX ? SELF::MAX_DURATION_LEVEL : $this->durationLevelMap[$levelIndex];

            $entry['level'] = $level;
            $entry['duration'] = $this->convertRawDurationToFormat($entry['duration']);

            $entry['date'] = Carbon::createFromDate($entry['date'])->toFormattedDateString();
            $entry['timestamp'] = intval(Carbon::createFromDate($entry['date'])->getPreciseTimestamp(3));

            return $entry;
        }, $rawData);

        return $result;
    }

    /**
     * Get the daily summary between given date range
     *
     * @param string $project
     * @param string $startDate
     * @param string $endDate
     * @return integer
     */
    public function getRangeSummary(string $project, string $startDate, string $endDate): int
    {
        $projectId = $this->getProjectIdByName($project);

        return DailySummaries::whereBetween('date', [$startDate, $endDate])
            ->where('project_id', $projectId)
            ->sum('duration');
    }

    public function getPercentageStringOfGoal(int $milliseconds): string
    {
        if ($milliseconds == 0) {
            return "Start meditating bro";
        }

        $goalInMilliSec = env('GOAL_HOUR') * 60 * 60 * 1000;
        $percent = round($milliseconds / $goalInMilliSec * 100, 2);
        if ($percent > 100) {
            $percent -= 100;
            return "👍You've achieved *$percent%* more than goal👍";
        } else {
            return "You already done $percent%, KEEP GOING 💪";
        }
    }
}
