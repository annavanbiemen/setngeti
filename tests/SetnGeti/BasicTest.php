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
     * Account test object fixture
     *
     * @var Account
     */
    protected $account;

    /**
     * Loads the fixture
     */
    public function setUp()
    {
        $this->account = new BasicSubject();
    }

    /**
     * Unloads the fixture
     */
    public function tearDown()
    {
        unset($this->account);
    }

    /**
     * Tests the getter under normal circumstances
     */
    public function testGet()
    {
        $this->assertSame(123, $this->account->sgGet('id'));
        $this->assertSame('John', $this->account->sgGet('firstname'));
        $this->assertSame('Doe', $this->account->sgGet('lastname'));
    }

    /**
     * Tests the getter on a property without @get tag
     */
    public function testGetInaccessable()
    {
        $this->setExpectedException('LogicException');
        $this->account->sgGet('password');
    }

    /**
     * Tests the getter on a property without documentation
     */
    public function testGetUndocumented()
    {
        $this->setExpectedException('LogicException');
        $this->account->sgGet('undocumented');
    }

    /**
     * Tests the getter on a non-existent property
     */
    public function testGetNonExistent()
    {
        $this->setExpectedException('ReflectionException');
        $this->account->sgGet('someNoneExistentProperty');
    }

    /**
     * Tests the setter under normal circumstances
     */
    public function testSet()
    {
        $result = $this->account->sgSet('firstname', 'Alan');
        $this->assertAttributeSame('Alan', 'firstname', $this->account);
        $this->assertSame($this->account, $result);

        $this->account->sgSet('lastname', 'Turing');
        $this->assertAttributeSame('Turing', 'lastname', $this->account);

        $this->account->sgSet('password', 'SomeNewSecret');
        $this->assertAttributeSame('SomeNewSecret', 'password', $this->account);
    }

    /**
     * Tests the setter on a property without @set tag
     */
    public function testSetInaccessable()
    {
        $this->setExpectedException('LogicException');
        $this->account->sgSet('id', 1234);
    }

    /**
     * Tests the setter on a property without documentation
     */
    public function testSetUndocumented()
    {
        $this->setExpectedException('LogicException');
        $this->account->sgSet('undocumented', 'whatever');
    }

    /**
     * Tests the setter on a non-existent property
     */
    public function testSetNonExistent()
    {
        $this->setExpectedException('ReflectionException');
        $this->account->sgSet('someNoneExistentProperty', 'whatever');
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
        $this->assertSame($expected, $this->account->sgFilter($value, $type));
    }

    /**
     * Filter testdata
     *
     * @return array                Filter testdata
     */
    public function filterScalarData()
    {
        //  VALUE           TYPE                EXPECTED
        return array(
            [0,             'string',           '0'],
            ['0',           'int',              0],
        );
    }

}
