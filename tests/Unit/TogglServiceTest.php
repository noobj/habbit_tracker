<?php

namespace Illuminate\Tests\Unit;

use App\Services\TogglService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\DailySummary;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

class TogglServiceTest extends TestCase
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


        $mockReportResponse = [
            'data' => [
                [
                "start" => "2021-04-21T23:00:00+08:00",
                "end" => "2021-04-21T23:20:00+08:00",
                "dur" => 1200000,
                "project" => "Meditation"
            ], [
                "start" => "2021-04-20T23:30:18+08:00",
                "end" => "2021-04-21T23:20:00+08:00",
                "dur" => 3300000,
                "project" => "Meditation"
            ]], 'total_count' => 2];

        $mock = \Mockery::mock('overload:App\Services\SummaryService');
        $mock->shouldReceive('getProjectIdByName')->once()->andReturn(157099012);
        $guzzleClientMock = \Mockery::mock(GuzzleClient::class);
        $guzzleClientMock->shouldReceive('details')->once()->andReturn($mockReportResponse);
        $reportClientMock = \Mockery::mock('overload:Noobj\Toggl\ReportsClient');
        $reportClientMock->shouldReceive('factory')->once()->andReturn($guzzleClientMock);

        $guzzleClient4TogglMock = \Mockery::mock(GuzzleClient::class);
        $guzzleClient4TogglMock->shouldReceive('getWorkspaces')->once()->andReturn([['id' => 123]]);
        $togglClientMock = \Mockery::mock('overload:Noobj\Toggl\TogglClient');
        $togglClientMock->shouldReceive('factory')->once()->andReturn($guzzleClient4TogglMock);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testFetch()
    {

        $result = (new TogglService)->fetch('2021-04-20', '2021-04-22');

        $this->assertEquals(4500000, $result['items']->sum());
        $this->assertEquals(157099012, $result['projectId']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSave()
    {
        $mock = \Mockery::mock('overload:App\Services\SummaryService');
        $mock->shouldReceive('getProjectIdByName')->once()->andReturn(157099012);

        $result = (new TogglService)->fetch('2021-04-20', '2021-04-22');
        $response = (new TogglService)->save($result);

        $this->assertEquals(DailySummary::all()->pluck('duration'), $result['items']->flatten());;
        $this->assertEquals("2 days have been updated;", $response);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSaveFailed()
    {
        $mock = \Mockery::mock('overload:App\Services\SummaryService');
        $mock->shouldReceive('getProjectIdByName')->once()->andReturn(157099012);
        $result = (new TogglService)->fetch('2021-04-20', '2021-04-22');
        $result['items']->transform( fn($entry) => null );

        $response = (new TogglService)->save($result);
        $this->assertStringContainsString("Update failed", $response);
    }
}