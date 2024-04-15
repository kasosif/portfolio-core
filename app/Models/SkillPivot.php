<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SkillPivot extends Pivot {
    protected $casts = ['icon_only' => 'boolean'];
}
