<?php

namespace App\Libraries;

trait HasDeletingProcesses
{
    public static function boot()
    {
        parent::boot();
        self::deleting(function ($item) {
            if ($item->pictures) {
                $item->pictures->each(function ($picture) {
                    $picture->delete();
                });
            }
            if ($item->translations) {
                $item->translations->each(function ($translation) {
                    $translation->delete();
                });
            }

        });
    }

}
