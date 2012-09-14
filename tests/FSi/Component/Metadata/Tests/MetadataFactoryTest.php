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
        $cache   = $this->getMock('FSi\Component\Cache\CacheInterface', 
                array('getItem', 'setItem', 'hasItem', 'removeItem', 'addItem', 'clear', 'clearNamespace'));
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
        $cache   = $this->getMock('FSi\Component\Cache\CacheInterface', 
                array('getItem', 'setItem', 'hasItem', 'removeItem', 'addItem', 'clear', 'clearNamespace'));
        $cache->expects($this->any())
                ->method('getItem')
                ->will($this->returnValue(false));

        $factory = new MetadataFactory(new TestDriver(), $cache);

        $metadata = $factory->getClassMetadata(self::ENTITYCHILDCLASS);

        $this->assertEquals($metadata->getPropertyMetadata('property0', 'test0'), 'test0');
        $this->assertEquals($metadata->getPropertyMetadata('property1', 'test1'), 'test1');

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
