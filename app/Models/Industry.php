<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon'];

    public function startupProfiles()
    {
        return $this->hasMany(StartupProfile::class);
    }

    public function corporateProfiles()
    {
        return $this->hasMany(CorporateProfile::class);
    }
}
