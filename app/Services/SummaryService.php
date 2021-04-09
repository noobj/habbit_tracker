<?php


namespace App\Services;


use App\Models\DailySummaries;
use App\Models\Projects;
use Exception;

class SummaryService
{
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
    private function getProjectIdByName(string $name): int
    {
        $project = Projects::where('name', $name)->first();

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
    public function getRangeSummary(string $project, string $startDate, string $endDate): array
    {
        $projectId = $this->getProjectIdByName($project);

        return DailySummaries::whereBetween('date', [$startDate, $endDate])
            ->where('project_id', $projectId)
            ->select('id', 'date', 'duration')
            ->get()->toArray();
    }

    private function convertToHoursMins(int $minuteRaw): string
    {
        if ($minuteRaw < 1) {
            return '1m';
        }

        $hours = floor($minuteRaw / 60);
        $minutes = ($minuteRaw % 60);

        if ($hours == 0) return sprintf('%dm', $minutes);

        return sprintf('%dh:%dm', $hours, $minutes);
    }

    public function processTheRawSummaries(array $rawData): array
    {
        $result = array_map(function($entry) {
            $durationInMinute = $entry['duration'] / 1000 / 60;
            $levelIndex = $durationInMinute / 30;

            $level = $levelIndex > 5 ? 4 : $this->durationLevelMap[$levelIndex];
            $entry['level'] = $level;
            $entry['duration'] = $this->convertToHoursMins($durationInMinute);
            return $entry;
        }, $rawData);

        return $result;
    }
}
