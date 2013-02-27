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

/**
 * SetnGeti trait
 *
 * Add 'use SetnGeti;' to your class and @get and @set tags to your property
 * docblocks to add getters and setters for those properties.
 *
 * @method mixed get<property>() get<property>() Gets a property value
 * @method object set<property>() set<property(mixed value) Sets a property value and returns this object instance
 */
trait SetnGeti
{

    /**
     * Reads a property comment
     *
     * @param string    $property   Property name
     * @return string               Property comment
     * @throws ReflectionException  When the property isn't found
     */
    protected function sgReadPropertyComment($property)
    {
        $reflection = new ReflectionProperty(__CLASS__, $property);
        return $reflection->getDocComment();
    }

    /**
     * Ensures that a value has the specified type
     *
     * @param mixed     $value      Value to be filtered
     * @param string    $type       Type to be enforced
     * @return mixed                Filtered value
     * @throws InvalidArgumentException When the value cannot match the required type.
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

        throw new InvalidArgumentException(sprintf('%s expected, but %s given', $type, $originalType));
    }

    /**
     * Sets a property to a specified value
     *
     * @param string    $property   Property name
     * @param mixed     $value      Property value
     * @return object               This object instance (allows method chaining)
     * @throws LogicException       When the property cannot be set
     */
    protected function sgSet($property, $value)
    {
        $comment = $this->sgReadPropertyComment($property);
        if (preg_match('/\\s@set\\s/', $comment) == 0) {
            throw new LogicException('Property does not allow set operation');
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
     * @param string    $property   Property name
     * @return mixed                Property value
     * @throws LogicException       When the property cannot be red
     */
    protected function sgGet($property)
    {
        if (preg_match('/\\s@get\\s/', $this->sgReadPropertyComment($property)) == 0) {
            throw new LogicException('Property does not allow get operation');
        }

        return $this->$property;
    }

    /**
     * Implements the getter and setter API
     *
     * @param string    $method     Method
     * @param array     $arguments  Arguments
     * @return mixed                Getter or setter return value
     * @throws BadMethodCallException When the method is not a getter or setter
     */
    public function __call($method, array $arguments)
    {
        $property = lcfirst(substr($method, 3));
        if (strpos($method, 'set') === 0) {
            return $this->sgSet($property, $arguments[0]);
        }
        if (strpos($method, 'get') === 0) {
            return $this->sgGet($property);
        }
        throw new BadMethodCallException('Method does not exist');
    }

}
