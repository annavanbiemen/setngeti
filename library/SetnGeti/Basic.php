<?php

/**
 * SetnGeti
 *
 * Requires PHP version 5.4
 *
 * @category  Library
 * @package   SetnGeti
 * @author    Guido van Biemen <guidovanbiemen@gmail.com>
 * @copyright 2012 Guido van Biemen
 * @license   http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @link      http://github.com/guidovanbiemen/setngeti/ SetnGeti
 */

namespace SetnGeti;

/**
 * Basic SetnGeti trait
 *
 * By itself this trait won't change the accessebility of your properties. If
 * you want SetnGeti to do this for you, consider using the Methods or
 * Properties traits.
 *
 * Using this trait directly will only give you manual access to the core
 * methods of SetnGeti. You would have to hook into PHP's magic methods yourself
 * and call sgGet or sgSet to implement property access using SetnGeti.
 */
trait Basic
{

    /**
     * Reads a property comment
     *
     * @param string    $property       Property name
     * @return string                   Property comment
     * @throws \ReflectionException     When the property isn't found
     */
    protected function sgReadPropertyComment($property)
    {
        $reflection = new \ReflectionProperty(__CLASS__, $property);

        return $reflection->getDocComment();
    }

    /**
     * Ensures that a value has the specified type
     *
     * @param mixed     $value          Value to be filtered
     * @param string    $type           Type to be enforced
     * @return mixed                    Filtered value
     * @throws \InvalidArgumentException When the value cannot match the required type.
     */
    protected function sgFilter($value, $type)
    {
        // Scalar type casting
        $originalType = gettype($value);
        switch ($type) {
            case '':
            case 'mixed':
            case $originalType:
                return $value;
            case 'boolean':
            case 'bool':
            case 'integer':
            case 'int':
            case 'double':
            case 'string':
            case 'array':
            case 'object':
            case 'resource':
            case 'null':
                settype($value, $type);
                return $value;
        }

        // Object type validation
        if ($type[0] != '\\') {
            $type = '\\' . __NAMESPACE__ . '\\' . $type;
        }
        if ($originalType == 'object' && $value instanceof $type) {
            return $value;
        }

        throw new \InvalidArgumentException(sprintf('%s expected, but %s given', $type, $originalType));
    }

    /**
     * Sets a property to a specified value
     *
     * @param string    $property       Property name
     * @param mixed     $value          Property value
     * @return object                   This object instance (allows method chaining)
     * @throws \LogicException          When the property cannot be set
     */
    protected function sgSet($property, $value)
    {
        $comment = $this->sgReadPropertyComment($property);
        if (preg_match('/\\s@set\\s/', $comment) == 0) {
            throw new \LogicException('Property does not allow set operation');
        }

        $parameters = array();
        if (preg_match('/\\s@var\\s([\\w\\\\]+)\\s/', $comment, $parameters)) {
            $this->$property = $this->sgFilter($value, $parameters[1]);
        } else {
            $this->$property = $value;
        }

        return $this;
    }

    /**
     * Gets a property value
     *
     * @param string    $property       Property name
     * @return mixed                    Property value
     * @throws \LogicException          When the property cannot be red
     */
    protected function sgGet($property)
    {
        if (preg_match('/\\s@get\\s/', $this->sgReadPropertyComment($property)) == 0) {
            throw new \LogicException('Property does not allow get operation');
        }

        return $this->$property;
    }

}
