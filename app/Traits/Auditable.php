<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::creating(function (Model $model){
            $model->created_at = now();
            $model->created_by = Auth::id();
        });

        static::updating(function(Model $model)
        {
            $model->updated_at = now();
            $model->updated_by = Auth::id();
        });

    }

}
