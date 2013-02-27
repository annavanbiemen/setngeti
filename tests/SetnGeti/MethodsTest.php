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
 * SetnGeti Basic testcase
 */
class MethodsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Account test object fixture
     *
     * @var Account
     */
    private $subject;

    /**
     * Loads the fixture
     */
    public function setUp()
    {
        $this->subject = new MethodsSubject();
    }

    /**
     * Unloads the fixture
     */
    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * Tests the getter under normal circumstances
     */
    public function testGet()
    {
        $this->assertSame(123, $this->subject->getId());
    }

    /**
     * Tests the setter under normal circumstances
     */
    public function testSet()
    {
        $result = $this->subject->setFirstname('Jane');
        $this->assertAttributeSame('Jane', 'firstname', $this->subject);
        $this->assertSame($this->subject, $result);
    }

    /**
     * Tests the behaviour when calling a method that isn't a getter or setter
     */
    public function testBadMethod()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->subject->someNonExistingMethod();
    }

}
