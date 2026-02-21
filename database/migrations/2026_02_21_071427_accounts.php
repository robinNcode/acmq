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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->default(1);
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', [
                'asset',
                'liability',
                'equity',
                'income',
                'expense'
            ]);
            $table->unsignedBigInteger('parent_id')->nullable(); // for hierarchy
            $table->timestamps();

            $table->index(['branch_id', 'type']);
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
