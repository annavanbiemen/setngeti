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
 * Testing subject with Basic SetnGeti methods exposed
 */
class BasicSubject
{

    /**
     * Use SetnGeti Basic with its internals exposed
     */
    use Basic {
        sgReadPropertyComment as public;
        sgFilter as public;
        sgGet as public;
        sgSet as public;
    }

    /**
     * Use the Subject trait
     */
    use Subject;

}
