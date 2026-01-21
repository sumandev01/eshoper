<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class and its directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the name from the command argument (e.g., Media)
        $name = $this->argument('name');
        
        // Define the directory path
        $directory = app_path('Repositories');

        // Create the directory if it doesn't exist
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Define the file path (e.g., app/Repositories/Media.php)
        $filePath = "{$directory}/{$name}.php";

        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error("Sorry, the repository {$name} already exists!");
            return;
        }

        // The boilerplate code for the new repository file
        $content = "<?php\n\nnamespace App\Repositories;\n\nclass {$name}\n{\n    /**\n     * Fetch all data.\n     */\n    public function all()\n    {\n        // Logic goes here\n    }\n\n    /**\n     * Find data by ID.\n     */\n    public function find(\$id)\n    {\n        // Logic goes here\n    }\n}\n";

        // Write the content to the new file
        File::put($filePath, $content);

        // Standardize path for better link support (especially on Windows)
        $clickablePath = str_replace('\\', '/', $filePath);

        // Show success message in terminal with a clickable file link
        $this->info("Success! Your repository {$name} has been created.");
        $this->line("<info>File Path:</info> <href=file://{$clickablePath}>{$filePath}</>");
    }
}