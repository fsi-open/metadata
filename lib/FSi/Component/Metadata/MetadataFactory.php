<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata;

use FSi\Component\Cache\CacheInterface;
use FSi\Component\Metadata\Driver\DriverInterface;

class MetadataFactory
{
    const METADATA_CLASS = 'FSi\Component\Metadata\ClassMetadata';

    /**
     * Driver used to read metadata.
     *
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Namespace used in cache.
     *
     * @var string
     */
    protected $cacheNamespace;
    /**
     * Name of class used to store metada
     * @var string
     */
    protected $metadataClassName;

    protected $loadedMetadata = array();

    /**
     * Create metadatafactory.
     * Sometimes it might be usefull to create own ClassMetadata, this
     *
     * @throws InvalidArgumentException
     * @param DriverInterface $driver
     * @param CacheInterface $cache
     * @param string $cacheNamespace
     * @param string $metadataClassName
     */
    public function __construct(DriverInterface $driver, $cache = null,
        $cacheNamespace = null, $metadataClassName = null)
    {
        $this->driver = $driver;
        if (isset($cache)) {
            if (!$cache instanceof CacheInterface) {
                throw new \InvalidArgumentException('Cache must implements FSi\Component\Cache\CacheInterface');
            }
            $this->cache  = $cache;
            if (isset($cacheNamespace)) {
                $this->cacheNamespace = $cacheNamespace;
            }
        }
        if (isset($metadataClassName)) {
            $metadataClassName = ltrim($metadataClassName, '\\');
            $metadataClassReflection = new \ReflectionClass($metadataClassName);
            if (!$metadataClassReflection->implementsInterface('FSi\Component\Metadata\ClassMetadataInterface')) {
                throw new \InvalidArgumentException('Metadata class must implement FSi\Component\Metadata\ClassMetadataInterface');
            }
            $this->metadataClassName = $metadataClassName;
        } else {
            $this->metadataClassName = self::METADATA_CLASS;
        }
    }

    public function getClassMetadata($class)
    {
        $class = ltrim($class, '\\');
        $metadataIndex = $class . $this->metadataClassName;

        if (isset($this->loadedMetadata[$metadataIndex])) {
            return $this->loadedMetadata[$metadataIndex];
        }

        if (isset($this->cache)) {
            if (false !== ($this->loadedMetadata[$metadataIndex] = $this->cache->getItem($metadataIndex, $this->cacheNamespace))) {
                return $this->loadedMetadata[$metadataIndex];
            }
        }

        $metadata = new $this->metadataClassName($class);

        if ($parent = $metadata->getClassReflection()->getParentClass()) {
            $metadata = $this->getClassMetadata($parent->name);
            $metadata->setClassName($class);
        }

        $this->driver->loadClassMetadata($metadata);

        if (isset($this->cache)) {
            $this->cache->setItem($metadataIndex, $metadata, 0, $this->cacheNamespace);
        }

        return $metadata;
    }
}