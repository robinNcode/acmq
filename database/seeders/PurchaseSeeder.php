<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $branches = range(1, 50); // BranchSeeder creates 50 branches
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate = Carbon::now()->subMonth()->endOfMonth();

        $supplierIds = DB::table('suppliers')->pluck('id')->toArray();

        // Fetch all products at once
        $allProducts = DB::table('products')->select('id', 'name', 'purchase_price')->get()->toArray();
        if (empty($allProducts) || empty($supplierIds)) {
            return; // Prevent seeding if no data
        }

        // Fetch Accounts for Double Entry
        $cashAccId = DB::table('accounts')->where('code', '1100')->value('id');
        $payableAccId = DB::table('accounts')->where('code', '2100')->value('id');
        $purchaseExpenseAccId = DB::table('accounts')->where('code', '5100')->value('id');

        foreach ($branches as $branchId) {
            $purchaseCount = rand(10, 15);
            $journalEntriesData = [];

            for ($i = 1; $i <= $purchaseCount; $i++) {
                $products = $this->randomProductsList($faker->numberBetween(1, 10), $allProducts, $faker);

                // Compute purchase-level totals based on product net prices
                $totalPrice = array_sum(array_column($products, 'net_price'));
                $paid       = array_sum(array_column($products, 'paid'));
                $due        = array_sum(array_column($products, 'due'));

                $purchaseData = [
                    'code'          => sprintf('PUR%03d%06d', $branchId, $i),
                    'branch_id'     => $branchId,
                    'supplier_id'   => $faker->randomElement($supplierIds),
                    'product_info'  => json_encode($products),
                    'purchase_date' => $faker->dateTimeBetween($startDate, $endDate),
                    'total_price'   => $totalPrice,
                    'discount'      => array_sum(array_column($products, 'discount')),
                    'paid'          => $paid,
                    'due'           => $due,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                    'deleted_at'    => null,
                ];

                $purchaseId = DB::table('purchases')->insertGetId($purchaseData);

                // Create Journal
                $journalId = DB::table('journals')->insertGetId([
                    'branch_id'      => $branchId,
                    'reference_type' => 'purchase',
                    'reference_id'   => $purchaseId,
                    'date'           => $purchaseData['purchase_date'],
                    'description'    => 'Purchase ' . $purchaseData['code'],
                    'created_by'     => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // Journal Entries for Purchase
                // 1. Debit Purchase Expense for net_price
                $journalEntriesData[] = [
                    'journal_id' => $journalId,
                    'account_id' => $purchaseExpenseAccId,
                    'debit'      => $totalPrice,
                    'credit'     => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 2. Credit Cash for paid
                if ($paid > 0) {
                    $journalEntriesData[] = [
                        'journal_id' => $journalId,
                        'account_id' => $cashAccId,
                        'debit'      => 0,
                        'credit'     => $paid,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // 3. Credit Accounts Payable for due
                if ($due > 0) {
                    $journalEntriesData[] = [
                        'journal_id' => $journalId,
                        'account_id' => $payableAccId,
                        'debit'      => 0,
                        'credit'     => $due,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Batch insert journal entries efficiently
            if (!empty($journalEntriesData)) {
                DB::table('journal_entries')->insert($journalEntriesData);
            }
        }
    }

    /**
     * Generate a random products list for purchases.
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
            $price    = $product->purchase_price;
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
