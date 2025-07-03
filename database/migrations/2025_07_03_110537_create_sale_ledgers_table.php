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
        Schema::create('sale_ledgers', function (Blueprint $table) {
            $table->id();
             $table->double('total_amount');
            $table->double('pending_amount');
            $table->double('paid_amount');
            $table->string('sale_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_ledgers');
    }
};
