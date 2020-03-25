<?php


namespace DLee\Platform\Core\Providers;


use DLee\Platform\Core\Supports\Helper;
use DLee\Platform\Core\Traits\LoadDataTrait;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    use LoadDataTrait;

    public function boot()
    {
        $this->setNamespace('platform/core')
            ->loadViews()
            ->loadTranslations();
    }

    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');
    }
}
