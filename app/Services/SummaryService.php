<?php


namespace App\Services;


use App\Models\DailySummaries;
use App\Models\Projects;
use Exception;

class SummaryService
{
    public function getProjectIdByName(string $name): int
    {
        $project = Projects::where('name', $name)->first();

        return $project->id;
    }

    public function getRangeSummary(string $project, string $startDate, string $endDate): array
    {
        $projectId = $this->getProjectIdByName($project);

        return DailySummaries::whereBetween('date', [$startDate, $endDate])
            ->where('project_id', $projectId)
            ->select('id', 'date', 'duration')
            ->get()->toArray();
    }
}