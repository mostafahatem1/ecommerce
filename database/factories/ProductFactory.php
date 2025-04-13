<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2, true),
            'slug' => $this->faker->unique()->slug(2, true),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(5, 200),
            'quantity' => $this->faker->numberBetween(10, 100),
            'product_category_id' => ProductCategory::whereNotNull('parent_id')->pluck('id')->random(),
            'featured' => rand(0, 1),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
