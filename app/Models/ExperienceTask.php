<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperienceTask extends Model
{
    use HasFactory;

    protected $hidden = ['experience_id'];
    protected $guarded = [];

    public function experience(): BelongsTo {
        return $this->belongsTo(Experience::class, 'experience_id');
    }
}
