<?php

namespace Laraning\DAL\Events;

trait ModelEvents
{
    public static function bootModelEvents()
    {
        static::saving(function ($model) {
            if (method_exists($model, 'computeSaving')) {
                $model->computeSaving();
            }

            if (method_exists($model, 'validateSaving')) {
                return $model->validateSaving();
            }
        });

        static::creating(function ($model) {
            if (method_exists($model, 'computeCreating')) {
                $model->computeCreating();
            }

            if (method_exists($model, 'validateCreatingOrUpdating')) {
                $model->validateCreatingOrUpdating();
            }

            if (method_exists($model, 'validateCreating')) {
                $model->validateCreating();
            }
        });

        static::updating(function ($model) {
            if (method_exists($model, 'computeUpdating')) {
                $model->computeUpdating();
            }

            if (method_exists($model, 'validateCreatingOrUpdating')) {
                $model->validateCreatingOrUpdating();
            }

            if (method_exists($model, 'validateUpdating')) {
                $model->validateUpdating();
            }
        });

        static::deleting(function ($model) {
            if (method_exists($model, 'computeDeleting')) {
                $model->computeDeleting();
            }

            if (method_exists($model, 'validateDeleting')) {
                return $model->validateDeleting();
            }
        });

        static::saved(function ($model) {
            if (method_exists($model, 'afterSaved')) {
                return $model->afterSaved();
            }
        });

        static::created(function ($model) {
            if (method_exists($model, 'afterCreated')) {
                return $model->afterCreated();
            }
        });

        static::updated(function ($model) {
            if (method_exists($model, 'afterUpdated')) {
                return $model->afterUpdated();
            }
        });
    }
}
