<?php
namespace Phactory\Sql\DbUtil;

/**
 * @author Konstantin G Romanov
 */
class PgsqlUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateArrayLiteral_numericArray_matchedExpected()
    {
        $expected = '{0,1,2,3}';
        $literal = PgsqlUtil::createArrayLiteral(array(0, 1, 2, 3), 'integer');
        $this->assertEquals($expected, $literal);
    }

    public function testCreateArrayLiteral_stringArray_matchedExpected()
    {
        $expected = "{'a','b','c'}";
        $literal = PgsqlUtil::createArrayLiteral(array('a', 'b', 'c'));
        $this->assertEquals($expected, $literal);
    }

    public function testCreateArrayLiteral_stringMatrix_matchedExpected()
    {
        $expected = "{{'a'},{'b'},{'c'}}";
        $literal = PgsqlUtil::createArrayLiteral(array(array('a'), array('b'), array('c')));
        $this->assertEquals($expected, $literal);
    }

    public function testCreateArrayLiteral_integerMatrix_matchedExpected()
    {
        $expected = "{{0},{2},{1}}";
        $literal = PgsqlUtil::createArrayLiteral(array(array(0), array(2), array(1)), 'integer');
        $this->assertEquals($expected, $literal);
    }

    public function testCreateArrayLiteral_stringAndIntArray_allQuoted()
    {
        $expected = "{'a','b','c','1'}";
        $literal = PgsqlUtil::createArrayLiteral(array('a', 'b', 'c', 1));
        $this->assertEquals($expected, $literal);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateArrayLiteral_unsupportedType_exception()
    {
        PgsqlUtil::createArrayLiteral(array('a', 'b', 'c'), 'blah');
    }

    public function testCreateArrayLiteral_emptyArrayStringType_matchedExpected()
    {
        $expected = '{}';
        $literal = PgsqlUtil::createArrayLiteral(array());
        $this->assertEquals($expected, $literal);
    }

    public function testCreateArrayLiteral_emptyArrayIntegerType_matchedExpected()
    {
        $expected = '{}';
        $literal = PgsqlUtil::createArrayLiteral(array(), 'integer');
        $this->assertEquals($expected, $literal);
    }
}
