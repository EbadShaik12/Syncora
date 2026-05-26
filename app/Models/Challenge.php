<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'corporate_id', 'industry_id', 'title', 'description', 'requirements',
        'budget_min', 'budget_max', 'deadline', 'required_tags', 'status',
        'attachment_path', 'attachment_filename',
    ];

    protected $casts = [
        'required_tags' => 'array',
        'deadline' => 'date',
    ];

    public function corporate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'corporate_id');
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ChallengeApplication::class);
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'open' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'reviewing' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' && $this->deadline->isFuture();
    }
}
