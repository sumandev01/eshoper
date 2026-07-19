<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanUnusedMediaRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:vacuum {--dry-run : Only show what would be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove media records and physical files that are not used anywhere in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $this->info($dryRun ? "Running in DRY-RUN mode..." : "Starting media vacuum...");

        // 1. Collect all used media IDs
        $usedMediaIds = collect();

        // Standard tables with media_id column
        $tablesWithMediaId = [
            'brands',
            'categories',
            'products',
            'product_galleries',
            'product_inventories',
            'sliders',
            'sub_categories',
            'team_members',
            'users',
            'sizes' // Checking just in case
        ];

        foreach ($tablesWithMediaId as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'media_id')) {
                $ids = DB::table($table)->whereNotNull('media_id')->pluck('media_id');
                $usedMediaIds = $usedMediaIds->merge($ids);
                $this->line("Found " . count($ids) . " media IDs in table: {$table}");
            }
        }

        // Special case: Settings table (site_logo, site_mobile_logo, site_favicon, site_footer_logo)
        if (Schema::hasTable('settings')) {
            $settingIds = DB::table('settings')
                ->whereIn('key_name', ['site_logo', 'site_mobile_logo', 'site_favicon', 'site_footer_logo'])
                ->whereNotNull('key_value')
                ->where('key_value', '!=', '')
                ->pluck('key_value')
                ->filter(fn($val) => is_numeric($val));
            
            $usedMediaIds = $usedMediaIds->merge($settingIds);
            $this->line("Found " . count($settingIds) . " media IDs in table: settings");
        }

        $uniqueUsedIds = $usedMediaIds->unique()->values()->toArray();
        $this->info("Total unique media IDs in use: " . count($uniqueUsedIds));

        // 2. Find orphaned records in media table
        $orphanedMedia = Media::whereNotIn('id', $uniqueUsedIds)->get();

        if ($orphanedMedia->isEmpty()) {
            $this->info("No unused media records found. Your database is clean!");
            return;
        }

        $this->warn("Found {$orphanedMedia->count()} unused media records.");

        // 3. Execution
        $count = 0;
        foreach ($orphanedMedia as $media) {
            if ($dryRun) {
                $this->line("Would delete: ID {$media->id} - {$media->src}");
            } else {
                // Using Eloquent delete() triggers the booted/deleted hook for physical file cleanup
                $media->delete();
            }
            $count++;
        }

        if ($dryRun) {
            $this->info("Dry-run complete. {$count} records would be removed.");
        } else {
            $this->info("Vacuum complete. {$count} unused records and their physical files have been removed.");
        }
    }
}
