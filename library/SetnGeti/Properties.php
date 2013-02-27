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
 * SetnGeti Methods trait
 *
 * Add 'use \SetnGeti\Properties;' to your class and @get and @set tags to your
 * property docblocks to allow public access to those properties.
 */
trait Properties
{

    /**
     * Use the Basic SetnGeti trait
     */
    use Basic;

    /**
     * Sets a property to a specified value
     *
     * @param string    $property       Property name
     * @param mixed     $value          Property value
     * @return object                   This object instance (allows method chaining)
     * @throws \LogicException          When the property cannot be set
     */
    public function __set($property, $value)
    {
        return $this->sgSet($property, $value);
    }

    /**
     * Gets a property value
     *
     * @param string    $property       Property name
     * @return mixed                    Property value
     * @throws \LogicException          When the property cannot be red
     */
    public function __get($property)
    {
        return $this->sgGet($property);
    }

}
