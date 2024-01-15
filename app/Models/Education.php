<?php

namespace App\Models;

use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory,HasTranslations;

    protected $guarded = [];
    protected $hidden = ['candidate_id'];
    protected $casts = ['current' => 'boolean'];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
