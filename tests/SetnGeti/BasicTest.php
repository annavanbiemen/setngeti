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
        $this->account = new Account();
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
        $this->assertSame(123, $this->account->getId());
        $this->assertSame('John', $this->account->getFirstname());
        $this->assertSame('Doe', $this->account->getLastname());
    }

    /**
     * Tests the getter on a property without @get tag
     */
    public function testGetInaccessable()
    {
        $this->setExpectedException('LogicException');
        $this->account->getPassword();
    }

    /**
     * Tests the getter on a property without documentation
     */
    public function testGetUndocumented()
    {
        $this->setExpectedException('LogicException');
        $this->account->getUndocumented();
    }

    /**
     * Tests the getter on a non-existent property
     */
    public function testGetNonExistent()
    {
        $this->setExpectedException('ReflectionException');
        $this->account->getSomeNoneExistentProperty();
    }

    /**
     * Tests the setter under normal circumstances
     */
    public function testSet()
    {
        $result = $this->account->setFirstname('Alan');
        $this->assertAttributeSame('Alan', 'firstname', $this->account);
        $this->assertSame($this->account, $result);

        $this->account->setLastname('Turing');
        $this->assertAttributeSame('Turing', 'lastname', $this->account);

        $this->account->setPassword('SomeNewSecret');
        $this->assertAttributeSame('SomeNewSecret', 'password', $this->account);
    }

    /**
     * Tests the setter on a property without @set tag
     */
    public function testSetInaccessable()
    {
        $this->setExpectedException('LogicException');
        $this->account->setId(1234);
    }

    /**
     * Tests the setter on a property without documentation
     */
    public function testSetUndocumented()
    {
        $this->setExpectedException('LogicException');
        $this->account->setUndocumented('whatever');
    }

    /**
     * Tests the setter on a non-existent property
     */
    public function testSetNonExistent()
    {
        $this->setExpectedException('ReflectionException');
        $this->account->setSomeNoneExistentProperty('whatever');
    }

    /**
     * Tests the behaviour when calling a method that isn't a getter or setter
     */
    public function testBadMethod()
    {
        $this->setExpectedException('BadMethodCallException');
        $this->account->someNonExistingMethod();
    }

    /**
     * Tests filtering
     *
     * @dataProvider filterScalarData
     * @param mixed     $value      Value to be filtered
     * @param string    $type       Type to be enforced
     * @param mixed     $expected   Expected result after normalization
     */
    public function testFilterScalar($value, $type, $expected)
    {
        $this->assertEquals($expected, $this->invokeFilter($value, $type));
    }

    /**
     * Invokes the protected sgFilter method
     *
     * @param mixed     $value      Value to be filtered
     * @param string    $type       Type to be enforced
     * @return mixed                Filtered value
     */
    protected function invokeFilter($value, $type)
    {
        $filter = new \ReflectionMethod($this->account, 'sgFilter');
        $filter->setAccessible(true);

        return $filter->invoke($this->account, $value, $type);
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
