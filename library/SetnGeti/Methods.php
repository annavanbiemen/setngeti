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
 * Add 'use \SetnGeti\Methods;' to your class and @get and @set tags to your
 * property docblocks to add getters and setters for those properties.
 *
 * @method mixed    get<property>() get<property>()             Gets a property value
 * @method object   set<property>() set<property(mixed value)   Sets a property value and returns this object instance
 */
trait Methods
{

    /**
     * Use the Basic SetnGeti trait
     */
    use Basic;

    /**
     * Implements the getter and setter method API
     *
     * @param string    $method         Method
     * @param array     $arguments      Arguments
     * @return mixed                    Getter or setter return value
     * @throws \BadMethodCallException  When the method is not a getter or setter
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

        throw new \BadMethodCallException('Method does not exist');
    }

}
