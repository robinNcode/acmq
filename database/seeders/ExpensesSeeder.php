<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $branches = range(1, 8000);
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfMonth = Carbon::now()->subMonth()->endOfMonth();

        foreach ($branches as $branch) {
            $expenses = [];
            $totalExpenses = $faker->numberBetween(1, 10);
            for ($i = 0; $i < $totalExpenses; $i++) {
                $expenses[] = [
                    'branch_id' => $branch,
                    'amount' => $faker->randomFloat(2, 300, 1000000),
                    'particulars' => $faker->sentence,
                    'date' => $faker->dateTimeBetween($startOfMonth, $endOfMonth),
                    'entry_by' => $faker->numberBetween(1, 1000),
                    'approved_by' => $faker->numberBetween(1, 1000),
                    'created_at' => $faker->dateTimeBetween($startOfMonth, $endOfMonth),
                    'updated_at' => $faker->dateTimeBetween($startOfMonth, $endOfMonth),
                ];
            }

            DB::table('expenses')->insert($expenses);
        }
    }
}
