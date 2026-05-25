<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('corporate_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('industry_id')->nullable()->constrained();
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->decimal('budget_min', 15, 2)->default(0);
            $table->decimal('budget_max', 15, 2)->default(0);
            $table->date('deadline');
            $table->json('required_tags')->nullable();
            $table->enum('status', ['open', 'reviewing', 'closed'])->default('open');
            $table->timestamps();
        });

        Schema::create('challenge_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('startup_id')->constrained('users')->cascadeOnDelete();
            $table->text('cover_letter');
            $table->text('approach')->nullable();
            $table->string('proposal_file')->nullable();
            $table->enum('status', ['pending', 'shortlisted', 'rejected'])->default('pending');
            $table->timestamps();
            $table->unique(['challenge_id', 'startup_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_applications');
        Schema::dropIfExists('challenges');
    }
};
