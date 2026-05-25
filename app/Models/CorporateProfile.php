<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorporateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'logo', 'industry_id', 'company_size', 'about',
        'problem_statement', 'partnership_types', 'seeking_technologies',
        'seeking_stages', 'budget_min', 'budget_max', 'website', 'linkedin',
        'city', 'state', 'country',
    ];

    protected $casts = [
        'partnership_types' => 'array',
        'seeking_technologies' => 'array',
        'seeking_stages' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }
}
