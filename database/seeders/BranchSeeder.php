<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Seed 50 Bangladeshi branches with structured branch codes.
     *
     * Branch Code Format:
     * BR-XXX
     *
     * @return void
     */
    public function run(): void
    {
        $now = now();

        $locations = [
            ['Gulshan', 'Dhaka', '1212'],
            ['Dhanmondi', 'Dhaka', '1209'],
            ['Mirpur', 'Dhaka', '1216'],
            ['Uttara', 'Dhaka', '1230'],
            ['Motijheel', 'Dhaka', '1000'],
            ['Panchlaish', 'Chattogram', '4203'],
            ['Kotwali', 'Chattogram', '4000'],
            ['Halishahar', 'Chattogram', '4216'],
            ['Agrabad', 'Chattogram', '4100'],
            ['Boalia', 'Rajshahi', '6000'],
            ['Motihar', 'Rajshahi', '6204'],
            ['Sonadanga', 'Khulna', '9100'],
            ['Khalishpur', 'Khulna', '9000'],
            ['Kotwali', 'Sylhet', '3100'],
            ['Beanibazar', 'Sylhet', '3170'],
            ['Sadar', 'Barishal', '8200'],
            ['Bandar', 'Narayanganj', '1400'],
            ['Sadar', 'Cumilla', '3500'],
            ['Sadar', 'Mymensingh', '2200'],
            ['Sadar', 'Rangpur', '5400'],
            ['Sadar', 'Bogura', '5800'],
            ['Sadar', 'Dinajpur', '5200'],
            ['Sadar', 'Jessore', '7400'],
            ['Sadar', 'Noakhali', '3800'],
            ['Sadar', 'Feni', '3900'],
            ['Sadar', 'Pabna', '6600'],
            ['Sadar', 'Kushtia', '7000'],
            ['Sadar', 'Tangail', '1900'],
            ['Sadar', 'Gazipur', '1700'],
            ['Sadar', 'Manikganj', '1800'],
            ['Sadar', 'Narsingdi', '1600'],
            ['Sadar', 'Brahmanbaria', '3400'],
            ['Sadar', 'Habiganj', '3300'],
            ['Sadar', 'Jhenaidah', '7300'],
            ['Sadar', 'Satkhira', '9400'],
            ['Sadar', 'Chapainawabganj', '6300'],
            ['Sadar', 'Gaibandha', '5700'],
            ['Sadar', 'Lalmonirhat', '5500'],
            ['Sadar', 'Nilphamari', '5300'],
            ['Sadar', 'Sherpur', '2100'],
            ['Sadar', 'Jamalpur', '2000'],
            ['Sadar', 'Bhola', '8300'],
            ['Sadar', 'Bagerhat', '9300'],
            ['Sadar', 'Madaripur', '7900'],
            ['Sadar', 'Gopalganj', '8100'],
            ['Sadar', 'Faridpur', '7800'],
            ['Sadar', 'Netrokona', '2400'],
            ['Sadar', 'Sunamganj', '3000'],
            ['Sadar', 'Thakurgaon', '5100'],
            ['Sadar', 'Panchagarh', '5000'],
        ];

        $branches = [];

        foreach ($locations as $index => $location) {

            $branches[] = [
                'branch_code' => 'BR-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'name' => 'Branch - ' . $location[0] . ', ' . $location[1],
                'thana' => $location[0],
                'district' => $location[1],
                'postal_code' => $location[2],
                'address' => $location[0] . ' Main Road, ' . $location[1],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('branches')->insert($branches);
    }
}
