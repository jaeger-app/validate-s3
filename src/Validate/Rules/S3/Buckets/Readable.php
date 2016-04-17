<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./Validate/Rules/S3/Buckets/Readable.php
 */
namespace JaegerApp\Validate\Rules\S3\Buckets;

use JaegerApp\Validate\AbstractRule;
use JaegerApp\Remote;
use JaegerApp\Remote\S3 as m62S3;
use JaegerApp\Validate\Rules\Filesystem\Readable;

/**
 * Jaeger - Directory Validation Rule
 *
 * Validates that a given input is a directory
 *
 * @package Validate\Rules\S3\Buckets
 * @author Eric Lamb <eric@mithra62.com>
 */
class Readable extends AbstractRule
{

    /**
     * The Rule shortname
     * 
     * @var string
     */
    protected $name = 's3_bucket_readable';

    /**
     * The error template
     * 
     * @var string
     */
    protected $error_message = 'Your bucket doesn\'t appear to be readable...';

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\Validate\RuleInterface::validate()
     * @ignore
     *
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
            
            $region = ($params['s3_region'] ? $params['s3_region'] : '');
            $client = m62S3::getRemoteClient($params['s3_access_key'], $params['s3_secret_key'], $region);
            if ($client->doesBucketExist($params['s3_bucket'])) {
                $filesystem = new Remote(new m62S3($client, $params['s3_bucket']));
                $filesystem->getAdapter()->listContents();
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
$rule = new Readable;
\JaegerApp\Validate::addrule($rule->getName(), array($rule, 'validate'), $rule->getErrorMessage());