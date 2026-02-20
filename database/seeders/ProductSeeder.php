<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

/**
 * Class ProductSeeder
 *
 * Seeds the products table with random product data.
 * - Generates realistic product names
 * - Ensures proper decimal formatting for price (15,2)
 * - Random stock quantities
 * - Assigns entry_by and optional approved_by
 *
 * @package Database\Seeders
 */
class ProductSeeder extends Seeder
{
    /**
     * Seed the products table.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Adjust these IDs based on your existing users table
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            return; // Prevent foreign key issues if no users exist
        }

        $products = [];

        for ($i = 0; $i < 80; $i++) {

            $entryBy = $faker->randomElement($userIds);
            $approvedBy = $faker->boolean(60)
                ? $faker->randomElement($userIds)
                : null;

            $products[] = [
                'name'           => ucfirst($faker->words(3, true)),
                'description'    => $faker->boolean(70) ? $faker->paragraph() : null,
                'price'          => number_format($faker->randomFloat(2, 10, 5000), 2, '.', ''),
                'stock_quantity' => $faker->numberBetween(0, 500),
                'entry_by'       => $entryBy,
                'approved_by'    => $approvedBy,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}
