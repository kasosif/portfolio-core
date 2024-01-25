<?php

namespace App\Models;

use App\Libraries\Draftable;
use App\Libraries\HasDeletingProcesses;
use App\Libraries\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory,HasTranslations, Draftable, HasDeletingProcesses;

    protected $guarded = [];
    protected $hidden = ['candidate_id'];
    protected $casts = ['current' => 'boolean', 'draft' => 'boolean'];

    public function candidate(): BelongsTo {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
