<?php


namespace App\Services;


use App\Models\DailySummaries;
use Exception;
use Noobj\Toggl\ReportsClient;
use Noobj\Toggl\TogglClient;
use App\Contracts\ThirdPartyFetchingService;
use App\Services\SummaryService;

class TogglService implements ThirdPartyFetchingService
{
    /**
     * Fetch the Toggl daily summary
     *
     * @param string $date
     * @return mixed
     * @throws Exception
     */
    public function fetchDailySummaryFromThirdParty(string $date, string $projectName = '') : array
    {
        $togglToken = env('TOGGL_TOKEN');

        // Get the toggl client with your toggl api key
        $toggl_client = TogglClient::factory(array('api_key' => $togglToken, 'apiVersion' => 'v8'));

        $projectId = SummaryService::getProjectIdByName($projectName);

        $workspaces = $toggl_client->getWorkspaces(array());

        $wid = $workspaces[0]['id']; // Retrieve this with the get-workspaces.php file and update
        $userAgent = "Toggl PHP Client";

        // Get the toggl client with your toggl api key
        $toggl_reports = ReportsClient::factory([
            'api_key'    => $togglToken,
            'apiVersion' => 'v2',
            'debug'      => false,
        ]);

        // Summary of single day.
        $response = $toggl_reports->summary([
            "user_agent"   => $userAgent,
            "workspace_id" => $wid,
            "project_ids" => $projectId,
            "since" => $date,
            "until" => $date
        ]);

        return $response['data'];
    }

    /**
     * @param array $summary
     * @param string $date
     */
    public function updateDailySummary(array $summary, string $date)
    {
        $returnStr = 'Projects [';
        foreach ($summary as $key => $entry) {
            $prjId = $entry['id'];
            $duration = $entry['time'];
            $dataSet = [
                'project_id' => $prjId,
                'date' => $date,
                'duration' => $duration
            ];

            DailySummaries::updateOrCreate(['project_id' => $prjId, 'date' => $date], $dataSet);
            $returnStr .= ' ' . $entry['title']['project'];
        }

        $returnStr .= ' ] has been updated.';

        return $returnStr;
    }
}
