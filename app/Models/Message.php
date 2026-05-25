<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['connection_id', 'sender_id', 'content', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function connection(): BelongsTo
    {
        return $this->belongsTo(Connection::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
