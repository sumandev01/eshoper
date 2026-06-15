<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OptimizeExistingMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:optimize {--force : Force re-optimization of already converted images}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert existing media to WebP and generate resized variants (thumb, medium, large)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $manager = new ImageManager(new Driver());
        $disk = Storage::disk('public');
        
        $query = Media::where('type', 'image');
        
        if (!$this->option('force')) {
            $query->where('src', 'NOT LIKE', '%.webp');
        }

        $medias = $query->get();

        if ($medias->isEmpty()) {
            $this->info('No images found for optimization.');
            return;
        }

        $this->info("Found {$medias->count()} images to optimize.");
        $bar = $this->output->createProgressBar($medias->count());
        $bar->start();

        foreach ($medias as $media) {
            $oldPath = $media->src;

            if (!$disk->exists($oldPath)) {
                $bar->advance();
                continue;
            }

            try {
                $directory = dirname($oldPath);
                $oldFilename = pathinfo($oldPath, PATHINFO_FILENAME);
                
                $currentExtension = pathinfo($oldPath, PATHINFO_EXTENSION);
                $newSlug = $currentExtension === 'webp' 
                    ? $oldFilename 
                    : Str::slug($media->alt_text ?: $oldFilename) . '_' . strtolower(Str::random(8));

                $mainPath = "{$directory}/{$newSlug}.webp";
                $thumbPath = "{$directory}/{$newSlug}-thumb.webp";
                $mediumPath = "{$directory}/{$newSlug}-medium.webp";
                $largePath = "{$directory}/{$newSlug}-large.webp";

                // Process Image
                $image = $manager->read($disk->get($oldPath));

                // Save WebP Variants
                $disk->put($mainPath, (string) (clone $image)->toWebp(80));
                $disk->put($thumbPath, (string) (clone $image)->scale(width: 300)->toWebp(80));
                $disk->put($mediumPath, (string) (clone $image)->scale(width: 600)->toWebp(80));
                $disk->put($largePath, (string) (clone $image)->scale(width: 1200)->toWebp(80));

                // Verify successful generation of at least the main file before updating DB
                if ($disk->exists($mainPath)) {
                    $media->update([
                        'name' => "{$newSlug}.webp",
                        'file_name' => "{$newSlug}.webp",
                        'src' => $mainPath,
                        'size' => $disk->size($mainPath),
                    ]);

                    // Safely delete original if it's different from the new one
                    if ($oldPath !== $mainPath && $disk->exists($oldPath)) {
                        $disk->delete($oldPath);
                    }
                }

            } catch (\Exception $e) {
                $this->error("\nFailed to process {$oldPath}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nOptimization complete!");
    }
}
