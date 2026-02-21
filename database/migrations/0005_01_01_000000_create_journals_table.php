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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('reference_type')->nullable(); // sale, purchase, expense
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->date('date');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->index(['branch_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
