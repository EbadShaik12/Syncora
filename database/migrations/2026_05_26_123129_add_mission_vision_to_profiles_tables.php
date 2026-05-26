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
        Schema::table('startup_profiles', function (Blueprint $table) {
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->string('annual_revenue')->nullable();
        });

        Schema::table('corporate_profiles', function (Blueprint $table) {
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->string('annual_revenue')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('startup_profiles', function (Blueprint $table) {
            $table->dropColumn(['mission', 'vision', 'annual_revenue']);
        });

        Schema::table('corporate_profiles', function (Blueprint $table) {
            $table->dropColumn(['mission', 'vision', 'annual_revenue']);
        });
    }
};
