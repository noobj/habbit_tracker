<?php


namespace App\Services;


use App\Models\DailySummaries;
use App\Models\Projects;
use Carbon\Carbon;
use Exception;

class SummaryService
{
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

        if ($project) {
            return $project->id;
        }

        return null;
    }

    /**
     * Get the daily summary between given date range
     *
     * @param string $project
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getRangeSummary(string $project, string $startDate, string $endDate): array
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
    private function convertToHoursMins(int $minuteRaw): string
    {
        if ($minuteRaw < 1) {
            return '1m';
        }

        $hours = floor($minuteRaw / 60);
        $minutes = ($minuteRaw % 60);

        if ($hours == 0) return sprintf('%dm', $minutes);

        return sprintf('%dh%dm', $hours, $minutes);
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
            $durationInMinute = $entry['duration'] / 1000 / 60;

            $levelIndex = $durationInMinute / 30;
            $level = $levelIndex > 5 ? 4 : $this->durationLevelMap[$levelIndex];
            $entry['level'] = $level;
            $entry['duration'] = $this->convertToHoursMins($durationInMinute);

            $entry['date'] = Carbon::createFromDate($entry['date'])->toFormattedDateString();
            $entry['timestamp'] = intval(Carbon::createFromDate($entry['date'])->getPreciseTimestamp(3));

            return $entry;
        }, $rawData);

        return $result;
    }
}
