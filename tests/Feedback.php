<?php

require '../Services/Apns/Client/Feedback.php';

$configFile = dirname(__FILE__) . '/config.php';
if (file_exists($configFile)) {
    include_once $configFile;
}

/**
 * Test class for Services_Apns_Client_Feedback.
 */
class Services_Apns_Client_Feedback_Tests extends PHPUnit_Framework_TestCase
{
    /**
     * @var Services_Apns_Client_Feedback
     */
    private $clientHandler;
    
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
    
    protected function setup()
    {
        // These constants must be set in order to run the tests
        if (!defined('APNS_SSL_CERTIFICATE_FILE_PATH')
                || !file_exists(APNS_SSL_CERTIFICATE_FILE_PATH)
        ) {
            $this->markTestSuiteSkipped('Credentials missing in config.php');
        }
        
        $this->clientHandler = new Services_Apns_Client_Feedback();
        $this->clientHandler->setSslCertificateFilePath(APNS_SSL_CERTIFICATE_FILE_PATH);
        
        if (defined('APNS_CERTIFICATE_PASSWORD_PHRASE')) {
            $this->clientHandler->setPasswordPhrase(APNS_CERTIFICATE_PASSWORD_PHRASE);
        }
        
        if (defined('APNS_ENV')) {
            $this->clientHandler->setDefaultEnvironment(APNS_ENV);
        }
    }

    /**
     * Test if we can connect to the gateway
     */
    public function testConnectionEstablished()
    {
        $this->clientHandler->connect();
        $this->assertTrue($this->clientHandler->isConnected());
        $this->clientHandler->close();
    }

    /**
     * Test getting feedback from the gateway
     */
    public function testGetFeedback()
    {
        try {
            $this->assertTrue(
                    is_array($this->clientHandler->getFeedback())
                );
        } catch (Services_Apns_Exception $e) {
            // Error triggered
            $this->assertTrue(false);
        }
    }
}
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
*/