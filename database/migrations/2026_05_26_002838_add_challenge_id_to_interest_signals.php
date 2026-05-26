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
        Schema::table('interest_signals', function (Blueprint $table) {
            // Drop old unique constraint
            $table->dropUnique(['sender_id', 'receiver_id']);
            
            // Add challenge_id field
            $table->foreignId('challenge_id')->nullable()->after('receiver_id')->constrained('challenges')->cascadeOnDelete();
            
            // Add new unique constraint
            $table->unique(['sender_id', 'receiver_id', 'challenge_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interest_signals', function (Blueprint $table) {
            $table->dropUnique(['sender_id', 'receiver_id', 'challenge_id']);
            $table->dropColumn('challenge_id');
            $table->unique(['sender_id', 'receiver_id']);
        });
    }
};
