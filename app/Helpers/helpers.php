<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('ai_setting')) {
    function ai_setting($key = null)
    {
        try {
            $setting = DB::table('ai_settings')->first();
            if ($key) {
                return $setting->$key;
            }

            return $setting;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
