<?php


namespace App\Services;


use App\Models\DailySummaries;
use App\Models\Projects;
use Exception;

class SummaryService
{
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
}
