<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Str;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $branches = range(1, 50); // BranchSeeder creates 50 branches
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        $customerIds = DB::table('customers')->pluck('id')->toArray();

        // Fetch all products at once
        $allProducts = DB::table('products')->select('id', 'name', 'selling_price')->get()->toArray();
        if (empty($allProducts) || empty($customerIds)) {
            return; // Prevent seeding if no data
        }

        // Fetch Accounts for Double Entry
        $cashAccId = DB::table('accounts')->where('code', '1100')->value('id');
        $receivableAccId = DB::table('accounts')->where('code', '1300')->value('id');
        $salesRevenueAccId = DB::table('accounts')->where('code', '4100')->value('id');

        foreach ($branches as $branchId) {
            $salesCount = rand(10, 15);
            $journalEntriesData = [];

            for ($i = 0; $i < $salesCount; $i++) {
                $products = $this->randomProductsList($faker->numberBetween(1, 10), $allProducts, $faker);

                // Compute sale-level totals based on product net prices
                $totalPrice = array_sum(array_column($products, 'net_price'));
                $paid       = array_sum(array_column($products, 'paid'));
                $due        = array_sum(array_column($products, 'due'));

                $salesData = [
                    'code'         => sprintf('SELL%03d%06d', $branchId, $i),
                    'branch_id'    => $branchId,
                    'customer_id'  => $faker->randomElement($customerIds),
                    'product_info' => json_encode($products),
                    'selling_date' => $faker->dateTimeBetween($startDate, $endDate),
                    'total_price'  => $totalPrice,
                    'discount'     => array_sum(array_column($products, 'discount')),
                    'paid'         => $paid,
                    'due'          => $due,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                    'deleted_at'   => null,
                ];

                $saleId = DB::table('sales')->insertGetId($salesData);

                // Create Journal
                $journalId = DB::table('journals')->insertGetId([
                    'branch_id'      => $branchId,
                    'reference_type' => 'sale',
                    'reference_id'   => $saleId,
                    'date'           => $salesData['selling_date'],
                    'description'    => 'Sale ' . $salesData['code'],
                    'created_by'     => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // Journal Entries for Sale
                // 1. Credit Sales Revenue for net_price
                $journalEntriesData[] = [
                    'journal_id' => $journalId,
                    'account_id' => $salesRevenueAccId,
                    'debit'      => 0,
                    'credit'     => $totalPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 2. Debit Cash for paid
                if ($paid > 0) {
                    $journalEntriesData[] = [
                        'journal_id' => $journalId,
                        'account_id' => $cashAccId,
                        'debit'      => $paid,
                        'credit'     => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // 3. Debit Accounts Receivable for due
                if ($due > 0) {
                    $journalEntriesData[] = [
                        'journal_id' => $journalId,
                        'account_id' => $receivableAccId,
                        'debit'      => $due,
                        'credit'     => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($journalEntriesData)) {
                DB::table('journal_entries')->insert($journalEntriesData);
            }
        }
    }

    /**
     * Generate a random products list for sales.
     *
     * @param int $total_products
     * @param array $allProducts
     * @param Generator $faker
     * @return array
     */
    public function randomProductsList(int $total_products, array $allProducts, $faker): array
    {
        $products = [];
        $pickedProducts = $faker->randomElements($allProducts, min($total_products, count($allProducts)));

        foreach ($pickedProducts as $product) {
            $quantity = $faker->numberBetween(1, 50);
            $price    = $product->selling_price;
            $total    = $price * $quantity;
            $discount = $faker->numberBetween(0, $total);
            $netPrice = $total - $discount;
            $paid     = $faker->numberBetween(0, $netPrice);
            $due      = $netPrice - $paid;

            $products[] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'quantity'   => $quantity,
                'price'      => $price,
                'total'      => $total,
                'discount'   => $discount,
                'net_price'  => $netPrice,
                'paid'       => $paid,
                'due'        => $due,
            ];
        }

        return $products;
    }
}
