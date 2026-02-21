<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the branches table with relevant columns for Bangladeshi branches.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_code', 10)->unique()->comment('Unique branch code, e.g., BR-001');
            $table->string('name')->comment('Branch full name including thana and district');
            $table->string('thana')->comment('Local thana or area name');
            $table->string('district')->comment('District name');
            $table->string('postal_code', 10)->nullable()->comment('Postal code for branch area');
            $table->text('address')->nullable()->comment('Full address of the branch');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the branches table.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
