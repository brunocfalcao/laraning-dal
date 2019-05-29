<?php

namespace Laraning\DAL\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Laraning\DAL\Models\User;
use Laraning\DAL\Models\Video;

trait VideoServices
{
    public function publish($notification = null)
    {
        // Gets all the users that can have notifications and send the
        // new video email to them.
        Notification::send(User::canBeNotified()->get(), new $notification($this));

        // Update video as published.
        $this->update(['published_at' => now()]);
    }

    public function next()
    {
        // Get the next video in the same series.
        return DB::table('videos')->where([['published_at', '<>', null],
                                           ['series_id', $this->series_id],
                                           ['id', '>', $this->id], ])
                                  ->oldest()->first();
    }

    public function previous()
    {
        // Get the next video in the same series.
        return DB::table('videos')->where([['published_at', '<>', null],
                                           ['series_id', $this->series_id],
                                           ['id', '<', $this->id], ])
                                  ->latest()->first();
    }
}
