<?php


namespace DLee\Platform\Core\Supports;


class Helper
{
    public static function autoload(string $helperPath)
    {
        $helperFiles = \File::glob($helperPath . DIRECTORY_SEPARATOR . '*.php');
        foreach ($helperFiles as $helperFile) {
            \File::requireOnce($helperFile);
        }
    }
}
