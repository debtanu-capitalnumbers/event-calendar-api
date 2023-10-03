<?php
if (! function_exists('my_export_csv')) {
    function my_export_csv()
    {
        $foldername = storage_path('app/public/csv');
        if (! File::exists($foldername)) {
            File::makeDirectory($foldername, 0755, true, true);
        }

        $fileSystemIterator = glob(storage_path('app/public/csv').'/*.{csv,ics,html}', GLOB_BRACE);
        $now = time();
        foreach ($fileSystemIterator as $file) {
            $filename = $file;
            if (is_file($filename)) {
                if ($now - filemtime($filename) >= 60 * 60 * 23 * 2) {
                    unlink($filename);
                }
            }
        }
    }
}
?>