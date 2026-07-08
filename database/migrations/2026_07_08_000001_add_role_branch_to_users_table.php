<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('email');
            $table->enum('role', ['admin', 'branch_user'])->default('branch_user')->after('branch_id');
            $table->unsignedBigInteger('employee_id')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('employee_id');

            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
        });

        // Set existing admin user to admin role
        DB::table('users')->where('email', 'admin@acmq.com')->update(['role' => 'admin']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'role', 'employee_id', 'is_active']);
        });
    }
};
