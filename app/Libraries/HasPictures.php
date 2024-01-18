<?php

namespace App\Libraries;
use App\Models\Picture;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
trait HasPictures {
    public function pictures():MorphMany
    {
        return $this->morphMany(Picture::class, 'galleriable');
    }

    public function picture(): object | null
    {
        return $this->pictures()->where('main', true)->first();
    }

    public function setMainPicture(int $pictureId): void {
        $this->pictures()->update(['main' => false]);
        $this->pictures()->where('id', $pictureId)->update(['main' => true]);
    }

    public function addPicture(UploadedFile $picture, bool $isMain): void {
        $filename = Str::random(12). '.' . $picture->getClientOriginalExtension();
        $path = 'pictures/'.$this->getTable().'/' . $this->getKey();
        $picture->storeAs($path, $filename);
        $mainexists = $this->pictures()
            ->where('main',true)
            ->exists();
        $picture = new Picture([
            'main' => !$mainexists,
            'path' => $path .'/'.$filename,
            'name' => $filename
        ]);
        $picture = $this->pictures()->save($picture);
        if ($isMain) {
            $this->setMainPicture($picture->id);
        }
    }
    public function addPictures(array $pictures): void {
        foreach ($pictures as $key => $picture) {
            $this->addPicture($picture, $key === 0);
        }
    }

    public function deletePicture(int $pictureId): void {
        $picture = $this->pictures()->where('id', $pictureId)->first();
        if ($picture) {
            $isMain = $picture->main;
            Storage::delete($picture->path);
            $picture->delete();
            if ($isMain) {
                $nextPicture = $this->pictures()->first();
                if ($nextPicture) {
                    $nextPicture->main = true;
                    $nextPicture->save();
                }
            }
        }

    }
    public function deleteAllPictures(): void {
        foreach ($this->pictures()->get() as $picture) {
            $this->deletePicture($picture->id);
        }
    }
}
