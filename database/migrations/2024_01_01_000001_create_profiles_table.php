<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('startup_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('logo')->nullable();
            $table->foreignId('industry_id')->nullable()->constrained();
            $table->enum('stage', ['idea', 'mvp', 'growth', 'scale'])->default('idea');
            $table->integer('team_size')->default(1);
            $table->integer('founded_year')->nullable();
            $table->text('elevator_pitch')->nullable();
            $table->string('pitch_deck')->nullable();
            $table->enum('funding_status', ['bootstrapped', 'pre_seed', 'seed', 'series_a', 'series_b_plus'])->default('bootstrapped');
            $table->decimal('funding_amount', 15, 2)->default(0);
            $table->json('tech_tags')->nullable();
            $table->json('seeking')->nullable(); // ['investment', 'pilot', 'mentorship']
            $table->decimal('budget_min', 15, 2)->default(0);
            $table->decimal('budget_max', 15, 2)->default(0);
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->timestamps();
        });

        Schema::create('corporate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('logo')->nullable();
            $table->foreignId('industry_id')->nullable()->constrained();
            $table->enum('company_size', ['small', 'medium', 'large', 'enterprise'])->default('medium');
            $table->text('about')->nullable();
            $table->text('problem_statement')->nullable();
            $table->json('partnership_types')->nullable(); // ['investment', 'pilot', 'mentorship', 'acquisition']
            $table->json('seeking_technologies')->nullable();
            $table->json('seeking_stages')->nullable();
            $table->decimal('budget_min', 15, 2)->default(0);
            $table->decimal('budget_max', 15, 2)->default(0);
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startup_profiles');
        Schema::dropIfExists('corporate_profiles');
        Schema::dropIfExists('industries');
    }
};
