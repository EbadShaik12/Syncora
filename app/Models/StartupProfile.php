<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StartupProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'logo', 'industry_id', 'stage', 'team_size',
        'founded_year', 'elevator_pitch', 'pitch_deck', 'funding_status',
        'funding_amount', 'tech_tags', 'seeking', 'budget_min', 'budget_max',
        'website', 'linkedin', 'city', 'state', 'country',
    ];

    protected $casts = [
        'tech_tags' => 'array',
        'seeking' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('milestone_date');
    }

    public function stageLabel(): string
    {
        return match($this->stage) {
            'idea' => 'Idea Stage',
            'mvp' => 'MVP',
            'growth' => 'Growth',
            'scale' => 'Scaling',
            default => ucfirst($this->stage),
        };
    }

    public function stageColor(): string
    {
        return match($this->stage) {
            'idea' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'mvp' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'growth' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'scale' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
