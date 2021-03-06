<?php

namespace Runn\Core;

use Runn\Storages\SingleValueStorageAwareInterface;
use Runn\Storages\SingleValueStorageInterface;

/**
 * Config class
 *
 * Class Config
 * @package Runn\Core
 */
class Config
    extends Std
    implements SingleValueStorageInterface, SingleValueStorageAwareInterface
{

    /**
     * @var \Runn\Storages\SingleValueStorageInterface|null $storage;
     */
    protected $storage;

    /**
     * @param \Runn\Storages\SingleValueStorageInterface|iterable|null $arg
     */
    public function __construct(/* SingleValueStorageInterface | iterable */$arg = null)
    {
        if ( (is_object($arg) && ($arg instanceof SingleValueStorageInterface)) ) {
            $this->setStorage($arg)->load();
        } else {
            parent::__construct($arg);
        }
    }

    /**
     * @param \Runn\Storages\SingleValueStorageInterface $storage
     * @return $this
     */
    public function setStorage(/*?*/SingleValueStorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return \Runn\Storages\SingleValueStorageInterface|null
     */
    public function getStorage(): /*?*/SingleValueStorageInterface
    {
        return $this->storage;
    }

    /**
     * Loads config from storage
     *
     * @return $this
     * @throws \Runn\Core\Exception
     */
    public function load()
    {
        if (empty($this->storage)) {
            throw new Exception('Empty config storage');
        }
        $storage = $this->getStorage();
        $storage->load();
        return $this->fromArray($storage->get());
    }

    /**
     * @return $this
     * @throws \Runn\Core\Exception
     */
    public function save()
    {
        if (empty($this->storage)) {
            throw new Exception('Empty config storage');
        }
        $storage = $this->getStorage();
        $storage->set($this->toArray());
        $storage->save();
        return $this;
    }

    public function get()
    {
        throw new \BadMethodCallException();
    }

    public function set($value)
    {
        throw new \BadMethodCallException();
    }

    protected function innerSet($key, $val)
    {
        if ('storage' === $key) {
            $this->__data['storage'] = $val;
            return;
        }
        parent::innerSet($key, $val);
    }

    protected function innerGet($key)
    {
        if ('storage' === $key) {
            return $this->__data['storage'] ?? null;
        }
        return parent::innerGet($key);
    }

}