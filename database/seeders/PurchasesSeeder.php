<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PurchasesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $branches = range(24, 200);
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        $salesSeeder = new SalesSeeder();
        $products = $salesSeeder->randomProductsList($faker->numberBetween(1, 10));

        foreach ($branches as $branchId) {
            $purchaseData = [];
            $purchaseCount = rand(500, 600);

            for ($i = 0; $i < $purchaseCount; $i++) {
                $purchaseData[] = [
                    'code' => 'P' . time() . rand(1000, 9999) . $i,
                    'branch_id' => $branchId,
                    'supplier_id' => $faker->numberBetween(1, 50000),
                    'product_info' => json_encode($products),
                    'purchase_date' => $faker->dateTimeBetween($startDate, $endDate),
                    'total_price' => $faker->numberBetween(100, 10000),
                    'discount' => $faker->numberBetween(0, 1000),
                    'paid' => $faker->numberBetween(50, 10000),
                    'due' => $faker->numberBetween(0, 5000),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => NULL,
                ];
            }

            DB::table('purchases')->insert($purchaseData);
        }
    }
}

