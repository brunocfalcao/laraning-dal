<?php

namespace Laraning\DAL\Services;

use Illuminate\Notifications\Notification;
use Laraning\DAL\Models\User;

trait UserServices
{
    /**
     * Registers a new user in the database and triggers a custom
     * notification if it's necessary.
     *
     * @param array                                        $attributes   User field attributes.
     * @param string|Illuminate\Notifications\Notification $notification Notification itself
     *
     * @return \Laraning\DAL\User
     */
    public static function register($attributes, $notification = null)
    {
        $user = User::create(['name'     => $attributes['name'],
                              'email'    => $attributes['email'],
                              'password' => bcrypt($attributes['password']), ]);

        // Trigger possible notification
        if (is_string($notification)) {
            $user->notify(new $notification());
        }

        if ($notification instanceof \Illuminate\Notifications\Notification) {
            $user->notify($notification);
        }

        return $user;
    }
}
