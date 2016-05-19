<?php

namespace Phlux\Laravel;

use Illuminate\Support\ServiceProvider
use Phlux\State\State;
use Phlux\Store\SessionStore;
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
            return new SessionStore($app['session.store']);
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
            // TODO: Do something here with a default state from config
            $state = new State($app['phlux.store']->get(), []);

            return new Phlux($state, $app['phlux.dispatcher']);
        });
    }
}
