<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOrphanedMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:clean {--dry-run : Only list files without deleting them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove physical files from storage that are not linked to any database record';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $disk = Storage::disk('public');
        $dryRun = $this->option('dry-run');

        $this->info($dryRun ? "Running in DRY-RUN mode..." : "Cleaning orphaned media...");

        // 1. Get all valid files from database
        $mediaRecords = Media::all();
        $validFiles = [];

        foreach ($mediaRecords as $media) {
            if (!$media->src) continue;

            // Add the main file
            $validFiles[] = $media->src;

            // Only for images, add the known variants
            if ($media->type === 'image') {
                $directory = dirname($media->src);
                $filename = pathinfo($media->src, PATHINFO_FILENAME);
                
                $validFiles[] = "{$directory}/{$filename}-thumb.webp";
                $validFiles[] = "{$directory}/{$filename}-medium.webp";
                $validFiles[] = "{$directory}/{$filename}-large.webp";
            }
        }

        $validFilesSet = array_flip($validFiles);

        // 2. Scan physical storage
        // We'll scan common directories where media is stored. 
        // Based on the code, 'media' is the primary one.
        $allFiles = $disk->allFiles();
        
        $orphanedCount = 0;
        $totalFreedSpace = 0;

        foreach ($allFiles as $filePath) {
            // Skip system files like .gitignore
            if (basename($filePath) === '.gitignore') continue;

            if (!isset($validFilesSet[$filePath])) {
                $fileSize = $disk->size($filePath);
                $this->warn("Orphaned: {$filePath} (" . number_format($fileSize / 1024, 2) . " KB)");
                
                if (!$dryRun) {
                    $disk->delete($filePath);
                }
                
                $orphanedCount++;
                $totalFreedSpace += $fileSize;
            }
        }

        $this->info("----------------------------------");
        if ($dryRun) {
            $this->info("Dry-run complete. Found {$orphanedCount} orphaned files.");
            $this->info("Estimated space to be freed: " . number_format($totalFreedSpace / 1024 / 1024, 2) . " MB");
        } else {
            $this->info("Cleanup complete. Deleted {$orphanedCount} orphaned files.");
            $this->info("Total space freed: " . number_format($totalFreedSpace / 1024 / 1024, 2) . " MB");
        }
    }
}
