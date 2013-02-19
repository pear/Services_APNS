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
 * LICENSE:
 *
 * Copyright (c) 2013, Yahav Gindi Bar; Pear Technology Investments, Ltd.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  * Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  * Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the distribution.
 *  * Neither the name of the PHP_LexerGenerator nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 * OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      https://github.com/YahavGB/Services_APNS
 */

// {{{ Load the exceptions class

/**
 * Load the APNS service exceptions class
 */
require_once 'Services/Apns/Exception.php';

// }}}

// {{{ Services_Apns_Client

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
 * @link      https://github.com/YahavGB/Services_APNS
 */
abstract class Services_Apns_Client
{	
    // {{{ constants

    /**
    * Constat used with {@link Services_Apns_Client::connect}.
    * Used to specify to use sandbox as the environment.
    * @var int
    */
    const ENV_SANDBOX = 1;
    
    /**
     * Constat used with {@link Services_Apns_Client::connect}.
     * Used to specify to use production as the environment.
     * @var int
     */
    const ENV_PRODUCTION = 2;
    
    /**
     * Specifiying the connection socket default timeout
     * @var int
     */
    const SOCKET_DEFAULT_TIMEOUT = 30;
    
    /**
     * The number of bytes to read
     * each time from the stream.
     * @var int
     */
    const SOCKET_READ_BYTES = 8192;
    
    // }}}
    
	// {{{ Properties
    
    /**
    * The default environment
    * @var int
    */
    protected $defaultEnvironment = self::ENV_PRODUCTION;
    
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
    
    // }}}
    
    // {{{ Abstract methods

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
    
    // }}}
    
    // {{{ dtor
    
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
    
    // }}}
    
    // {{{ connect()
    
    /**
    * Open the connection to Apple Push Notifications service
    * 
    * @param int $environment The environment value
    * 
    * @return Services_Apns_Client
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
        
        if ($environment != self::ENV_SANDBOX
            && $environment != self::ENV_PRODUCTION
        ) {
            throw new Services_Apns_Exception(
                'The $environment value must be '
                . 'Services_Apns_Client::ENV_SANDBOX or '
                . 'Services_Apns_Client::ENV_PRODUCTION.'
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
        
        if ($environment == self::ENV_SANDBOX) {
            $this->initConnection($this->getApnsSandboxUri(), $sslOptions);
        } else {
            $this->initConnection($this->getApnsProductionUri(), $sslOptions);
        }
        
        $this->isConnected = true;
        return $this;
    }
    
    // }}}
    
    // {{{ close()
    
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
    
    // }}}
    
    // {{{ initConnection($gatewayAddress, $sslData)
    
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
            $timeout = self::SOCKET_DEFAULT_TIMEOUT;
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
    
    // }}}
    
    // {{{ read()
    
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
    
    // }}}
    
    // {{{ write($data)

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
    
    // }}}
    
    // {{{ Getters & Setters
    
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
        
        if ($value != self::ENV_SANDBOX
            && $value != self::ENV_PRODUCTION
        ) {
            throw new Services_Apns_Exception(
                'The $environment value must be '
                . 'Services_Apns_Client::ENV_SANDBOX or '
                . 'Services_Apns_Client::ENV_PRODUCTION.'
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
    
    // }}}
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
