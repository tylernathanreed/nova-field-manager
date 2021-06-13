<?php

namespace Reedware\NovaFieldManager;

use Illuminate\Support\ServiceProvider;
use Reedware\NovaFieldManager\Contracts\Guesser;

class NovaFieldManagerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge the nova field configuration
        $this->mergeNovaFieldConfiguration();

        // Register the resource parameter guesser
        $this->registerResourceParameterGuesser();

        // Register the nova field manager
        $this->registerNovaFieldManager();
    }

    /**
     * Merges the existing confirmation with the default nova field configuration.
     *
     * @return void
     */
    protected function mergeNovaFieldConfiguration()
    {
        // Determine the configuration path
        $configPath = $this->getDefaultConfigPath();

        // Merge the existing configuration with the default configuration
        $this->mergeConfigFrom($configPath, 'nova-fields');
    }

    /**
     * Registers the resource parameter guesser.
     *
     * @return void
     */
    protected function registerResourceParameterGuesser()
    {
        $this->app->singleton(Guesser::class, ResourceParameterGuesser::class);
    }

    /**
     * Registers the nova field manager.
     *
     * @return void
     */
    protected function registerNovaFieldManager()
    {
        // Register the nova field manager
        $this->app->singleton(NovaFieldManager::class, function($app) {
            return new NovaFieldManager($app[Guesser::class], $app['config']['nova-fields']);
        });

        // Provide a short-hand alias
        $this->app->alias(NovaFieldManager::class, 'nova-field-manager');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the nova field configuration
        $this->publishNovaFieldConfiguration();
    }

    /**
     * Publishes the nova field configuration.
     *
     * @return void
     */
    protected function publishNovaFieldConfiguration()
    {
        $this->publishes([$this->getDefaultConfigPath() => $this->getApplicationConfigPath()], 'config');
    }

    /**
     * Returns the path to the default configuration file.
     *
     * @return string
     */
    public function getDefaultConfigPath()
    {
        return __DIR__ . '/../config/nova-fields.php';
    }

    /**
     * Returns the path to the application configuration file.
     *
     * @return string
     */
    public function getApplicationConfigPath()
    {
        return $this->app->configPath('nova-fields.php');
    }

    /**
     * Returns the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [NovaFieldManager::class, 'nova-field-manager'];
    }
}
