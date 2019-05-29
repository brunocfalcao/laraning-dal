<?php

namespace Laraning\DAL\Models;

use Illuminate\Database\Eloquent\Model;
use Laraning\DAL\Events\ModelEvents;
use Laraning\DAL\Events\SeriesEvents;

class Series extends Model
{
    use ModelEvents;
    use SeriesEvents;

    protected $fillable = ['title',
                           'description_short',
                           'description_long',
                           'series_type',
                           'image_path', ];

    public function getRouteKeyName()
    {
        return request()->route()->getName() == 'series.show' ? 'link_slug' : 'id';
    }

    public function videos()
    {
        return $this->hasMany(\Laraning\DAL\Models\Video::class);
    }

    public function socialTags()
    {
        return $this->morphMany(\Laraning\DAL\Models\SocialTag::class, 'socialiable');
    }

    public function scopeNonSequenced($query)
    {
        return $query->where('series_type', 'non sequenced');
    }

    public function scopeSequenced($query)
    {
        return $query->where('series_type', 'sequenced');
    }
}
