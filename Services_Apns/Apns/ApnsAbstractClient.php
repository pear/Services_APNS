<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file contains an abstraction implementation
 * used to query Apple Push Notifications Service (APNS)
 *
 * This file contaions an abstraction layer
 * implementation used to query Apple Push Notifications Service.
 * 
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://github.com/yahavgb/PEAR/Services/Services_Apns
 */

// {{{ Load the exceptions class

/**
 * Load the APNS service exceptions class
 */
require_once 'Exception.php';

/// }}}

/// {{{ Constants

/**
 * The number of bytes to read
 * each time from the stream.

 * @var int
 */
define('APNS_SERVICE_SOCKET_READ_BYTES', 1024);

/**
 * Default socket timeout
 * 
 * @var int
 */
define('APNS_SERVICE_SOCKET_DEFAULT_TIMEOUT', 30);

/**
 * Constat used with {@link Services_ApnsAbstractClient::connect}.
 * Used to specify to use sandbox as the environment.
 * 
 * @var int
 */
define('APNS_SERVICE_ENV_SANDBOX', 1);

/**
 * Constat used with {@link Services_ApnsAbstractClient::connect}.
 * Used to specify to use production as the environment.
 *
 * @var int
 */
define('APNS_SERVICE_ENV_PRODUCTION', 2);

/// }}}

// {{{ Services_ApnsAbstractClient

/**
 * Short description for class
 *
 * This class provides an abstraction layer
 * implementation used to query Apple Push Notifications Service.
 * 
 * Each class extended from this abstract class
 * should implement a specific APNS gateway.
 * 
 * As I'm writing this class, there're two available gateways:
 * The messaging gateway and the feedback gateway.
 *
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 0.1.0
 * @link      http://github.com/yahavgb/PEAR/Services/Services_Apns
 */
abstract class Services_ApnsAbstractClient
{
    /// {{{ Properties
    
    /**
    * The default environment
    * @var int
    */
    protected $defaultEnvironment = APNS_SERVICE_ENV_PRODUCTION;
    
    /**
    * Is the stream socket connected
    * @var boolean
    */
    protected $isConnected = false;
    
    /**
    * The stream socket handler
    * @var resource
    */
    protected $socketHandler = null;
    
    /**
    * The file path to the authorized SSL certificate provided by Apple
    * @var string
    */
    protected $sslCertFilePath = '';
    
    /**
    * The password pharse
    * @var string
    */
    protected $passPhrase = null;
    
    /// }}}
    
    /// {{{ Abstract methods

    /**
    * Get the APNS sandbox uri to query
    *
    * @return string
    */
    protected abstract function getApnsProductionUri();
    
    /**
    * Get the APNS sandbox uri to query
    * 
    * @return string
    */
    protected abstract function getApnsSandboxUri();
    
    /// }}}
    
    /// {{{ dtor
    
    /**
    * Class destructor:
    * used to dispose the connection, in case it was opened
    * 
    * @return void
    */
    public function __destruct()
    {
        $this->close();
    }
    
    /// }}}
    
    /// {{{ connect()
    
    /**
    * Open the connection to Apple Push Notifications service
    * 
    * @param int $environment The environment value
    * 
    * @return Services_ApnsAbstractClient
    */
    public function connect($environment = null)
    {
        if ($this->isConnected()) {
            throw new Services_Apns_Exception(
                'Connection has already been opened and must be firstly closed'
            );
        }
        
        if ($environment === null) {
            $environment = $this->defaultEnvironment;
        }
        
        if ($environment != APNS_SERVICE_ENV_SANDBOX
            && $environment != APNS_SERVICE_ENV_PRODUCTION
        ) {
            throw new Services_Apns_Exception(
                'The $environment value must be '
                . 'APNS_SERVICE_ENV_PRODUCTION or APNS_SERVICE_ENV_SANDBOX.'
            );
        }
    
        if (empty($this->sslCertFilePath)) {
            throw new Services_Apns_Exception(
                'Before connecting to the service,'
                . ' you must firstly set up your authorized SSL certificate.'
            );
        }
    
        $sslOptions = array(
            'local_cert' => $this->sslCertFilePath,
        );
        
        if ($this->passPhrase !== null) {
            $sslOptions['passphrase'] = $this->passPhrase;
        }
        
        if ($environment == APNS_SERVICE_ENV_SANDBOX) {
            $this->initConnection($this->getApnsSandboxUri(), $sslOptions);
        } else {
            $this->initConnection($this->getApnsProductionUri(), $sslOptions);
        }
        
        $this->isConnected = true;
        return $this;
    }
    
    /// }}}
    
    /// {{{ close()
    
    /**
    * Close the connection to the APNS gateway
    * 
    * @return Services_ApnsAbstractGateway
    */
    public function close()
    {
        if ($this->isConnected && is_resource($this->socketHandler)) {
            fclose($this->socketHandler);
        }
        
        $this->isConnected = false;
        return $this;
    }
    
