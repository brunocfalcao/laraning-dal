<?php

namespace Laraning\DAL\Events;

use Illuminate\Support\Facades\Validator;
use Laraning\DAL\Exceptions\LaraningException;
use Laraning\DAL\Models\SocialTag;

trait VideoEvents
{
    public function computeSaving()
    {
        // Compute attribute -- slug.
        $this->link_slug = $this->link_slug ?? str_slug($this->title);

        // Store social image in different dimensions.
        if ($this->image_path instanceof \Illuminate\Http\UploadedFile) {
            $path = public_path('storage/assets/wave/images');
            // Original image.
            $this->image_path->save('original', $path);
            // For website (half dimension).
            $this->image_path->retina()->resizeHalf()->save('website', $path);
            // For Wave (half dimension).
            $this->image_path->resizeHalf()->save('wave', $path);
            // For twitter.
            $this->image_path->reload()->resize(1024, null)->save('twitter', $path);
            // For facebook.
            $this->image_path->reload()->resize(1200, null)->save('facebook', $path);
            // For email notifications.
            $this->image_path->reload()->resize(244, null)->save('notification', $path);
            // Get file name.
            $this->image_path = $this->image_path->filename();
        }
    }

    public function computeUpdating()
    {
        $this->link_slug = str_slug($this->title);
    }

    public function validateCreatingOrUpdating()
    {
        $validator = Validator::make($this->attributes, [
            'title'                => 'required|string',
            'description_short'    => 'required|string',
            'description_long'     => 'required|string',
            'vimeo_id'             => 'nullable|string',
            'image_path'           => 'nullable',
            'duration'             => 'nullable|date_format:H:i:s',
            'link_slug'            => 'required|string',
            'series_id'            => 'nullable|integer|exists:series,id',
            'published_at'         => 'nullable|date',
        ]);

        // Validate attributes.
        if ($validator->fails()) {
            throw LaraningException::default($validator->errors()->first().'
                ('.__CLASS__.'@validateCreatingOrUpdating)', 100);
        }
    }

    public function afterSaved()
    {
        // Delete all related tags with this id.
        $this->socialTags()->getResults()->each->delete();

        // Associate social tags (morphTo relationship).
        $tag = new SocialTag();
        $tag->fb_og_title = $this->attributes['title'];
        $tag->fb_og_description = $this->attributes['description_short'];
        $tag->fb_og_type = 'website';
        $tag->fb_og_image = url(image($this->attributes['image_path'], 'facebook'));
        $tag->fb_og_url = route('wave.videos.show', ['id' => $this->{$this->primaryKey}]);
        $tag->twitter_card = 'summary_large_image';
        $tag->twitter_site = route('wave.videos.show', ['id' => $this->{$this->primaryKey}]);
        $tag->twitter_title = $this->attributes['title'];
        $tag->twitter_description = $this->attributes['description_short'];
        $tag->twitter_image = url(image($this->attributes['image_path'], 'twitter'));
        $this->socialTags()->create($tag->attributes);
    }
}
