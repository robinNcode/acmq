<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $branches = range(1, 50); // BranchSeeder creates 50 branches
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfMonth = Carbon::now()->subMonth()->endOfMonth();

        // Fetch Accounts for Double Entry
        $cashAccId = DB::table('accounts')->where('code', '1100')->value('id');
        $operatingExpenseAccId = DB::table('accounts')->where('code', '5200')->value('id');

        foreach ($branches as $branch) {
            $totalExpenses = $faker->numberBetween(1, 10);
            $journalEntriesData = [];

            for ($i = 0; $i < $totalExpenses; $i++) {
                $expenseData = [
                    'branch_id' => $branch,
                    'amount' => $faker->numberBetween(300, 1000000),
                    'particulars' => $faker->sentence,
                    'date' => $faker->dateTimeBetween($startOfMonth, $endOfMonth),
                    'entry_by' => $faker->numberBetween(1, 1000),
                    'approved_by' => $faker->numberBetween(1, 1000),
                    'created_at' => $faker->dateTimeBetween($startOfMonth, $endOfMonth),
                    'updated_at' => now(),
                    'deleted_at' => NULL,
                ];

                $expenseId = DB::table('expenses')->insertGetId($expenseData);

                // Create Journal
                $journalId = DB::table('journals')->insertGetId([
                    'branch_id'      => $branch,
                    'reference_type' => 'expense',
                    'reference_id'   => $expenseId,
                    'date'           => $expenseData['date'],
                    'description'    => 'Expense: ' . $expenseData['particulars'],
                    'created_by'     => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // Journal Entries for Expense
                // 1. Debit Operating Expense for amount
                $journalEntriesData[] = [
                    'journal_id' => $journalId,
                    'account_id' => $operatingExpenseAccId,
                    'debit'      => $expenseData['amount'],
                    'credit'     => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // 2. Credit Cash for amount
                $journalEntriesData[] = [
                    'journal_id' => $journalId,
                    'account_id' => $cashAccId,
                    'debit'      => 0,
                    'credit'     => $expenseData['amount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($journalEntriesData)) {
                DB::table('journal_entries')->insert($journalEntriesData);
            }
        }
    }
}
