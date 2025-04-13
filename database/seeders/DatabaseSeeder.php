<?php

namespace Database\Seeders;

use Database\Seeders\EntrustSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(WorldSeeder::class);
        $this->call(WorldStatusSeeder::class);
        $this->call(EntrustSeeder::class);
        $this->call(UserAddressSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(ProductsTagsSeeder::class);
        $this->call(ProductsImagesSeeder::class);
        $this->call(ProductCouponSeeder::class);
        $this->call(ProductReviewSeeder::class);
        $this->call(ShippingCompanySeeder::class);




    }
}
