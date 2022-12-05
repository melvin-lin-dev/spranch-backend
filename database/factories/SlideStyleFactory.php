<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SlideStyle>
 */
class SlideStyleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'top' => fake()->numberBetween(10, 600),
            'left' => fake()->numberBetween(10, 1000),
            'color' => $this->getRandomColor(),
            'background_color' => $this->getRandomColor()
        ];
    }

    private function getRandomColor()
    {
        $r = fake()->numberBetween(0, 255);
        $g = fake()->numberBetween(0, 255);
        $b = fake()->numberBetween(0, 255);
        return "rgb($r, $g, $b)";
    }
}
