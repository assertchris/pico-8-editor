<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Project
 *
 * @property string $id
 * @property string $name
 * @property string|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $slug
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sound> $sounds
 * @property-read int|null $sounds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sprite> $sprites
 * @property-read int|null $sprites_count
 * @property-read \App\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Project extends Model
{
    use HasFactory;
    use HasUlids;

    protected $appends = [
        'segment',
    ];

    protected $guarded = [];

    public function sprites(): HasMany
    {
        return $this->hasMany(Sprite::class);
    }

    public function sounds(): HasMany
    {
        return $this->hasMany(Sound::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function url(): Attribute
    {
        return new Attribute(
            get: fn () => route('projects.show-project', [
                'project' => $this->segment,
                'user' => $this->user->segment,
            ])
        );
    }

    public function segment(): Attribute
    {
        return new Attribute(
            get: fn () => $this->slug ?? $this->id
        );
    }
}
