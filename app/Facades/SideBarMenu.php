<?php


namespace DLee\Platform\Core\Facades;


use DLee\Platform\Core\Supports\SideBarMenu as SideBarMenuBase;
use Illuminate\Support\Facades\Facade;

class SideBarMenu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SideBarMenuBase::class;
    }
}
