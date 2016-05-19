<?php

namespace Phlux\Laravel\Store;

use Phlux\Store\Store;
use Phlux\Contracts\StateInterface;
use Illuminate\Cache\CacheManager as LaravelCacheStore;

class CacheStore extends Store
{
    /**
     * The Laravel cache store
     *
     * @var Illuminate\Cache\CacheManager
     */
    protected $store;

    /**
     * Create a new cache store
     *
     * @param Illuminate\Cache\CacheManager $store
     * @param string $id
     * @return void
     */
    public function __construct(LaravelCacheStore $store, $id)
    {
        $this->setId($id);
        $this->store = $store;
    }

    /**
     * Store the state in the store
     *
     * @param Phlux\Contracts\StateInterface $state
     */
    public function set(StateInterface $state)
    {
        $this->store->forever($this->getId(), serialize($state));
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
