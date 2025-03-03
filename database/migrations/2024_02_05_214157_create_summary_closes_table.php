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
        Schema::create('summary_closes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['MONTH', 'YEAR'])->default('MONTH');
            $table->date('from_date');
            $table->date('to_date');

            $table->decimal('petty_cash', 8, 2)->default(0.00);//caja chica

            $table->decimal('previous_balance', 8, 2)->nullable()->default(0.00);
            $table->decimal('previous_income_balance', 8, 2)->nullable()->default(0.00);
            $table->decimal('previous_expense_balance', 8, 2)->nullable()->default(0.00);

            $table->decimal('current_balance', 8, 2)->nullable()->default(0.00);
            $table->decimal('current_income_balance', 8, 2)->nullable()->default(0.00);
            $table->decimal('current_expense_balance', 8, 2)->nullable()->default(0.00);

            $table->json('account_balance')->nullable();

            $table->decimal('current_income_nulled', 8, 2)->nullable()->default(0.00);
            $table->decimal('current_expense_nulled', 8, 2)->nullable()->default(0.00);
            $table->decimal('current_nulled', 8, 2)->nullable()->default(0.00);

            $table->string('generated_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_closes');
    }
};
