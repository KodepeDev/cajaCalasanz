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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('company_name')->nullable()->before('created_at');
            $table->string('company_ruc')->nullable()->before('created_at');
            $table->string('email')->nullable()->before('created_at');
            $table->string('phone')->nullable()->before('created_at');
            $table->string('logo')->nullable()->before('created_at');
            $table->boolean('before_date_add')->default(true)->before('created_at');
            $table->integer('number_of_days_add')->default(3)->before('created_at');
            $table->boolean('before_date_out')->default(true)->before('created_at');
            $table->integer('number_of_days_out')->default(3)->before('created_at');
            $table->string('report_type')->before('created_at')->nullable();
            $table->string('receipt_type')->before('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('company_ruc');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('logo');
            $table->dropColumn('before_date_add');
            $table->dropColumn('number_of_days_add');
            $table->dropColumn('before_date_out');
            $table->dropColumn('number_of_days_out');
            $table->dropColumn('report_type');
            $table->dropColumn('receipt_type');
        });
    }
};
