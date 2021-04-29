<?php

namespace Illuminate\Tests\Unit;

use App\Services\TogglService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\DailySummary;

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
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testFetch()
    {
        $mock = \Mockery::mock('overload:App\Services\SummaryService');
        $mock->shouldReceive('getProjectIdByName')->once()->andReturn(157099012);
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
        $result['items']->transform( fn($entry) => 'aaa' );

        $response = (new TogglService)->save($result);
        $this->assertStringContainsString("Update failed", $response);
    }
}