<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SummaryService;

class SummaryController extends Controller
{
    protected $summaryService;

    /**
     * @param SummaryService $summaryService
     */
    public function __construct(SummaryService $summaryService)
    {
        $this->summaryService = $summaryService;
    }

    /**
     * Get specific project daily summary by given date range
     *
     * @param Request $request
     * @param string $project
     * @return void
     */
    public function getProjectSummary(Request $request, string $project = 'Meditation')
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->getMessageBag()->first());
        }

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $rawSummaryData = $this->summaryService->getRangeDailySummary($project, $startDate, $endDate);
        return $this->summaryService->processTheRawSummaries($rawSummaryData);
    }
}
