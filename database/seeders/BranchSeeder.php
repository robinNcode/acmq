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
            ['Bakerganj', 'Barishal', '8200'],
            ['Bandar', 'Narayanganj', '1400'],
            ['Chauddagram', 'Cumilla', '3500'],
            ['Trishal', 'Mymensingh', '2200'],
            ['Gangachara', 'Rangpur', '5400'],
            ['Shibganj', 'Bogura', '5800'],
            ['Parbatipur', 'Dinajpur', '5200'],
            ['Abhaynagar', 'Jessore', '7400'],
            ['Senbagh', 'Noakhali', '3800'],
            ['Chhagalnaiya', 'Feni', '3900'],
            ['Santhia', 'Pabna', '6600'],
            ['Khoksa', 'Kushtia', '7000'],
            ['Bhuapur', 'Tangail', '1900'],
            ['Sreepur', 'Gazipur', '1700'],
            ['Sadar', 'Manikganj', '1800'],
            ['Belabo', 'Narsingdi', '1600'],
            ['Sarail', 'Brahmanbaria', '3400'],
            ['Baniachong', 'Habiganj', '3300'],
            ['Kaliganj', 'Jhenaidah', '7300'],
            ['Debhata', 'Satkhira', '9400'],
            ['Shibganj', 'Chapainawabganj', '6300'],
            ['Palashbari', 'Gaibandha', '5700'],
            ['Aditmari', 'Lalmonirhat', '5500'],
            ['Saidpur', 'Nilphamari', '5300'],
            ['Nakla', 'Sherpur', '2100'],
            ['Islampur', 'Jamalpur', '2000'],
            ['Charfesson', 'Bhola', '8300'],
            ['Fakirhat', 'Bagerhat', '9300'],
            ['Kalkini', 'Madaripur', '7900'],
            ['Tungipara', 'Gopalganj', '8100'],
            ['Boalmari', 'Faridpur', '7800'],
            ['Khaliajuri', 'Netrokona', '2400'],
            ['Jagannathpur', 'Sunamganj', '3000'],
            ['Pirganj', 'Thakurgaon', '5100'],
            ['Atwari', 'Panchagarh', '5000'],
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
