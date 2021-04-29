<?php

namespace Illuminate\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\DailySummaries;
use App\Services\SummaryService;

class SummaryServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');

    }

    public function testGetProjectIdByName()
    {
        $prjId = SummaryService::getProjectIdByName('meditation');

        $this->assertEquals(157099012, $prjId);
    }

    public function testGetProjectIdByNameFailed()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Project not found.');

        $prjId = SummaryService::getProjectIdByName('medation');
    }

    public function testGetRangeDailySummary()
    {
        DailySummaries::factory(7)->create();
        $summaries = (new SummaryService)->getRangeDailySummary('meditation', '2021-04-07', '2021-04-27');

        $this->assertEquals(7, sizeof($summaries));
        $this->assertInstanceOf(DailySummaries::class, $summaries->first());
    }
}