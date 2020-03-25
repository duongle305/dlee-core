<?php


namespace DLee\Platform\Core\Supports;


use DLee\Platform\Auth\Models\User;
use Illuminate\Support\Collection;

class SideBarMenu
{

    /**
     * @var User
     */
    public $user;

    public $items = [];
    /**
     * @var string
     */
    public $url;
    /**
     * @var string|null
     */
    public $prefix;

    public function init(User $user = null)
    {
        $this->user = $user ?? auth()->user();
        $this->url = \URL::full();
        $this->prefix = request()->route()->getPrefix();
        $this->items = \Config::get('sidebar.items') ?? [];
    }

    /**
     * @param $items
     * @return array|string
     */
    protected function prepare($items)
    {
        $items = ($items instanceof Collection ? $items : collect($items))->sortBy(function ($item) {
            return $item->sort;
        });
        $html = '';
        foreach ($items as $item) {
            if ($item->allow()) {
                if ($item->hasChildren) $children = $this->prepare($item->children);
                try {
                    $item->url = $item->hasChildren ? 'javascript:;' : ($item->route ? route($item->route) : $item->url);
                    $children = isset($children) ? $children : null;
                    $html .= view('platform.core::layouts.sidebar.item', compact('item', 'children'))->render();
                } catch (\Throwable $e) {
                }
            }
        }
        return $html;
    }

    public function render()
    {
        try {
            return view('platform.core::layouts.sidebar.main', ['items' => $this->prepare($this->items)])->render();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
