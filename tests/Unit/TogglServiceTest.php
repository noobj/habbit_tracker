<?php

namespace Illuminate\Tests\Unit;

use App\Services\TogglService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\DailySummaries;

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

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFetch()
    {
        $service = new TogglService();
        $mock = \Mockery::mock('overload:App\Services\SummaryService');
        $mock->shouldReceive('getProjectIdByName')->once()->andReturn(157099012);
        $result = $service->fetch('2021-04-20', '2021-04-22');

        $this->assertEquals(4500000, $result['items']->sum());
        $this->assertEquals(157099012, $result['projectId']);
    }

    public function testSave()
    {
        $service = new TogglService();
        $mock = \Mockery::mock('overload:App\Services\SummaryService');
        $mock->shouldReceive('getProjectIdByName')->once()->andReturn(157099012);
        $result = $service->fetch('2021-04-20', '2021-04-22');

        $response = $service->save($result);
        $this->assertEquals(DailySummaries::all()->pluck('duration'), $result['items']->flatten());;
        $this->assertEquals("2 days have been updated;", $response);
    }
}