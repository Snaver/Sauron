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
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/create_domains_table.php');

        $this->publishes([
            __DIR__.'/../config/sauron.php' => config_path('sauron.php'),
        ]);

        $this->app->Register('GrahamCampbell\Bitbucket\BitbucketServiceProvider');
        $this->app->Register('GrahamCampbell\GitHub\GitHubServiceProvider');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            Console\Checks::class,
            Console\GistHelper::class
        ]);

        // Load the config file and merge it with the user's (should it get published)
        $this->mergeConfigFrom( __DIR__.'/../config/sauron.php', 'sauron');
    }
}