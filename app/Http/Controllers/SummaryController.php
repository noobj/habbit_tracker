<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SummaryService;

class SummaryController extends Controller
{
    protected $summaryService;

    public function __construct(SummaryService $summaryService)
    {
        $this->summaryService = $summaryService;
    }

    public function getProjectSummary(Request $request, string $project = 'Productive') {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->getMessageBag()->first());
        }

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        var_dump($this->summaryService->getProjectIdByName($project));

        return $this->summaryService->getRangeSummary($project, $startDate, $endDate);
    }
}
