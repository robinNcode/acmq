<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Seed global Chart of Accounts with hierarchical structure.
     *
     * Account Types:
     * - asset
     * - liability
     * - equity
     * - income
     * - expense
     *
     * @return void
     */
    public function run(): void
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | ROOT ACCOUNTS
        |--------------------------------------------------------------------------
        */

        $assetId = DB::table('accounts')->insertGetId([
            'name' => 'Assets',
            'code' => '1000',
            'type' => 'asset',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $liabilityId = DB::table('accounts')->insertGetId([
            'name' => 'Liabilities',
            'code' => '2000',
            'type' => 'liability',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $equityId = DB::table('accounts')->insertGetId([
            'name' => 'Equity',
            'code' => '3000',
            'type' => 'equity',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $incomeId = DB::table('accounts')->insertGetId([
            'name' => 'Income',
            'code' => '4000',
            'type' => 'income',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $expenseId = DB::table('accounts')->insertGetId([
            'name' => 'Expenses',
            'code' => '5000',
            'type' => 'expense',
            'parent_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ASSET ACCOUNTS
        |--------------------------------------------------------------------------
        */

        DB::table('accounts')->insert([
            [
                'name' => 'Cash',
                'code' => '1100',
                'type' => 'asset',
                'parent_id' => $assetId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bank',
                'code' => '1200',
                'type' => 'asset',
                'parent_id' => $assetId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Accounts Receivable',
                'code' => '1300',
                'type' => 'asset',
                'parent_id' => $assetId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Inventory',
                'code' => '1400',
                'type' => 'asset',
                'parent_id' => $assetId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LIABILITY ACCOUNTS
        |--------------------------------------------------------------------------
        */

        DB::table('accounts')->insert([
            [
                'name' => 'Accounts Payable',
                'code' => '2100',
                'type' => 'liability',
                'parent_id' => $liabilityId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | EQUITY ACCOUNTS
        |--------------------------------------------------------------------------
        */

        DB::table('accounts')->insert([
            [
                'name' => 'Owner Capital',
                'code' => '3100',
                'type' => 'equity',
                'parent_id' => $equityId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Retained Earnings',
                'code' => '3200',
                'type' => 'equity',
                'parent_id' => $equityId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | INCOME ACCOUNTS
        |--------------------------------------------------------------------------
        */

        DB::table('accounts')->insert([
            [
                'name' => 'Sales Revenue',
                'code' => '4100',
                'type' => 'income',
                'parent_id' => $incomeId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | EXPENSE ACCOUNTS
        |--------------------------------------------------------------------------
        */

        DB::table('accounts')->insert([
            [
                'name' => 'Purchase Expense',
                'code' => '5100',
                'type' => 'expense',
                'parent_id' => $expenseId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Operating Expense',
                'code' => '5200',
                'type' => 'expense',
                'parent_id' => $expenseId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
