<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata\Tests;

use FSi\Component\Metadata\ClassMetadata;

class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    const ENTITYCLASS = 'FSi\Component\Metadata\Tests\Fixtures\Entity';

    protected $metadata;

    protected function setUp()
    {
        $this->metadata = new ClassMetadata(self::ENTITYCLASS);
    }

    protected function tearDown()
    {
        $this->metadata = null;
    }

    public function testClassMetadata()
    {
        $this->metadata->addClassMetadata('test', 'testvalue');

        $this->assertTrue($this->metadata->hasClassMetadata('test'));

        $this->assertFalse($this->metadata->hasClassMetadata('testa'));

        $this->assertEquals($this->metadata->getClassMetadata('test'), 'testvalue');

        $this->assertFalse($this->metadata->getClassMetadata('testaa'));

        $expectsClassMetadata = array(
            'test' => 'testvalue'
        );

        $classMetadata = $this->metadata->getAllClassMetadata();

        $this->assertSame($classMetadata, $expectsClassMetadata);
    }

    public function testPropertyMetadata()
    {

        $this->metadata->addPropertyMetadata('property' ,'test', 'testvalue');

        $this->assertTrue($this->metadata->hasPropertyMetadata('property' ,'test'));

        $this->assertFalse($this->metadata->hasPropertyMetadata('property' ,'testa'));

        $this->assertEquals($this->metadata->getPropertyMetadata('property' ,'test'), 'testvalue');

        $this->assertFalse($this->metadata->getPropertyMetadata('property' ,'testaa'));

        $expectsPropertiesMetadata = array(
            'property' => array('test' => 'testvalue')
        );

        $propertiesMetadata = $this->metadata->getAllPropertyMetadata();

        $this->assertSame($propertiesMetadata, $expectsPropertiesMetadata);
    }

    public function testMethodMetadata()
    {

        $this->metadata->addMethodMetadata('method' ,'test', 'testvalue');

        $this->assertTrue($this->metadata->hasMethodMetadata('method' ,'test'));

        $this->assertFalse($this->metadata->hasMethodMetadata('method' ,'testa'));

        $this->assertEquals($this->metadata->getMethodMetadata('method' ,'test'), 'testvalue');

        $this->assertFalse($this->metadata->getMethodMetadata('method' ,'testaa'));

        $expectsMethodsMetadata = array(
            'method' => array('test' => 'testvalue')
        );

        $methodsMetadata = $this->metadata->getAllMethodMetadata();

        $this->assertSame($methodsMetadata, $expectsMethodsMetadata);
    }
}
