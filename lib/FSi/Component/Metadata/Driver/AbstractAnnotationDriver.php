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

use Doctrine\Common\Annotations\Reader;
use FSi\Component\Metadata\Driver\DriverInterface;

abstract class AbstractAnnotationDriver implements DriverInterface
{
    protected $reader;
    
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
}