    /// }}}
    
    /// {{{ initConnection($gatewayAddress, $sslData)
    
    /**
    * Initialize the connection to the APNS gateway
    * 
    * @param string $gatewayAddress The gateway URI address
    * @param array  $sslData        Additional SSL data to assign to the stream
    * 
    * @return Services_ApnsAbstractGateway
    */
    protected function initConnection($gatewayAddress, array $sslData) 
    {
        if (($timeout = ini_get('socket_timeout')) === false) {
            $timeout = APNS_SERVICE_SOCKET_DEFAULT_TIMEOUT;
        }
        
        
        $this->socketHandler = stream_socket_client(
            $gatewayAddress,
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT,
            stream_context_create(
                array(
                    'ssl' => $sslData,
                )
            )
        );
        
        if (!$this->socketHandler) {
            throw new Services_Apns_Exception(
                'Enable to connect to Apple Push Notifications Service.'
                . PHP_EOL . $errstr . '(code: ' . $errno
                . ', gateway address: ' . $gatewayAddress . ')'
            );
        }
        
        stream_set_blocking($this->socketHandler, 0);
        stream_set_write_buffer($this->socketHandler, 0);
        return $this;
    }
    
    /// }}}
    
    /// {{{ read()
    
    /**
    * Read bytes from the APNS gateway stream
    * 
    * @return string|null
    */
    protected function read()
    {
        if (!$this->isConnected()) {
            throw new Services_Apns_Exception(
                'The connection to Apple Push Notifications Service is not opened.'
            );
        }
        
        $data = null;
        if (!feof($this->socket)) {
            $data = fread($this->socket, self::SOCKET_READ_BYTES);
        }
        return $data;
    }
    
    /// }}}
    
    /// {{{ write($data)

    /**
    * Writes the given data to the socket stream
    * 
    * @param string $data The data (payload) to write to the stream
    * 
    * @return int
    */
    protected function write($data)
    {
        if (!$this->isConnected()) {
            throw new Services_Apns_Exception(
                'The connection to Apple Push Notifications Service is not opened.'
            );
        }
        return @fwrite($this->socketHandler, $data);
    }
    
    /// }}}
    
    /// {{{ Getters & Setters
    
    /**
    * Set the file path to the authorized SSL certificate provided by Apple
    * 
    * @param string $filePath The SSL certificate file path
    * 
    * @return Services_ApnsAbstractGateway
    */
    public function setSslCertificateFilePath($filePath)
    {
        // Enable to reset the file path to a null value.
        if ($filePath === null) {
            $this->sslCertFilePath = null;
            return $this;
        }
        
        if (!is_string($filePath)) {
            throw new Services_Apns_Exception(
                'The given file path must be a string.'
            );
        }
        
        if (empty($filePath)) {
            throw new Services_Apns_Exception(
                'The given file path can not be empty.'
            );
        }
        
        if (!file_exists($filePath)) {
            throw new Services_Apns_Exception(
                'The given certificate file path does not exists.'
            );
        }
        
        $this->sslCertFilePath = $filePath;
        return $this;
    }
    
    /**
    * Get the file path to the authorized SSL certificate provided by Apple
    * 
    * @return string
    */
    public function getSslCertificateFilePath()
    {
        return $this->sslCertFilePath;
    }

    /**
    * Set the password phrase
    * 
    * @param string $phrase The password phrase
    * 
    * @return Services_ApnsAbstractGateway
    */
    public function setPasswordPhrase($phrase)
    {
        if (!is_scalar($phrase)) {
            throw new Services_Apns_Exception(
                'The given phrase must be a scalar type.'
            );
        }
        
        $this->passPhrase = $phrase;
        return $this;
    }
    
    /**
    * Gets the password phrase
    * 
    * @return string
    */
    public function getPasswordPhrase()
    {
        return $this->passPhrase;
    }
    
    /**
    * Set the default environment
    * 
    * @param int $value The default environment value
    * 
    * @return Services_ApnsAbstractGateway
    */
    public function setDefaultEnvironment($value)
    {
        if (!is_int($value)) {
            throw new Services_Apns_Exception(
                'The given value must be an int type.'
            );
        }
        
        if ($value != APNS_SERVICE_ENV_PRODUCTION
            && $value != APNS_SERVICE_ENV_SANDBOX
        ) {
            throw new Services_Apns_Exception(
                'The $environment value must be '
                . 'APNS_SERVICE_ENV_PRODUCTION or APNS_SERVICE_ENV_SANDBOX.'
            );
        }
    
        $this->defaultEnvironment = $value;
        return $this;
    }
    
    /**
    * Gets the default environment
    * 
    * @return string
    */
    public function getDefaultEnvironment()
    {
        return $this->defaultEnvironment;
    }
    
    
    /**
    * Gets the value that indicates if the stream
    * currently connected to the APNS gateway.
    * 
    * @return boolean
    */
    public function isConnected()
    {
        return $this->isConnected;
    }
    
    /// }}}
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
