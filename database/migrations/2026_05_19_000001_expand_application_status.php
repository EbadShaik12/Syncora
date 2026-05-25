<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN for enums.
        // We recreate the column by: copy data → drop old → add new text column → restore data.
        // This effectively removes the CHECK constraint and uses a text column with app-level validation.

        Schema::table('challenge_applications', function (Blueprint $table) {
            $table->string('status_new')->default('pending')->after('approach');
        });

        // Copy existing values
        DB::statement('UPDATE challenge_applications SET status_new = status');

        Schema::table('challenge_applications', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('challenge_applications', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    public function down(): void
    {
        // Revert to enum — any non-standard values will be lost
        Schema::table('challenge_applications', function (Blueprint $table) {
            $table->string('status_old')->default('pending')->after('approach');
        });
        DB::statement("UPDATE challenge_applications SET status_old = CASE WHEN status IN ('pending','shortlisted','rejected') THEN status ELSE 'pending' END");
        Schema::table('challenge_applications', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('challenge_applications', function (Blueprint $table) {
            $table->renameColumn('status_old', 'status');
        });
    }
};
