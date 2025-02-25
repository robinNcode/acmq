<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SalesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $branches = range(1, 8000);
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        foreach ($branches as $branchId) {
            $salesData = [];
            $salesCount = rand(500, 600);

            for ($i = 0; $i < $salesCount; $i++) {
                $salesData[] = [
                    'code' => strtoupper($faker->bothify('S#######')),
                    'branch_id' => $branchId,
                    'customer_id' => $faker->numberBetween(1, 50000),
                    'product_info' => json_encode($this->randomProductsList($faker->numberBetween(1, 10))),
                    'selling_date' => $faker->dateTimeBetween($startDate, $endDate),
                    'total_price' => $faker->randomFloat(2, 100, 10000),
                    'paid' => $faker->randomFloat(2, 50, 10000),
                    'due' => $faker->randomFloat(2, 0, 5000),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ];
            }

            DB::table('sales')->insert($salesData);
        }
    }

    /**
     * Generate random products list for sales ...
     * @param $total_products
     * @return array
     */
    public function randomProductsList($total_products): array
    {
        $products = [];
        $faker = Faker::create();
        for ($i = 0; $i < $total_products; $i++) {
            $products[] = [
                'product_id' => $faker->numberBetween(1, 1000),
                'name' => $faker->sentence,
                'quantity' => $faker->numberBetween(1, 10),
                'price' => $faker->randomFloat(2, 100, 10000),
            ];
        }
        return $products;
    }
}

