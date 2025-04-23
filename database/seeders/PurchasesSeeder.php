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
        $branches = range(1, 200);
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        $salesSeeder = new SalesSeeder();
        $products = $salesSeeder->randomProductsList($faker->numberBetween(1, 10));

        foreach ($branches as $branchId) {
            $purchaseData = [];
            $purchaseCount = rand(500, 600);

            for ($i = 0; $i < $purchaseCount; $i++) {
                $purchaseData[] = [
                    'code' => strtoupper($faker->bothify('P#######')),
                    'branch_id' => $branchId,
                    'customer_id' => $faker->numberBetween(1, 50000),
                    'product_ids' => json_encode([$faker->numberBetween(1, 1000), $faker->numberBetween(1, 1000)]),
                    'purchasing_date' => $faker->dateTimeBetween($startDate, $endDate),
                    'total_price' => $faker->numberBetween(100, 10000),
                    'paid' => $faker->numberBetween(50, 10000),
                    'due' => $faker->numberBetween(0, 5000),
                    'created_at' => now(),
                    'updated_at' => NULL,
                    'deleted_at' => NULL,
                ];
            }

            DB::table('purchases')->insert($purchaseData);
        }
    }
}

