<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/Validate/Rules/S3/Buckets/Exists.php
 */
namespace mithra62\Validate\Rules\S3\Buckets;

use mithra62\Remote\S3 as m62S3;
use mithra62\Validate\AbstractRule;
if (! class_exists('\\mithra62\\Validate\\Rules\\S3\\Buckets\\Exists')) {

    /**
     * mithra62 - S3 Bucket Existance Validation Rule
     *
     * Validates that a given bucket name exists in S3
     *
     * @package Validate\Rules\S3\Buckets
     * @author Eric Lamb <eric@mithra62.com>
     */
    class Exists extends AbstractRule
    {

        /**
         * The Rule shortname
         * 
         * @var string
         */
        protected $name = 's3_bucket_exists';

        /**
         * The error template
         * 
         * @var string
         */
        protected $error_message = 'Your bucket doesn\'t appear to exist...';

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
                
                $region = ($params['s3_region'] ? $params['s3_region'] : '');
                $client = m62S3::getRemoteClient($params['s3_access_key'], $params['s3_secret_key'], $region);
                if ($client->doesBucketExist($params['s3_bucket'])) {
                    return true;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
    }
}