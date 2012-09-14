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
     * Name of class used to store metada
     * @var string
     */
    protected $metadataClassName;

    protected $loadedMetadata = array();
    
    /**
     * Create metadatafactory. 
     * Sometimes it might be usefull to create own ClassMetadata, this  
     * 
     * @param DriverInterface $driver
     * @param CacheInterface $cache
     * @param string $metadataClassName
     */
    public function __construct(DriverInterface $driver, CacheInterface $cache,
        $metadataClassName = null)
    {
        $this->driver = $driver;
        $this->cache  = $cache;
        $this->metadataClassName = isset($metadataClassName) ? ltrim($metadataClassName, '\\') : self::METADATA_CLASS;
    }

    public function getClassMetadata($class)
    {
        $class = ltrim($class, '\\');
        $metadataIndex = $class . $this->metadataClassName;
        

        if (isset($this->loadedMetadata[$metadataIndex])) {
            return $this->loadedMetadata[$metadataIndex];
        }

        if (false !== ($this->loadedMetadata[$metadataIndex] = $this->cache->getItem($metadataIndex))) {
            return $this->loadedMetadata[$metadataIndex];
        }

        $metadata = new $this->metadataClassName($class);

        if ($parent = $metadata->getClassReflection()->getParentClass()) {
            $metadata = $this->getClassMetadata($parent->name);
            $metadata->setClassName($class);
        }

        $this->driver->loadClassMetadata($metadata);

        $this->cache->setItem($metadataIndex, $metadata);

        return $metadata;
    }
}