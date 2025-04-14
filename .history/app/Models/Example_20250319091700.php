<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Example extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'feature_one', 'feature_two'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach (['feature_one', 'feature_two'] as $feature) {
                $attributedField = "{$feature}_attributed_at";
                $modifiedField = "{$feature}_modified_at";

                if ($model->isDirty($feature)) {
                    $oldValue = $model->getOriginal($feature);
                    $newValue = $model->{$feature};

                    if ($oldValue == 0 && $newValue == 1) {
                        // Set attributed_at only if it is null (first time)
                        if (is_null($model->getOriginal($attributedField))) {
                            $model->{$attributedField} = now();
                        }
                    }

                    // Always update modified_at when the value changes (either 0 → 1 or 1 → 0)
                    if ($oldValue != $newValue) {
                        $model->{$modifiedField} = now();
                    }
                }
            }
        });
    }
}
