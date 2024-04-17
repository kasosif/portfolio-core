<?php

namespace App\Models;

use App\Libraries\Draftable;
use App\Libraries\HasDeletingProcesses;
use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Experience extends Model
{
    use HasFactory , HasTranslations, Draftable, HasDeletingProcesses;

    protected $guarded = [];
    protected $with = ['tasks'];
    protected $casts = ['current' => 'boolean', 'draft' => 'boolean'];
    protected $hidden = ['candidate_id'];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }


    public function tasks():MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
