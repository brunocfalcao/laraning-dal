<?php

namespace Laraning\DAL\Models;

use Illuminate\Database\Eloquent\Model;

class SocialTag extends Model
{
    protected $fillable = [
        'fb_og_title',
        'fb_og_description',
        'fb_og_type',
        'fb_og_image',
        'fb_og_url',
        'twitter_card',
        'twitter_site',
        'twitter_title',
        'twitter_description',
        'twitter_image',
    ];

    public function socialiable()
    {
        return $this->morphTo();
    }
}
