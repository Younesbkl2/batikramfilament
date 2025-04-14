<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    protected $fillable = ['feature_one', 'feature_one_attributed_at', 'feature_one_modified_at', 'feature_two', 'feature_two_attributed_at', 'feature_two_modified_at'];

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
                        // First time setting to 1
                        $model->{$attributedField} = now();
                    } elseif ($oldValue != $newValue) {
                        // Any subsequent change
                        $model->{$modifiedField} = now();
                    }
                }
            }
        });
    }
}
