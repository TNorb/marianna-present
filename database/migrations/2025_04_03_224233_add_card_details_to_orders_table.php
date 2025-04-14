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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('card_number')->nullable()->after('total_price');
            $table->string('card_type')->nullable()->after('card_number');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->boolean('fragile')->nullable()->after('fragile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
