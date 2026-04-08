<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });

        Schema::table('summaries', function (Blueprint $table) {
            $table->foreignId('school_year_id')->nullable()->constrained('school_years')->onDelete('set null');
        });

        // Backfill existing summaries with the currently active school year
        $active = DB::table('school_years')->where('is_active', true)->first();
        if ($active) {
            DB::table('summaries')->whereNull('school_year_id')->update(['school_year_id' => $active->id]);
        }
    }

    public function down(): void
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropForeign(['school_year_id']);
            $table->dropColumn('school_year_id');
        });

        Schema::table('school_years', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
