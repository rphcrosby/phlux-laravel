<?php

namespace Phlux\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\CacheManager;
use Phlux\State\State;
use Phlux\Laravel\Store\CacheStore;
use Phlux\Middleware\LoggerMiddleware;
use Phlux\Observer\PersistenceObserver;
use Phlux\Laravel\Store\SessionStore;
use Phlux\Queue\ArrayQueue;
use Phlux\Pipeline\Pipeline;
use Phlux\Dispatcher\Dispatcher;
use Phlux\Phlux;

class PhluxProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDispatcher();
        $this->registerStore();
        $this->registerPhlux();
        $this->registerLogger();
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/phlux.php' => config_path('phlux.php'),
        ]);

        $this->bootListeners();
        $this->bootObservers();
        $this->bootPersistence();
    }

    /**
     * Boots any Phlux listeners that have been created
     *
     * @return void
     */
    protected function bootListeners()
    {
        $listeners = $this->app['config']['phlux.listeners'];

        foreach ($listeners as $listener) {
            $this->app['phlux']->listen(new $listener);
        }
    }

    /**
     * Boots any Phlux observers that have been created
     *
     * @return void
     */
    protected function bootObservers()
    {
        $observers = $this->app['config']['phlux.observers'];

        foreach ($observers as $observer) {
            $this->app['phlux']->observe(new $observer);
        }
    }

    /**
     * If specified in config, will enable persistence of state across requests
     *
     * @return void
     */
    protected function bootPersistence()
    {
        if ($this->app['config']['phlux.persistence']) {
            $this->app['phlux']->observe(
                new PersistenceObserver($this->app['phlux.store'])
            );
        }
    }

    /**
     * Register the Phlux dispatcher with Laravel
     *
     * @return void
     */
    protected function registerDispatcher()
    {
        $this->app->singleton('phlux.dispatcher', function($app)
        {
            return new Dispatcher(new ArrayQueue, new Pipeline);
        });
    }

    /**
     * Register the Phlux store with Laravel
     *
     * @return void
     */
    protected function registerStore()
    {
        $this->app->singleton('phlux.store', function($app)
        {
            $manager = new CacheManager($app);

            return new CacheStore($manager, 123);
        });
    }

    /**
     * Register Phlux with Laravel
     *
     * @return void
     */
    protected function registerPhlux()
    {
        $this->app->singleton('phlux', function($app)
        {
            $state = $app['phlux.store']->get();

            if (!$state) {
                $state = new State($app['config']['phlux.initial']);
            }

            return new Phlux($state, $app['phlux.dispatcher']);
        });
    }

    /**
     * Register a Phlux logger with Laravel
     *
     * @return void
     */
    protected function registerLogger()
    {
        $this->app['phlux']->middleware(
            new LoggerMiddleware($this->app['log'])
        );
    }
}
