<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurriculumVitae extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['path','candidate_id','language_id'];
    protected $with = ['language:id,code,name'];
    protected $appends = ['url','public_url'];
    protected $casts = ['public' => 'boolean'];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function getUrlAttribute(): string {
        return url('api/v1/resumes/download/'. $this->id);
    }

    public function getPublicUrlAttribute(): string | null {
        if ($this->public) {
            return url('api/v1/cdn/resumes/'.$this->id);
        }
        return null;
    }

    public function language(): BelongsTo {
        return $this->belongsTo(Language::class, 'language_id');
    }

}
