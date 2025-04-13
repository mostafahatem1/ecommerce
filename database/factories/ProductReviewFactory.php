<?php

namespace Database\Factories;

use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory()->create()->id,
            'name' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'status' => rand(0, 1),
            'rating' => rand(1, 5),
        ];
    }
}
