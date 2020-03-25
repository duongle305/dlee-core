<?php
if (!function_exists('module_path')) {
    function module_path(string $path = null): string
    {
        return realpath(__DIR__ . '/../../../' . $path);
    }
};

if (!function_exists('sidebar_menu')) {
    function sidebar_menu()
    {
        return DLee\Platform\Core\Facades\SideBarMenu::render();
    }
}
