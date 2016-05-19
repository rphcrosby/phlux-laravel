<?php

namespace Phlux\Laravel\State;

use Phlux\Store\Store;
use Phlux\Contracts\StateInterface;
use Illuminate\Session\Store as LaravelSessionStore;

class SessionStore extends Store
{
    /**
     * The Laravel session store
     *
     * @var Illuminate\Session\Store
     */
    protected $store;

    /**
     * Create a new session store
     *
     * @param Illuminate\Session\Store $store
     * @return void
     */
    public function __construct(LaravelSessionStore $store)
    {
        $this->setId($store->getId());
        $this->store = $store;
    }

    /**
     * Store the state in the store
     *
     * @param Phlux\Contracts\StateInterface $state
     */
    public function set(StateInterface $state)
    {
        $this->store->set($this->getId(), serialize($state));
    }

    /**
     * Retrieve the state from the store
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function get()
    {
        return unserialize(
            $this->store->get($this->getId())
        );
    }
}
