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
        Schema::table('details', function (Blueprint $table) {
            $table->decimal('changed_amount', 8, 2)->after('amount')->default(0);
            $table->foreignId('currency_id')->default(1)->constrained('currencies')->after('summary_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('details', function (Blueprint $table) {
            $table->dropColumn('changed_amount');
            $table->dropColumn('currency_id');
        });
    }
};
