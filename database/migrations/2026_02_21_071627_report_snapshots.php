<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('report_type'); // balance_sheet, trial_balance
            $table->string('period_type'); // day, month
            $table->date('period_date');
            $table->json('data');
            $table->string('status')->default('completed');
            $table->timestamps();

            //$table->unique(['branch_id','report_type','period_type','period_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_snapshots');
    }
};
