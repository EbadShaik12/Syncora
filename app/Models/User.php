<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function startupProfile(): HasOne
    {
        return $this->hasOne(StartupProfile::class);
    }

    public function corporateProfile(): HasOne
    {
        return $this->hasOne(CorporateProfile::class);
    }

    public function sentSignals(): HasMany
    {
        return $this->hasMany(InterestSignal::class, 'sender_id');
    }

    public function receivedSignals(): HasMany
    {
        return $this->hasMany(InterestSignal::class, 'receiver_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('awarded_at')->withTimestamps();
    }

    public function challenges(): HasMany
    {
        return $this->hasMany(Challenge::class, 'corporate_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ChallengeApplication::class, 'startup_id');
    }

    // Connections (matched relationships)
    public function connectionsAsUserOne(): HasMany
    {
        return $this->hasMany(Connection::class, 'user_one_id');
    }

    public function connectionsAsUserTwo(): HasMany
    {
        return $this->hasMany(Connection::class, 'user_two_id');
    }

    public function getAllConnections()
    {
        return Connection::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id)
            ->where('status', 'active')
            ->get();
    }

    // Helpers
    public function isStartup(): bool
    {
        return $this->role === 'startup';
    }

    public function isCorporate(): bool
    {
        return $this->role === 'corporate';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function profile()
    {
        if ($this->isStartup()) return $this->startupProfile;
        if ($this->isCorporate()) return $this->corporateProfile;
        return null;
    }

    public function companyName(): string
    {
        return $this->profile()?->company_name ?? $this->name;
    }

    public function logoUrl(): string
    {
        $logo = $this->profile()?->logo;
        if ($logo) return asset('storage/' . $logo);
        // Default gradient avatar based on name
        $initial = strtoupper(substr($this->companyName(), 0, 1));
        return "https://ui-avatars.com/api/?name={$initial}&background=6366f1&color=fff&size=200&bold=true";
    }
}
