<?php


namespace App\Services;


use App\Models\DailySummaries;
use Carbon\Carbon;
use Exception;
use Noobj\Toggl\ReportsClient;
use Noobj\Toggl\TogglClient;
use App\Contracts\ThirdPartyFetchingService;

class TogglService implements ThirdPartyFetchingService
{
    /**
     * Fetch the Toggl daily summary
     *
     * @param string $date
     * @return mixed
     * @throws Exception
     */
    public function fetchDailySummaryFromThirdParty(string $date) : array
    {
        $toggl_token = env('TOGGL_TOKEN');

        // Get the toggl client with your toggl api key
        $toggl_client = TogglClient::factory(array('api_key' => $toggl_token, 'apiVersion' => 'v8'));

        $workspaces = $toggl_client->getWorkspaces(array());

        $wid = $workspaces[0]['id']; // Retrieve this with the get-workspaces.php file and update
        $user_agent = "Toggl PHP Client";

        // Get the toggl client with your toggl api key
        $toggl_reports = ReportsClient::factory([
            'api_key'    => $toggl_token,
            'apiVersion' => 'v2',
            'debug'      => false,
        ]);

        // Summary of single day.
        $response = $toggl_reports->summary([
            "user_agent"   => $user_agent,
            "workspace_id" => $wid,
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
