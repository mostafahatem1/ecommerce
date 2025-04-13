<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // تحديد عدد المنتجات المطلوب إنشاؤها
        $totalProducts = 500;
        $chunkSize = 100;

        // إنشاء 1000 منتج باستخدام ProductFactory
        Product::factory()->count($totalProducts)->create()->chunk($chunkSize, function ($products) {
            // إدخال المنتجات دفعة دفعة (في حالة الحاجة إلى إضافة بيانات إضافية مثل الصور)
            Product::insert($products->toArray());
        });


    }
}
