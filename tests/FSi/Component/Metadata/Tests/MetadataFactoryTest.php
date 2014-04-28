<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
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

    public function testInvalidMetadataClass()
    {
        $this->setExpectedException('InvalidArgumentException');
        $factory = new MetadataFactory(new TestDriver(), null, null, 'FSi\Component\Metadata\Tests\TestDriver');
    }

    public function testLoadClassMetadata()
    {
        $cache   = $this->getMock('Doctrine\Common\Cache\Cache');
        $cache->expects($this->at(0))
                ->method('fetch')
                ->will($this->returnValue(false));

        $cache->expects($this->at(1))
                ->method('save')
                ->will($this->returnValue(false));

        $factory = new MetadataFactory(new TestDriver(), $cache);

        $metadata = $factory->getClassMetadata(self::ENTITYCLASS);

        $this->assertEquals($metadata->getPropertyMetadata('property0', 'test0'), 'test0');
    }

    public function testLoadParentMetadata()
    {
        $cache   = $this->getMock('Doctrine\Common\Cache\Cache');
        $cache->expects($this->any())
                ->method('fetch')
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

    public function testLoadClassMetadataWithCachePrefix()
    {
        $cache = $this->getMock('Doctrine\Common\Cache\Cache');
        $cache->expects($this->any())
                ->method('fetch')
                ->will($this->returnValue(false));

        $cache->expects($this->at(1))
                ->method('save')
                ->with(
                    $this->logicalAnd(
                        $this->stringContains('cache1'),
                        $this->stringContains(self::ENTITYCHILDCLASS)),
                    $this->isInstanceOf('FSi\Component\Metadata\ClassMetadata'),
                    0
                );

        $cache->expects($this->at(3))
                ->method('save')
                ->with(
                    $this->logicalAnd(
                        $this->stringContains('cache2'),
                        $this->stringContains(self::ENTITYCHILDCLASS)),
                    $this->isInstanceOf('FSi\Component\Metadata\ClassMetadata'),
                    0
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
