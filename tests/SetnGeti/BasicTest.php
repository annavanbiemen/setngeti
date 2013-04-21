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
class BasicTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test subject fixture
     *
     * @var BasicSubject
     */
    private $subject;

    /**
     * Loads the fixture
     */
    public function setUp()
    {
        $this->subject = new BasicSubject();
    }

    /**
     * Unloads the fixture
     */
    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * Tests comment reading under normal circumstances
     */
    public function testComment()
    {
        $this->assertSame('/** @set @get */', $this->subject->sgComment('lastname'));
    }

    /**
     * Tests comment reading for a property without documentation
     */
    public function testCommentUndocumented()
    {
        $this->setExpectedException('LogicException');
        $this->subject->sgGet('undocumented');
    }

    /**
     * Tests tag interpretation inside comments
     */
    public function testTag()
    {
        $this->assertTrue($this->subject->sgTag("/** @set @get */", 'get'));
        $this->assertFalse($this->subject->sgTag("/** @set @get */", 'var'));
        $this->assertSame('array', $this->subject->sgTag("/** @set @get @var array */", 'var'));
    }

    /**
     * Tests the getter under normal circumstances
     */
    public function testGet()
    {
        $this->assertSame(123, $this->subject->sgGet('id'));
        $this->assertSame('John', $this->subject->sgGet('firstname'));
        $this->assertSame('Doe', $this->subject->sgGet('lastname'));
    }

    /**
     * Tests the getter on a property without @get tag
     */
    public function testGetInaccessable()
    {
        $this->setExpectedException('LogicException');
        $this->subject->sgGet('password');
    }

    /**
     * Tests the getter on a property without documentation
     */
    public function testGetUndocumented()
    {
        $this->setExpectedException('LogicException');
        $this->subject->sgGet('undocumented');
    }

    /**
     * Tests the getter on a non-existent property
     */
    public function testGetNonExistent()
    {
        $this->setExpectedException('LogicException');
        $this->subject->sgGet('someNoneExistentProperty');
    }

    /**
     * Tests the setter under normal circumstances
     */
    public function testSet()
    {
        $result = $this->subject->sgSet('firstname', 'Alan');
        $this->assertAttributeSame('Alan', 'firstname', $this->subject);
        $this->assertSame($this->subject, $result);

        $this->subject->sgSet('lastname', 'Turing');
        $this->assertAttributeSame('Turing', 'lastname', $this->subject);

        $this->subject->sgSet('password', 'SomeNewSecret');
        $this->assertAttributeSame('SomeNewSecret', 'password', $this->subject);
    }

    /**
     * Tests the setter on a property without @set tag
     */
    public function testSetInaccessable()
    {
        $this->setExpectedException('LogicException');
        $this->subject->sgSet('id', 1234);
    }

    /**
     * Tests the setter on a property without documentation
     */
    public function testSetUndocumented()
    {
        $this->setExpectedException('LogicException');
        $this->subject->sgSet('undocumented', 'whatever');
    }

    /**
     * Tests the setter on a non-existent property
     */
    public function testSetNonExistent()
    {
        $this->setExpectedException('LogicException');
        $this->subject->sgSet('someNoneExistentProperty', 'whatever');
    }

    /**
     * Tests filtering
     *
     * @dataProvider                filterScalarData
     * @param mixed     $value      Value to be filtered
     * @param string    $type       Type to be enforced
     * @param mixed     $expected   Expected result after normalization
     */
    public function testFilterScalar($value, $type, $expected)
    {
        $this->assertSame($expected, $this->subject->sgFilter($value, $type));
    }

    /**
     * Filter testdata
     *
     * @return array                Filter testdata
     */
    public function filterScalarData()
    {
        $date = new \DateTime('now');

        //  VALUE           TYPE                EXPECTED
        return array(
            [0,             'string',           '0'],
            ['0',           'int',              0],
            [$date,         '\\DateTime',       $date],
            //[$date,         'DateTime',         $date],
            [$date,         'object',           $date],
        );
    }

}
