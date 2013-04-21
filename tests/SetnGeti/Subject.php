<?php

/**
 * SetnGeti
 *
 * Requires PHP version 5.4
 *
 * @category  Tests
 * @package   SetnGeti
 * @author    Guido van Biemen <guidovanbiemen@gmail.com>
 * @copyright 2012 Guido van Biemen
 * @license   http://www.gnu.org/licenses/lgpl.html GNU LGPL
 * @link      http://github.com/guidovanbiemen/setngeti/ SetnGeti
 */

namespace SetnGeti;

/**
 * Generic testing subject trait
 */
trait Subject
{

    /**
     * Person identifier (readonly)
     *
     * @var integer
     * @get
     */
    private $id = 123;

    /**
     * First name
     *
     * @var string
     * @set
     * @get
     */
    private $firstname = 'John';

    /** @set @get */
    private $lastname = 'Doe';

    /**
     * Password (writeonly)
     *
     * @var string
     * @set
     */
    private $password = 'secret';

    private $undocumented;

    /**
     * Full name pseudo property
     *
     * @var string
     * @get getFullName
     * @set setFullName
     */
    private $fullname;

    public function getFullname()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function setFullname($fullname)
    {
        $matches = array();
        preg_match('/^(.+)\\s+(.+)$/', $fullname, $matches);
        $this->firstname = $matches[1];
        $this->lastname  = $matches[2];
    }

}
