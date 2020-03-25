<?php


namespace DLee\Platform\Core\Supports;


use Illuminate\Support\Collection;

/**
 * @property Collection children
 * @property bool hasChildren
 * @property array permissions
 */
class SideBarMenuItem
{
    protected $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = collect($attributes)->mapWithKeys(function ($value, $key) {
            return [\Str::camel($key) => $value];
        })->toArray();
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }


    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function allow()
    {
        if (defined('PLATFORM_ACL') && !empty($this->attributes['permissions'])) {
            return \Auth::user()->hasPermission($this->permissions);
        }
        return true;
    }

}
