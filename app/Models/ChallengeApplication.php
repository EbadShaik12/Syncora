<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id', 'startup_id', 'cover_letter', 'approach',
        'proposal_file', 'status',
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function startup(): BelongsTo
    {
        return $this->belongsTo(User::class, 'startup_id');
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'shortlisted' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
