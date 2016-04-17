<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./Validate/Rules/S3/Connect.php
 */
namespace JaegerApp\Validate\Rules\S3;

use JaegerApp\Remote\S3 as m62S3;
use JaegerApp\Validate\AbstractRule;

/**
 * Jaeger - Directory Validation Rule
 *
 * Validates that a given input is a directory
 *
 * @package Validate\Rules\S3
 * @author Eric Lamb <eric@mithra62.com>
 */
class Connect extends AbstractRule
{

    /**
     * The Rule shortname
     * 
     * @var string
     */
    protected $name = 's3_connect';

    /**
     * The error template
     * 
     * @var string
     */
    protected $error_message = 'Can\'t connect to {field}';

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
            if (empty($params['s3_access_key']) || empty($params['s3_secret_key'])) {
                return false;
            }
            
            $region = ($params['s3_region'] ? $params['s3_region'] : '');
            $client = m62S3::getRemoteClient($params['s3_access_key'], $params['s3_secret_key'], $region);
            $client->listBuckets();
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

$rule = new Connect;
\JaegerApp\Validate::addrule($rule->getName(), array($rule, 'validate'), $rule->getErrorMessage());