<?php

namespace Database\Factories;

use App\Enums\Period;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => ucfirst($this->faker->words(mt_rand(1, 2), true)),
            'description' => $this->faker->sentences(mt_rand(1, 3), true),
            'channel_id' => Channel::inRandomOrder()->first(),
        ];
    }

    public function last(Period $period)
    {
        return $this->state(function () use ($period) {
            $createdAt = $this->faker->dateTimeBetween("-1 $period->value");

            return [
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        });
    }
}
