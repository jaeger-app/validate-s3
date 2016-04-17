<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./tests/ConnectTest.php
 */
namespace JaegerApp\tests;

use JaegerApp\Validate;
use JaegerApp\Validate\Rules\S3\Connect;

/**
 * Jaeger - Valiate object Unit Tests
 *
 * Contains all the unit tests for the \JaegerApp\Valiate object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class ConnectTest extends \PHPUnit_Framework_TestCase
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

    /**
     * The Amazon S3 Test Credentials
     */
    protected function getS3Creds()
    {
        return include 'data/s3creds.config.php';
    }
}