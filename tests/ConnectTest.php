<?php
/**
 * mithra62 - Unit Test
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Validate/Rules/S3/ConnectTest.php
 */
namespace mithra62\tests\Validate\Rules\S3;

use mithra62\tests\TestFixture;
use mithra62\Validate;
use mithra62\Validate\Rules\S3\Connect;

/**
 * mithra62 - Valiate object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\Valiate object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class ConnectTest extends TestFixture
{

    /**
     * Tests the name of the rule
     */
    public function testName()
    {
        $dir = new Connect();
        $this->assertEquals($dir->getName(), 's3_connect');
    }

    /**
     * Tests that a file can be determined false
     */
    public function testRuleFail()
    {
        $val = new Validate();
        $val->rule('s3_connect', 'connection_field', array(
            's3_access_key' => 'fdsafdsa',
            's3_secret_key' => 'fdsafdsa'
        ))->val(array(
            'connection_field' => __FILE__
        ));
        $this->assertTrue($val->hasErrors());
    }

    /**
     * Tests that a directory can be determined true
     */
    public function testRuleSuccess()
    {
        $val = new Validate();
        $val->rule('s3_connect', 'connection_field', $this->getS3Creds())
            ->val(array(
            'connection_field' => 'Foo'
        ));
        $this->assertFALSE($val->hasErrors());
    }
}