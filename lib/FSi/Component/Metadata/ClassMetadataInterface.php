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

interface ClassMetadataInterface
{
    /**
     * Return class name. 
     */
    public function getClassName();

    /**
     * Set class name 
     * @param string $name
     */
    public function setClassName($name);

    /**
     * Return class reflection object.
     */
    public function getClassReflection();
}
