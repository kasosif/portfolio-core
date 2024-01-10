<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Translation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function language(): BelongsTo {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function translatable(): MorphTo {
        return $this->morphTo();
    }
}
