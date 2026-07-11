<?php

function formatBDT($amount)
{
    $amount = (int)$amount;

    $lastThree = substr($amount, -3);

    $restUnits = substr($amount, 0, -3);

    if ($restUnits != '') {
        $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
        return $restUnits . "," . $lastThree;
    }

    return $lastThree;
}

if (!function_exists('clean_html')) {
    function clean_html($html)
    {
        if (empty($html)) return $html;
        $allowed_tags = '<p><a><b><i><strong><em><ul><li><ol><br><img><h1><h2><h3><h4><h5><h6><span><div><table><tr><td><th><tbody><thead><tfoot><hr><blockquote>';
        return strip_tags($html, $allowed_tags);
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        // Cache settings forever to avoid DB queries on every page load
        $settings = \Illuminate\Support\Facades\Cache::rememberForever('site_settings', function () {
            return \App\Models\Setting::pluck('key_value', 'key_name')->toArray();
        });

        // If the key exists in cache, return its value
        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        // Auto-creation: If the key is missing in the database, insert it and return default.
        // This makes sure the application never crashes due to missing seeder data.
        \App\Models\Setting::create([
            'key_name' => $key,
            'key_value' => $default
        ]);

        // Clear the cache so it gets rebuilt on the next call
        \Illuminate\Support\Facades\Cache::forget('site_settings');

        return $default;
    }
}

if (!class_exists('SiteSettingsProxy')) {
    class SiteSettingsProxy {
        private $cached = [];

        public function __get($key) {
            if (array_key_exists($key, $this->cached)) {
                return $this->cached[$key];
            }
            return get_setting($key);
        }

        public function __set($key, $value) {
            $this->cached[$key] = $value;
        }

        public function __isset($key) {
            return true;
        }
    }
}
