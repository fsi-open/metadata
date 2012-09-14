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

use FSi\Component\Reflection\ReflectionClass;

class ClassMetadata implements ClassMetadataInterface
{
    /**
     * Name of the class for which the data is stored
     * 
     * @var string
     */
    protected $class;
    
    /**
     * Class metadata 
     * 
     * @var array
     */
    protected $classMetadata = array();
    
    /**
     * Metadata for properties 
     * 
     * @var array
     */
    protected $propertyMetadata = array();
    
    /**
     * Methods metadata
     * 
     * @var array
     */
    protected $methodMetadata = array();

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function setClassName($name)
    {
        $this->class = $name;
        return $this;
    }

    public function getClassName()
    {
        return $this->class;
    }

    public function getClassReflection()
    {
        return ReflectionClass::factory($this->getClassName());
    }

    /**
     * Add value for class metadata under $index 
     * 
     * @param string $index
     * @param mixed $value
     */
    public function addClassMetadata($index, $value)
    {
        $this->classMetadata[$index] = $value;
        return $this;
    }

    /**
     * Check if metadata exists.
     * 
     * @param string $index
     */
    public function hasClassMetadata($index)
    {
        return isset($this->classMetadata[$index]);        
    }

    /**
     * Get class metadata.
     * 
     * @param string $index
     */
    public function getClassMetadata($index)
    {
        if ($this->hasClassMetadata($index)) {
            return $this->classMetadata[$index];
        }

        return false;
    }

    /**
     * Add new value under $index for $property. If value already exists 
     * it will be overwritten. 
     * 
     * @param string $property
     * @param string $index
     * @param mixed $value
     * @return ClassMetadata
     */
    public function addPropertyMetadata($property, $index, $value)
    {
        if (!isset($this->propertyMetadata[$property])) {
            $this->propertyMetadata[$property] = array($index => $value);
        } else {
            $this->propertyMetadata[$property][$index] = $value;
        }

        return $this;
    }

    /**
     * Check if there is a value under $index for $property
     * 
     * @param string $property
     * @param string $index
     * @return boolean
     */
    public function hasPropertyMetadata($property, $index)
    {
        return isset($this->propertyMetadata[$property], $this->propertyMetadata[$property][$index]);
    }

    /**
     * Returns value from $index for $property
     * 
     * @param string $property
     * @param string $index
     * 
     * @return boolean - return value or fase if there is nothing under $index for $property
     */
    public function getPropertyMetadata($property, $index)
    {
        if ($this->hasPropertyMetadata($property, $index)) {
            return $this->propertyMetadata[$property][$index];
        }

        return false;
    }

    /**
     * Add new value under $index for $method. If value already exists 
     * it will be overwritten. 
     * 
     * @param string $method
     * @param string $index
     * @param mixed $value
     * @return ClassMetadata
     */
    public function addMethodMetadata($method, $index, $value)
    {
        if (!isset($this->methodMetadata[$method])) {
            $this->methodMetadata[$method] = array($index => $value);
        } else {
            $this->methodMetadata[$method][$index] = $value;
        }

        return $this;
    }

    /**
     * Check if there is a value under $index for $property
     * 
     * @param string $method
     * @param string $index
     * @return boolean
     */
    public function hasMethodMetadata($method, $index)
    {
        return isset($this->methodMetadata[$method], $this->methodMetadata[$method][$index]);
    }

    /**
     * Returns value from $index for $method
     * 
     * @param string $method
     * @param string $index
     * 
     * @return boolean - return value or fase if there is nothing under $index for $property
     */
    public function getMethodMetadata($method, $index)
    {
        if ($this->hasMethodMetadata($method, $index)) {
            return $this->methodMetadata[$method][$index];
        }

        return false;
    }
}