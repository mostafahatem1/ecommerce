<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Schema::disableForeignKeyConstraints();

        Product::all()->each(function ($product) {
            $product->reviews()->createMany(
                ProductReview::factory()->count(5)->make()->toArray()
            );
        });

//        Schema::enableForeignKeyConstraints();
    }
}
