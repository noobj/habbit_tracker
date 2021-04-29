<?php

namespace Database\Factories;

use App\Models\DailySummary;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailySummaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DailySummary::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->dateTimeBetween('2021-04-07', '2021-04-27'),
            'project_id' => 157099012,
            'duration' => 100000
        ];
    }
}
