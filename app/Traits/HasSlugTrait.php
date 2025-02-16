<?php

namespace App\Traits;

trait HasSlugTrait
{
    public string $slugField = 'slug';

    public string $sourceField = 'title';

    public static function bootHasSlugTrait()
    {
        static::creating(function ($model) {
            $model->slug = $model->generateUniqueSlug();
        });
    }

    protected function generateUniqueSlug(): string
    {
        $sourceFieldValue = $this->{$this->sourceField};
        $baseSlug = generatePersianSlug($sourceFieldValue);
        $uniqueSlug = $baseSlug;
        $count = 1;

        while ($this->slugExists($uniqueSlug)) {
            $uniqueSlug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $uniqueSlug;
    }

    protected function slugExists(string $slug): bool
    {
        return static::query()
            ->where($this->slugField, $slug)
            ->exists();
    }
}
