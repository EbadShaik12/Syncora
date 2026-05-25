<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one_id', 'user_two_id', 'matched_at', 'status',
        'rating_by_user_one', 'review_by_user_one',
        'rating_by_user_two', 'review_by_user_two'
    ];

    protected $casts = [
        'matched_at' => 'datetime',
        'rating_by_user_one' => 'integer',
        'rating_by_user_two' => 'integer',
    ];

    public function hasRated($userId): bool
    {
        if ($this->user_one_id == $userId) {
            return !is_null($this->rating_by_user_one);
        }
        return !is_null($this->rating_by_user_two);
    }

    public function getRatingAndReview($userId): array
    {
        if ($this->user_one_id == $userId) {
            return [
                'rating' => $this->rating_by_user_one,
                'review' => $this->review_by_user_one
            ];
        }
        return [
            'rating' => $this->rating_by_user_two,
            'review' => $this->review_by_user_two
        ];
    }

    public function getRatingOfUser($userId): array
    {
        // Returns the rating and review given to this user by the other user
        if ($this->user_one_id == $userId) {
            return [
                'rating' => $this->rating_by_user_two,
                'review' => $this->review_by_user_two
            ];
        }
        return [
            'rating' => $this->rating_by_user_one,
            'review' => $this->review_by_user_one
        ];
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function otherUser($currentUserId)
    {
        return $this->user_one_id == $currentUserId ? $this->userTwo : $this->userOne;
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
