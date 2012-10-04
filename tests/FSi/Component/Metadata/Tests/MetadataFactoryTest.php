<?php
/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata\Tests;

use FSi\Component\Metadata\MetadataFactory;
use FSi\Component\Metadata\ClassMetadataInterface;
use FSi\Component\Metadata\Driver\DriverInterface;

class MetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    const ENTITYCLASS = 'FSi\Component\Metadata\Tests\Fixtures\Entity';
    const ENTITYCHILDCLASS = 'FSi\Component\Metadata\Tests\Fixtures\ChildEntity';

    public function testLoadClassMetadata()
    {
        if (!class_exists('FSi\Component\Cache\AbstractCache')) {
            $this->markTestSkipped('The FSi\Component\Metadata requires FSi\Component\Cache\CacheInterface to run testLoadClassMetadata test ');
        }

        $cache   = $this->getMock('FSi\Component\Cache\CacheInterface');
        $cache->expects($this->at(0))
                ->method('getItem')
                ->will($this->returnValue(false));

        $cache->expects($this->at(1))
                ->method('setItem')
                ->will($this->returnValue(false));

        $factory = new MetadataFactory(new TestDriver(), $cache);

        $metadata = $factory->getClassMetadata(self::ENTITYCLASS);

        $this->assertEquals($metadata->getPropertyMetadata('property0', 'test0'), 'test0');
    }

    public function testLoadParentMetadata()
    {
        if (!class_exists('FSi\Component\Cache\AbstractCache')) {
            $this->markTestSkipped('The FSi\Component\Metadata requires FSi\Component\Cache\CacheInterface to run testLoadParentMetadata test ');
        }

        $cache   = $this->getMock('FSi\Component\Cache\CacheInterface');
        $cache->expects($this->any())
                ->method('getItem')
                ->will($this->returnValue(false));

        $factory = new MetadataFactory(new TestDriver(), $cache);

        $metadata = $factory->getClassMetadata(self::ENTITYCHILDCLASS);

        $this->assertEquals($metadata->getPropertyMetadata('property0', 'test0'), 'test0');
        $this->assertEquals($metadata->getPropertyMetadata('property1', 'test1'), 'test1');

    }

    public function testLoadClassMetadataWithoutCache()
    {
        $factory = new MetadataFactory(new TestDriver());
        $metadata = $factory->getClassMetadata(self::ENTITYCLASS);
        $this->assertEquals($metadata->getPropertyMetadata('property0', 'test0'), 'test0');
    }

    public function testLoadClassMetadataWithCacheNamespace()
    {
        if (!class_exists('FSi\Component\Cache\AbstractCache')) {
            $this->markTestSkipped('The FSi\Component\Metadata requires FSi\Component\Cache\CacheInterface to run testLoadClassMetadataWithCacheNamespace test ');
        }

        $cache = $this->getMock('FSi\Component\Cache\CacheInterface');
        $cache->expects($this->any())
                ->method('getItem')
                ->will($this->returnValue(false));

        $cache->expects($this->at(2))
                ->method('setItem')
                ->with(
                    $this->stringContains(self::ENTITYCLASS),
                    $this->isInstanceOf('FSi\Component\Metadata\ClassMetadata'),
                    0,
                    'cache1'
                );

        $cache->expects($this->at(6))
                ->method('setItem')
                ->with(
                    $this->stringContains(self::ENTITYCLASS),
                    $this->isInstanceOf('FSi\Component\Metadata\ClassMetadata'),
                    0,
                    'cache2'
                );

        $factory1 = new MetadataFactory(new TestDriver(), $cache, 'cache1');
        $factory2 = new MetadataFactory(new TestDriver(), $cache, 'cache2');

        $metadata1 = $factory1->getClassMetadata(self::ENTITYCHILDCLASS);
        $metadata2 = $factory2->getClassMetadata(self::ENTITYCHILDCLASS);
    }
}

class TestDriver implements DriverInterface
{
    protected $i = 0;

    public function loadClassMetadata(ClassMetadataInterface $metadata)
    {
        $metadata->addPropertyMetadata('property' . $this->i, 'test' . $this->i, 'test' . $this->i);
        $this->i++;
    }
}
