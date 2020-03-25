<?php


namespace DLee\Platform\Core\Traits;

/**
 * Trait LoadDataTrait
 * @package DLee\Platform\Core\App\Traits
 * @mixin \Illuminate\Support\ServiceProvider
 */
trait LoadDataTrait
{
    protected $namespace = null;

    protected $modulePath = null;

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace(string $namespace): self
    {
        $this->namespace = rtrim(ltrim($namespace, '/'), '/');
        return $this->setModuleLoaded();
    }


    /**
     * @return $this
     */
    private function setModuleLoaded(): self
    {
        $key = \Str::upper(str_replace('.', '_', $this->getDotedNamespace()));
        if (!defined($key))
            define($key, true);
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setModulePath(string $path): self
    {
        $this->modulePath = $path;
        return $this;
    }

    /**
     * @param $alias
     * @param $middleware
     * @return $this
     */
    public function aliasMiddleware($alias, $middleware): self
    {
        $this->app['router']->aliasMiddleware($alias, $middleware);
        return $this;
    }

    /**
     * @param $middleware
     * @return $this
     */
    public function pushToGroupMiddleware($middleware): self
    {
        $this->app['router']->pushToGroupMiddleware($middleware);
        return $this;
    }

    /**
     * @return $this
     */
    public function loadTranslations(): self
    {
        $this->loadTranslationsFrom($this->getTranslationPath(), $this->getDotedNamespace());
        return $this;
    }

    /**
     * @return string
     */
    protected function getTranslationPath()
    {
        return $this->getModulePath() . DIRECTORY_SEPARATOR . $this->getDashedNamespace() . DIRECTORY_SEPARATOR . 'resources/lang' . DIRECTORY_SEPARATOR;
    }

    protected function loadRoutes(): self
    {
        $routes = \File::glob($this->getRoutePath() . '*.php');
        foreach ($routes as $route) {
            $this->loadRoutesFrom($route);
        }
        return $this;
    }

    protected function getRoutePath(): string
    {
        return $this->getModulePath() . DIRECTORY_SEPARATOR . $this->getDashedNamespace() . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return $this
     */
    public function loadMigrations(): self
    {
        $this->loadMigrationsFrom($this->getMigrationPath());
        return $this;
    }

    /**
     * @return string
     */
    protected function getMigrationPath(): string
    {
        return $this->getModulePath() . DIRECTORY_SEPARATOR . $this->getDashedNamespace() . DIRECTORY_SEPARATOR . 'database/migrations' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return $this
     */
    public function loadViews(): self
    {
        $this->loadViewsFrom($this->getViewPath(), $this->getDotedNamespace());
        return $this;
    }

    /**
     * @return string
     */
    protected function getViewPath(): string
    {
        return $this->getModulePath() . DIRECTORY_SEPARATOR . $this->getDashedNamespace() . DIRECTORY_SEPARATOR . 'resources/views' . DIRECTORY_SEPARATOR;
    }

    /**
     * @param array $excepts
     * @return $this
     */
    public function loadAndMergeConfigs(array $excepts = []): self
    {
        $configFiles = \File::glob($this->getConfigPath() . '*.php');
        $configs = preg_replace('/^(?:.*)\/([\w]+(?:-[\w]+)*).php$/', '$1', $configFiles);
        foreach ($configFiles as $key => $configFile) {
            if (!in_array($configFile, $excepts)) {
                $this->mergeConfigFrom($configFile, $this->getDotedNamespace() . '.' . $configs[$key]);
                $this->mergeConfig($this->getDotedNamespace() . '.' . $configs[$key], $configs[$key]);
            }
        }
        return $this;
    }

    /**
     * @param string $fromGroup
     * @param string $toGroup
     */
    protected function mergeConfig(string $fromGroup, string $toGroup): void
    {
        $configs = \Config::get($toGroup);
        // if exists configs
        if (is_array($configs)) {
            // get all this package configs
            $formConfigs = \Config::get($fromGroup);
            foreach ($formConfigs as $key => $formConfig) {
                // if children config is array
                if (is_array($formConfig)) {
                    // if key of children config is int
                    if (is_int($key)) {
                        // push this package configs to configs
                        array_push($configs, $formConfig);
                        // else merge this package configs to configs
                    } else $configs[$key] = array_merge($configs[$key], $formConfig);
                    // overwrite this config as this package config
                } else $configs[$key] = $formConfig;
            }
            // overwrite all configs
            \Config::offsetSet($toGroup, $configs);
            // else not exists configs and set all this package configs
        } else \Config::offsetSet($toGroup, \Config::get($fromGroup));
    }

    /**
     * @return string
     */
    protected function getConfigPath(): string
    {
        return $this->getModulePath() . DIRECTORY_SEPARATOR . $this->getDashedNamespace() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string|null
     */
    public function getModulePath()
    {
        return $this->modulePath ?? module_path();
    }

    /**
     * @return string
     */
    protected function getDotedNamespace(): string
    {
        return str_replace('/', '.', $this->namespace);
    }

    /**
     * @return string
     */
    protected function getDashedNamespace(): string
    {
        return $this->namespace;
    }
}
