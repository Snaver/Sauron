<?php
/**
 * Sauron
 *
 * @author Richard <git@snaver.net>
 * @link   https://github.com/Snaver/Sauron
 */

namespace Snaver\Sauron;

use Illuminate\Support\ServiceProvider;

class SauronServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/create_domains_table.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Commands\Checks::class,
            Commands\GistHelper::class
        ]);
    }
}