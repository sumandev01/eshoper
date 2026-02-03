<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    // Command name for terminal (php artisan make:service ServiceName)
    protected $signature = 'make:service {name}';

    // Description of the command
    protected $description = 'Create a new service class file';

    public function handle()
    {
        // Get the service name from command argument
        $name = $this->argument('name');
        
        // Define the file path in app/Services directory
        $path = app_path('Services/' . $name . '.php');

        // Create the Services directory if it does not exist
        if (!File::isDirectory(app_path('Services'))) {
            File::makeDirectory(app_path('Services'), 0755, true);
        }

        // Check if the service file already exists
        if (File::exists($path)) {
            $this->error("Service already exists!");
            return;
        }

        // Basic template for the new service class
        $content = "<?php\n\nnamespace App\Services;\n\nclass {$name}\n{\n    // Write your logic here\n}\n";

        // Save the file with the content
        File::put($path, $content);

        // Success message
        $this->info("Service created successfully at App/Services/{$name}.php");
    }
}