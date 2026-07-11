<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Instantiate the proxy just like AppServiceProvider does
$settings = new \SiteSettingsProxy();

// Check if a new magic key exists in the database before accessing it
$existsBefore = \App\Models\Setting::where('key_name', 'magic_test_key')->exists();
echo "Exists before: " . ($existsBefore ? 'Yes' : 'No') . "\n";

// Access the property through the proxy (this simulates ?? null in blade)
$value = isset($settings->magic_test_key) ? $settings->magic_test_key : null;
echo "Value returned: " . var_export($value, true) . "\n";

// Check if it exists in the database after accessing it
$existsAfter = \App\Models\Setting::where('key_name', 'magic_test_key')->exists();
echo "Exists after: " . ($existsAfter ? 'Yes' : 'No') . "\n";

// Clean up
if ($existsAfter) {
    \App\Models\Setting::where('key_name', 'magic_test_key')->delete();
    \Illuminate\Support\Facades\Cache::forget('site_settings');
}
