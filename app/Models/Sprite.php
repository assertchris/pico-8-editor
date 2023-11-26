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
        'pretty',
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

    public function pretty(): Attribute
    {
        return new Attribute(
            get: function () {
                $segment = $this->slug ?? $this->name;

                $pixels = $this->pixels;
                $flags = $this->flags;

                if ($pixels == null || count($pixels) < 1) {
                    $pixels = [];

                    for ($y = 0; $y < $this->size; $y++) {
                        for ($x = 0; $x < $this->size; $x++) {
                            $pixels[$this->address($x, $y)] = -1;
                        }
                    }
                }

                if ($flags == null || count($flags) < 1) {
                    $flags = [];

                    for ($i = 0; $i < $this->size; $i++) {
                        $flags[$i] = false;
                    }
                }

                $script = "var sprites = [
                    // ...
                    '{$segment}': [
                        [";

                for ($y = 0; $y < $this->size; $y++) {
                    $script .= "\n";

                    for ($x = 0; $x < $this->size; $x++) {
                        $color = $pixels[$this->address($x, $y)];
                        $script .= " {$color},";
                    }
                }

                $script .= '
                                ], [
                            ';

                for ($i = 0; $i < count($flags); $i++) {
                    $flag = $flags[$i] ? 'true' : 'false';
                    $script .= "{$flag}, ";
                }

                $script .= '
                        ],
                        ';

                $script .= '];';

                return $script;
            }
        );
    }

    private function address(int $x, int $y): int
    {
        return ($y * (int) $this->size) + $x;
    }
}
