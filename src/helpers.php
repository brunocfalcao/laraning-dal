<?php

if (! function_exists('delete_files')) {
    function delete_files($collection)
    {
        if ($collection->count() > 0) {
            $collection->each(function ($item) {
                if (is_file($item)) {
                    File::delete($item);
                }

                if (is_dir($item)) {
                    File::deleteDirectory($item);
                }
            });
        }
    }
}
