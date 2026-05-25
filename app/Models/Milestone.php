<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = ['startup_profile_id', 'title', 'description', 'milestone_date', 'icon', 'sort_order'];

    protected $casts = [
        'milestone_date' => 'date',
    ];

    public function startupProfile(): BelongsTo
    {
        return $this->belongsTo(StartupProfile::class);
    }
}
