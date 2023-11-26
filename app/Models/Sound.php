<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Sound
 *
 * @property string $id
 * @property string $name
 * @property int|null $length
 * @property array|null $notes
 * @property string|null $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $slug
 * @property-read \App\Models\Project|null $project
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Sound newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sound newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sound query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sound whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Sound extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'notes' => 'array',
    ];

    protected $appends = [
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
            get: function() {
                $segment = $this->slug ?? $this->name;

                $script = "var sounds = {
                    // ...
                    '{$segment}': [";

                $script .= "
                        {$this->length},";

                for ($i = 0; $i < 32; $i++) {
                    $script .= "
                    [{$this->notes[$i][0]}, {$this->notes[$i][1]}, {$this->notes[$i][2]}],";
                }

                $script .= "
                    ],
                };";

                return $script;
            }
        );
    }
}
