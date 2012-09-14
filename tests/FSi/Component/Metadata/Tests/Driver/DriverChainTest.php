<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata\Tests\Driver;

use FSi\Component\Metadata\Driver\DriverChain;
use FSi\Component\Metadata\Driver\DriverInterface;
use FSi\Component\Metadata\ClassMetadataInterface;

class DriverChainTest extends \PHPUnit_Framework_TestCase
{
    const ENTITYCLASS = 'FSi\Component\Metadata\Tests\Fixtures\Entity';

    public function testDriverChain()
    {
        $driver = new DriverChain(array(
            new TestDriverFirst(),
            new TestDriverSecond()
        ));

        $metadata = $this->getMock('FSi\Component\Metadata\ClassMetadata',
            array('addPropertyMetadata'), array('class' =>self::ENTITYCLASS)
        );

        $metadata->expects($this->exactly(2))
                    ->method('addPropertyMetadata');

        $driver->loadClassMetadata($metadata);
    }
}

class TestDriverFirst implements DriverInterface
{
    public function loadClassMetadata(ClassMetadataInterface $metadata)
    {
        $metadata->addPropertyMetadata('first', 'first', 'first');
    }
}

class TestDriverSecond implements DriverInterface
{
    public function loadClassMetadata(ClassMetadataInterface $metadata)
    {
        $metadata->addPropertyMetadata('second', 'second', 'second');
    }
}