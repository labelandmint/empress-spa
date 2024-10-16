<?php
use App\Models\Setting;

if (!function_exists('get_setting')) {
    /**
     * Retrieve a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting($key, $default = null)
    {
        $setting = Setting::first(); // Assumes only one row with all settings
        return $setting ? $setting->$key : $default;
    }
}
