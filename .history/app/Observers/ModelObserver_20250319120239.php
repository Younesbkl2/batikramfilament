<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ModelObserver
{
    public function updating(Model $model)
    {
        if (Auth::check()) {
            $model->last_modified_by = Auth::id();
        }
    }

    public function deleting(Model $model)
    {
        if (Auth::check()) {
            $model->deleted_by = Auth::id();
            $model->save(); // Save deleted_by before soft delete
        }
    }

    public function restored(Model $model)
    {
        $model->deleted_by = null;
        $model->save(); // Clear deleted_by after restoration
    }
}

