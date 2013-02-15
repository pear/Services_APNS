<?php

require '../Services/Apns/Client/Message.php';

$configFile = dirname(__FILE__) . '/config.php';
if (file_exists($configFile)) {
    include_once $configFile;
}

/**
 * Test class for Services_Apns_Client_Message.
 */
class Services_Apns_Client_Message_Tests extends PHPUnit_Framework_TestCase
{
    /**
     * @var Services_Apns_Client_Message
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
        
        $this->clientHandler = new Services_Apns_Client_Message();
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
     * Test dummy message sending
     */
    public function testSendDummyMessage()
    {
        $this->assertTrue(defined('DUMMY_DEVICE_TOKEN'));
        $this->assertTrue(DUMMY_DEVICE_TOKEN != "");
        
        $message = new Services_Apns_Message();
        $message->setDeviceToken(DUMMY_DEVICE_TOKEN);
        $message->setBody('Hello world!');
        
        try {
            $this->assertTrue(
                    is_array($this->clientHandler->sendMessage($message))
                );
        } catch (Services_Apns_Exception $e) {
            // Error triggered
            $this->assertTrue(false);
        }
    }

    /**
     * Test message with badge
     */
    public function testSendDummyMessageWithBadge()
    {
        $this->assertTrue(defined('DUMMY_DEVICE_TOKEN'));
        $this->assertTrue(DUMMY_DEVICE_TOKEN != "");
        
        $message = new Services_Apns_Message();
        $message->setDeviceToken(DUMMY_DEVICE_TOKEN);
        $message->setBody('Hello world!');
        $message->setBadge(5);
        
        try {
            $this->assertTrue(
                    is_array($this->clientHandler->sendMessage($message))
                );
        } catch (Services_Apns_Exception $e) {
            // Error triggered
            $this->assertTrue(false);
        }
    }
    
    /**
     * Test dummy message with sound
     * as body.
     */
    public function testSendDummyMessageWithSound()
    {
        $this->assertTrue(defined('DUMMY_DEVICE_TOKEN'));
        $this->assertTrue(DUMMY_DEVICE_TOKEN != "");
        
        $this->assertTrue(SOUND_TEST_SOUND_FILE_PATH != "");
        
        $message = new Services_Apns_Message();
        $message->setDeviceToken(DUMMY_DEVICE_TOKEN);
        $message->setBody('Hello world!');
        $message->setSoundFile(SOUND_TEST_SOUND_FILE_PATH);
        
        try {
            $this->assertTrue(
                    is_array($this->clientHandler->sendMessage($message))
            );
        } catch (Services_Apns_Exception $e) {
            // Error triggered
            $this->assertTrue(false);
        }
    }

    /**
     * Test dummy message with {@link Services_Apns_Alert}
     * as body.
     */
    public function testSendDummyMessageWithAlertAsBody()
    {
        $this->assertTrue(defined('DUMMY_DEVICE_TOKEN'));
        $this->assertTrue(DUMMY_DEVICE_TOKEN != "");
    
        $message = new Services_Apns_Message();
        $message->setDeviceToken(DUMMY_DEVICE_TOKEN);
         
        $alert = new Services_Apns_Alert('This is a sample alert text.');
         
        $message->setBody($alert);
    
        try {
            $this->assertTrue(
                    is_array($this->clientHandler->sendMessage($message))
            );
        } catch (Services_Apns_Exception $e) {
            // Error triggered
            $this->assertTrue(false);
        }
    }

    /**
     * Test dummy message with {@link Services_Apns_Alert}
     * as body and using localized key.
     */
    public function testSendDummyMessageWithAlertAsBodyWithLocalizedTitle()
    {
        $this->assertTrue(defined('DUMMY_DEVICE_TOKEN'));
        $this->assertTrue(DUMMY_DEVICE_TOKEN != "");
    
        $message = new Services_Apns_Message();
        $message->setDeviceToken(DUMMY_DEVICE_TOKEN);
    
        $alert = new Services_Apns_Alert(null, ALERT_LOCALIZED_KEY_TEST_KEY_NAME);
        $message->setBody($alert);
    
        try {
            $this->assertTrue(
                    is_array($this->clientHandler->sendMessage($message))
            );
        } catch (Services_Apns_Exception $e) {
            // Error triggered
            $this->assertTrue(false);
        }
    }
    
    /**
     * Test dummy message with {@link Services_Apns_Alert}
     * as body and action button.
     */
    public function testSendDummyMessageWithAlertAsBodyAndActionButton()
    {
        $this->assertTrue(defined('DUMMY_DEVICE_TOKEN'));
        $this->assertTrue(DUMMY_DEVICE_TOKEN != "");
    
        $message = new Services_Apns_Message();
        $message->setDeviceToken(DUMMY_DEVICE_TOKEN);
    
        $alert = new Services_Apns_Alert('This is a sample alert text.');
        $alert->setActionLocalizedKey('Click Here');
        $message->setBody($alert);
    
        try {
            $this->assertTrue(
                    is_array($this->clientHandler->sendMessage($message))
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