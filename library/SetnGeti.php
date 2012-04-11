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
 */
trait SetnGeti
{

    /**
     * Tests that the property exists and has the required tag in its docblock
     *
     * @param string    $property   Property name
     * @param string    $tag        Tag (without leading @)
     * @throws LogicException       When the tag isn't found in the docblock
     * @throws ReflectionException  When the property isn't found
     */
    protected function sgRequirePropertyTag($property, $tag)
    {
        $reflection = new ReflectionProperty(__CLASS__, $property);
        if (preg_match("/\\s@$tag\\s/", $reflection->getDocComment()) == 0) {
            throw new LogicException('Operation not allowed on this property');
        }
    }

    /**
     * Sets a property to a specified value
     *
     * @param string    $property   Property name
     * @param mixed     $value      Property value
     * @return object               This object instance (allows method chaining)
     */
    protected function sgSet($property, $value)
    {
        $this->sgRequirePropertyTag($property, 'set');
        $this->$property = $value;
        return $this;
    }

    /**
     * Gets a property value
     *
     * @param string    $property   Property name
     * @return mixed                Property value
     */
    protected function sgGet($property)
    {
        $this->sgRequirePropertyTag($property, 'get');
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
