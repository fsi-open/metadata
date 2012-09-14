<?php

/*
 * This file is part of the FSi Symfony Extension package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata\Driver;

use FSi\Component\Metadata\Driver\DriverInterface;
use FSi\Component\Metadata\ClassMetadataInterface;

class DriverChain implements DriverInterface
{
    protected $drivers;

    /**
     * Accepts a list of DriverInterface instances
     *
     * @param array $drivers An array of LoaderInterface instances
     *
     * @throws InvalidArgumentException If any of the drivers does not implement DriverInterface
     */
    public function __construct(array $drivers)
    {
        foreach ($drivers as $driver) {
            if (!$driver instanceof DriverInterface) {
                throw new \InvalidArgumentException(sprintf('Class %s is expected to implement DriverInterface', get_class($driver)));
            }
        }

        $this->drivers = $drivers;
    }  
    
    /**
     * {@inheritDoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $metadata)
    {
        foreach ($this->drivers as $driver) {
            $driver->loadClassMetadata($metadata);
        }
    }
}