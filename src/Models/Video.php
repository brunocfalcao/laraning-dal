<?php

namespace Laraning\DAL\Models;

use Illuminate\Database\Eloquent\Model;
use Laraning\DAL\Events\ModelEvents;
use Laraning\DAL\Events\VideoEvents;
use Laraning\DAL\Services\VideoServices;

class Video extends Model
{
    use ModelEvents;
    use VideoEvents;
    use VideoServices;

    protected $fillable = ['title',
                           'description_short',
                           'description_long',
                           'step',
                           'vimeo_id',
                           'published_at',
                           'duration',
                           'series_id',
                           'image_path',
                            ];

    protected $appends = ['updated_at_rss'];

    public function getRouteKeyName()
    {
        return request()->route()->getName() == 'videos.show' ? 'link_slug' : 'id';
    }

    public function series()
    {
        return $this->belongsTo(\Laraning\DAL\Models\Series::class);
    }

    public function socialTags()
    {
        return $this->morphMany(\Laraning\DAL\Models\SocialTag::class, 'socialiable');
    }

    public function getDurationAttribute($value)
    {
        // Return only the minutes and seconds.
        return !is_null($value) ? substr($value, 3) : '00:00';
    }

    public function getPublishedAtRfc3339Attribute($value)
    {
        return str_replace(' ', 'T', $this->attributes['updated_at']) . '+01:00';
    }

    public function setDurationAttribute($value)
    {
        $this->attributes['duration'] = !is_null($value) ? '00:'.$value : null;
    }

    public function scopeNotPublished($query)
    {
        return $query->where('published_at', null);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<>', null);
    }
}
