<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./Validate/Rules/S3/Buckets/Writable.php
 */
namespace JaegerApp\Validate\Rules\S3\Buckets;

use JaegerApp\Remote\S3 as m62S3;
use JaegerApp\Validate\AbstractRule;
use JaegerApp\Remote;
use JaegerApp\Remote\Local;

/**
 * mithra62 - Directory Validation Rule
 *
 * Validates that a given input is a directory
 *
 * @package Validate\Rules\S3\Buckets
 * @author Eric Lamb <eric@mithra62.com>
 */
class Writable extends AbstractRule
{

    /**
     * The shortname of the rule
     * 
     * @var string
     */
    protected $name = 's3_bucket_writable';

    /**
     * The error message template
     * 
     * @var string
     */
    protected $error_message = 'Your bucket doesn\'t appear to be writable...';

    /**
     * (non-PHPdoc)
     * 
     * @ignore
     *
     * @see \mithra62\Validate\RuleInterface::validate()
     */
    public function validate($field, $input, array $params = array())
    {
        try {
            if ($input == '' || empty($params['0'])) {
                return false;
            }
            
            $params = $params['0'];
            if (empty($params['s3_access_key']) || empty($params['s3_secret_key']) || empty($params['s3_bucket'])) {
                return false;
            }
            
            $local = new Remote(new Local(dirname($this->getTestFilePath())));
            $region = ($params['s3_region'] ? $params['s3_region'] : '');
            $client = m62S3::getRemoteClient($params['s3_access_key'], $params['s3_secret_key'], $region);
            if ($client->doesBucketExist($params['s3_bucket'])) {
                $contents = $local->read($this->test_file);
                $filesystem = new Remote(new m62S3($client, $params['s3_bucket']));
                
                if ($filesystem->has($this->test_file)) {
                    $filesystem->delete($this->test_file);
                } else {
                    if ($filesystem->write($this->test_file, $contents)) {
                        $filesystem->delete($this->test_file);
                    }
                }
                
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}

$rule = new Writable;
\JaegerApp\Validate::addrule($rule->getName(), array($rule, 'validate'), $rule->getErrorMessage());