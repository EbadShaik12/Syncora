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
        Schema::table('connections', function (Blueprint $table) {
            $table->tinyInteger('rating_by_user_one')->nullable()->unsigned();
            $table->text('review_by_user_one')->nullable();
            $table->tinyInteger('rating_by_user_two')->nullable()->unsigned();
            $table->text('review_by_user_two')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->dropColumn([
                'rating_by_user_one',
                'review_by_user_one',
                'rating_by_user_two',
                'review_by_user_two'
            ]);
        });
    }
};
