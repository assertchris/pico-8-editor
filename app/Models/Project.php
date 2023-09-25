<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    public function sprites(): HasMany
    {
        return $this->hasMany(Sprite::class);
    }

    public function sounds(): HasMany
    {
        return $this->hasMany(Sound::class);
    }

    public function url(): Attribute
    {
        return new Attribute(
            get: fn() => route('show-project', $this)
        );
    }
}
