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
     * @throws \LogicException          When the property isn't found
     */
    protected function sgComment($property)
    {
        try {
            $reflection = new \ReflectionProperty(__CLASS__, $property);

            return $reflection->getDocComment();
        } catch (\ReflectionException $e) {
            throw new \LogicException('Property does not exist', 0, $e);
        }
    }

    /**
     * Reads a tag from a property comment
     *
     * @param string    $comment        Property comment
     * @param string    $tag            Tag
     * @param string    $default        Default tag value (optional)
     * @return boolean|string           False if not found, or string/true when found
     */
    protected function sgTag($comment, $tag, $default = true)
    {
        $parameters = array();
        if (!preg_match('/\\s@' . $tag . '(\\s[\\w\\\\]+)?\\s/', $comment, $parameters)) {
            return false;
        } elseif (count($parameters) == 2) {
            return trim($parameters[1]);
        } else {
            return $default;
        }
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
        $comment = $this->sgComment($property);
        $set = $this->sgTag($comment, 'set');
        $var = $this->sgTag($comment, 'var');

        if ($set === true) {
            $this->$property = $var ? $this->sgFilter($value, $var) : $value;

            return $this;
        } elseif (is_string($set)) {
            return $this->$set($value);
        } else {
            throw new \LogicException('Property does not allow set operation');
        }
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
        $get = $this->sgTag($this->sgComment($property), 'get');

        if ($get === true) {
            return $this->$property;
        } elseif (is_string($get)) {
            return $this->$get();
        } else {
            throw new \LogicException('Property does not allow get operation');
        }
    }

}
