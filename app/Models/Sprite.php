<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Sprite
 *
 * @property string $id
 * @property string $name
 * @property array|null $pixels
 * @property string|null $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $slug
 * @property array|null $flags
 * @property int $size
 * @property-read \App\Models\Project|null $project
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite whereFlags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite wherePixels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprite whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Sprite extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'pixels' => 'array',
        'flags' => 'array',
    ];

    protected $appends = [
        'url',
        'segment',
        'key',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function url(): Attribute
    {
        return new Attribute(
            get: fn () => route('projects.assets.show-data', [
                'user' => $this->project->user->segment,
                'project' => $this->project->segment,
                'asset' => $this->segment,
                'type' => 'spr',
            ])
        );
    }

    public function segment(): Attribute
    {
        return new Attribute(
            get: fn () => $this->slug ?? $this->id
        );
    }

    public function key(): Attribute
    {
        return new Attribute(
            get: fn () => $this->slug ?? $this->name
        );
    }
}
