<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Services_Apns_Tests_All::main');
}

require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__) . '/Message.php';
require_once dirname(__FILE__) . '/Feedback.php';

$configFile = dirname(__FILE__) . '/config.php';
if (file_exists($configFile)) {
    include_once $configFile;
}

class Services_Apns_Tests_All extends PHPUnit_Framework_TestSuite
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    protected function setUp()
    {
        // These constants must be set in order to run the tests
        if (!defined('APNS_SSL_CERTIFICATE_FILE_PATH')
            || !file_exists(APNS_SSL_CERTIFICATE_FILE_PATH)
        ) {
            $this->markTestSuiteSkipped('Credentials missing in config.php');
        }
    }

    public static function suite()
    {
        $suite = new Services_Apns_Tests_All('Services_Apns Tests');
        $suite->addTestSuite('Services_Apns_Client_Message_Tests');
        $suite->addTestSuite('Services_Apns_Client_Feedback_Tests');

        return $suite;
    }
}

// exec test suite
if (PHPUnit_MAIN_METHOD == 'Services_Apns_Tests_All::main') {
    Services_Apns_Tests_All::main();
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
*/