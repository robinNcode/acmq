<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $totalRecords = 20000;
        $chunkSize = 1000;

        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            $customers = [];

            for ($j = 0; $j < $chunkSize && ($i + $j) < $totalRecords; $j++) {
                $index = $i + $j + 1;

                $customers[] = [
                    'name'       => $faker->name(),
                    'email'      => "customer{$index}@example.com", // deterministic unique email
                    'phone'      => $faker->boolean(80) ? $faker->phoneNumber() : null,
                    'address'    => $faker->boolean(70) ? $faker->address() : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('customers')->insert($customers);
        }
    }
}
