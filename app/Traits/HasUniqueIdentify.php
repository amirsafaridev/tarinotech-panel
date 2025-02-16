<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

trait HasUniqueIdentify
{
    public bool $isInt = false;

    abstract public function identifiable(): string;

    protected static function bootHasUniqueIdentify()
    {
        static::creating(function ($model) {
            $model->{$model->identifiable()} = $model->generateUniqueValue($model->identifiable());
        });
    }

    private function generateUniqueValue($col): int|string
    {
        $rnd = $this->isInt ? rand(1000, 999999) : Str::random(rand(6, 10));

        $query = self::where($col, $rnd);

        if (in_array(SoftDeletes::class, class_uses($this))) {
            $query->withTrashed();
        }

        $exists = $query->limit(1)->exists();

        if ($exists) {
            return $this->generateUniqueValue($col);
        }

        return $rnd;
    }
}
