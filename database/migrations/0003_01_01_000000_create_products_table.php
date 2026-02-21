<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Products table for pharmacy-based inventory system.
     * Supports accounting integration and MQ-driven reporting.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            // Identification
            $table->string('product_code')->unique(); // SKU e.g., MED-0001
            $table->string('name');
            $table->string('category')->default('medicine'); // medicine, surgical, etc.
            $table->string('type')->nullable(); // Tablet, Capsule, Syrup, etc.
            $table->string('unit')->nullable(); // pcs, strip, bottle, tube, box
            $table->string('manufacturer')->nullable();

            // Description
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            | purchase_price → inventory valuation
            | selling_price → revenue generation
            */
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Inventory
            |--------------------------------------------------------------------------
            */
            $table->integer('stock_quantity')->default(0);
            $table->decimal('stock_value', 15, 2)->default(0);
            // stock_quantity * purchase_price (can be recalculated)

            /*
            |--------------------------------------------------------------------------
            | Accounting Mapping
            |--------------------------------------------------------------------------
            | Link to chart of accounts for automation
            */
            $table->unsignedBigInteger('inventory_account_id')->nullable();
            $table->unsignedBigInteger('cogs_account_id')->nullable();
            $table->unsignedBigInteger('revenue_account_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Prescription and Status
            |--------------------------------------------------------------------------
            */
            $table->boolean('is_prescription_required')->default(false);
            $table->enum('status', ['draft', 'approved', 'archived'])->default('draft');

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('entry_by');
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            $table->index('name');
            $table->index('category');

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */
            $table->foreign('inventory_account_id')
                ->references('id')
                ->on('accounts')
                ->nullOnDelete();

            $table->foreign('cogs_account_id')
                ->references('id')
                ->on('accounts')
                ->nullOnDelete();

            $table->foreign('revenue_account_id')
                ->references('id')
                ->on('accounts')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
