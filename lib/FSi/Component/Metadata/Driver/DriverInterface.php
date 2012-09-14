<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata\Driver;

use FSi\Component\Metadata\ClassMetadataInterface;

interface DriverInterface
{
    /**
     * Load metadata into object.
     * 
     * @param ClassMetadataInterface $metadata
     */
    public function loadClassMetadata(ClassMetadataInterface $metadata);
}