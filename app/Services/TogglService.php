<?php

namespace App\Services;

use App\Models\DailySummaries;
use Exception;
use Noobj\Toggl\ReportsClient;
use Noobj\Toggl\TogglClient;
use App\Contracts\ThirdPartyFetchingService;
use App\Services\SummaryService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TogglService implements ThirdPartyFetchingService
{
    /**
     * Fetch the Toggl daily summary
     *
     * @param string $date
     * @return mixed
     * @throws Exception
     */
    public function fetch(string $startDate, string $endDate, string $projectName = 'meditation') : array
    {
        $togglToken = env('TOGGL_TOKEN');

        // Get the toggl client with your toggl api key
        $toggl_client = TogglClient::factory(array('api_key' => $togglToken, 'apiVersion' => 'v8'));

        try {
            $projectId = SummaryService::getProjectIdByName($projectName);

            $workspaces = $toggl_client->getWorkspaces(array());

            $wid = $workspaces[0]['id']; // Retrieve this with the get-workspaces.php file and update
            $userAgent = "Toggl PHP Client";
        } catch (\Throwable $e) {
            throw $e;
        }

        // Get the toggl client with your toggl api key
        $toggl_reports = ReportsClient::factory([
            'api_key'    => $togglToken,
            'apiVersion' => 'v2',
            'debug'      => false,
        ]);

        $page = 1;
        $details = [];

        try {
            do {
                $response = $toggl_reports->details([
                    "user_agent"   => $userAgent,
                    "workspace_id" => $wid,
                    "project_ids" => $projectId,
                    "since" => $startDate,
                    "until" => $endDate,
                    "page" => $page++
                ]);

                $details = array_merge($details, $response['data']);
            } while(sizeof($details) != $response['total_count']);
        } catch (\Exception $e) {
            throw $e;
        }


        $result['items'] = $this->sumUpSummaryDaily($details);
        $result['projectId'] = $projectId;

        return $result;
    }

    /**
     * Sum up the duration of each day
     *
     * @param array $details
     * @return array
     */
    private function sumUpSummaryDaily(array $details): Collection
    {
        return collect($details)->groupBy(function ($detail) {
            return $this->fetchDateString($detail['start']);
        })->map(function($entries) {
            return $entries->sum('dur');
        });
    }

    /**
     * Extract the date string
     *
     * @param string $dateString
     * @return string
     */
    private function fetchDateString(string $dateString): string
    {
        return Carbon::create($dateString)->toDateString();
    }

    /**
     * @param array $summary
     * @param string $date
     */
    public function save(array $summaries)
    {
        $count = 0;
        $prjId = $summaries['projectId'];
        try {
            DB::beginTransaction();
            $summaries['items']->map(function ($entry, $key) use ($prjId, &$count) {
                $dataSet = [
                    'project_id' => $prjId,
                    'date' => $key,
                    'duration' => $entry
                ];

                $count++;
                DailySummaries::updateOrCreate(['project_id' => $prjId, 'date' => $key], $dataSet);
            });

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return "Update failed $e->getMessage()";
        }
        return "$count days have been updated;";
    }
}
