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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('supplier_id');
            $table->json('product_info');
            $table->dateTime('purchase_date');
            $table->decimal('total_price', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->decimal('due', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
