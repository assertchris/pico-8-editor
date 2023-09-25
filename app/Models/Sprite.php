<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sprite extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'pixels' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function url(): Attribute
    {
        return new Attribute(
            get: fn() => route('show-project-asset-data', [
                'project' => $this->project,
                'asset' => $this->id,
            ])
        );
    }
}
