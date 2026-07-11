<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

class PermissionScannerService
{
    /**
     * Scans the Permission Enum directory, extracts cases, creates missing permissions,
     * and returns the structured array for the view.
     * 
     * @return array
     */
    public function syncAndGetPermissions(): array
    {
        $directory = app_path('Enums/Permission');
        $files = File::allFiles($directory);

        $adminAccess = [];
        $groups = [];

        foreach ($files as $file) {
            $fileName = $file->getFilenameWithoutExtension();
            $className = "App\\Enums\\Permission\\" . $fileName;

            if (enum_exists($className)) {
                $cases = $className::cases();
                
                // Sync to database
                foreach ($cases as $case) {
                    Permission::firstOrCreate([
                        'name' => $case->value,
                        'guard_name' => 'web'
                    ]);
                }

                // Format the group name (e.g. 'CategoryPermission' -> 'Category')
                if ($fileName === 'AdminAccessEnums') {
                    $adminAccess['Admin Access'] = $cases;
                } else {
                    $groupName = str_replace(['Permission', 'Enums'], '', $fileName);
                    // Add spaces before capital letters for better readability
                    $groupName = preg_replace('/(?<!^)([A-Z])/', ' $1', $groupName);
                    
                    $groups[$groupName] = $cases;
                }
            }
        }

        // Sort groups alphabetically for better UI
        ksort($groups);

        return [
            'adminAccess' => $adminAccess,
            'groups' => $groups
        ];
    }
}
