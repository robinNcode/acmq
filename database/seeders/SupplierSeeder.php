<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

/**
 * Class SupplierSeeder
 *
 * Seeds the Suppliers table with random SupplierController data.
 * - Generates unique emails
 * - Random nullable phone and address
 * - Skips admin email conflict
 *
 * @package Database\Seeders
 */
class SupplierSeeder extends Seeder
{
    /**
     * Seed the Suppliers table with random data.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();
        $suppliers = [];

        for ($i = 0; $i < 1000; $i++) {
            $suppliers[] = [
                'name'       => $faker->name(),
                'email'      => $faker->unique()->safeEmail(),
                'phone'      => $faker->boolean(80) ? $faker->phoneNumber() : null,
                'address'    => $faker->boolean(70) ? $faker->address() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('suppliers')->insert($suppliers);
    }
}
