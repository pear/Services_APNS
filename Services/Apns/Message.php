<?php

/**
 * Simple alert message implementation for
 * Apple Push Notification Service (APNS) gateway 
 *
 * This file contains simple alert message implementation
 * that can be sent to Apple Push Notifications Service (APNS).
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
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version   SVN: $Id:$
 * @link      https://github.com/YahavGB/Services_APNS
 */


/// {{{ Load Services_Apns_Alert

/**
 * Load the alert entity
 */
require_once 'Services/Apns/Alert.php';

/// }}}

/**
 * Alert message entity that can be used to query Apple Push Notifications Service.
 *
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version   Release: 0.1.0
 * @link      https://github.com/YahavGB/Services_APNS
 */
class Services_Apns_Message
{
    /// {{{ Properties
    
    /**
    * The alert body
    * @var string|Services_Apns_Alert|null
    */
    protected $body = null;
    
    /**
    * The device token
    * @var string|null
    */
    protected $deviceToken = null;
    
    /**
    * The message expiration date
    * @var int
    */
    protected $expirationDate = null;
    
    /**
    * The badge to assign to the app
    * @var string|null
    */
    protected $badge = null;
    
    /**
    * An array contains custom arguments to add to the message
    * @var array
    */
    protected $customArgs = array();
    
    /**
    * A sound file to fire when the alert received
    * @var string|null
    */
    protected $soundFile = null;
    
    /// }}}
    
    /// {{{ Getters & Setters

    /**
    * Set the device token you wish to query
    * (the token received from the APNS registeration method in iOS SDK)
    *
    * @param string|null $value The value to assign
    *
    * @return Services_Apns_Alert
    */
    public function setDeviceToken($value)
    {
        if (!is_null($value) && !is_string($value)) {
            throw new Services_Apns_Exception(
                'The value must be null or a string type.'
            );
        }
        $this->deviceToken = $value;
        return $this;
    }
    
    /**
    * Gets the device token
    * 
    * @return string|null
    */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }
    
    /**
    * Set the alert body
    * 
    * @param string|Services_Apns_Alert|null $value The value to assign
    * 
    * @return Services_Apns_Alert
    */
    public function setBody($value)
    {
        if ($value instanceof Services_Apns_Alert) {
            $this->body = $value;
            return $this;
        }
        
        if (!is_null($value) && !is_scalar($value)) {
            throw new Services_Apns_Exception(
                'The value must be null or a scalar type.'
            );
        }
        $this->body = $value;
        return $this;
    }
    
    /**
    * Gets the alert body
    * 
    * @return string|null
    */
    public function getBody()
    {
        return $this->body;
    }
        
    /**
    * Set the message expiration date
    * 
    * @param int|null $value The value to assign
    * 
    * @return Services_Apns_Alert
    */
    public function setExpirationDate($value)
    {
        if (!is_null($value) && !is_int($value)) {
            throw new Services_Apns_Exception(
                'The value must be null or a int type.'
            );
        }
        $this->expirationDate = intval($value);
        return $this;
    }
    
    /**
    * Gets the launch image
    * 
    * @return int|null
    */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
    

    /**
    * Set the badge that'll be assigned to the app
    * 
    * @param string|null $value The value to assign
    * 
    * @return Services_Apns_Alert
    */
    public function setBadge($value)
    {
        if (!is_null($value) && !is_string($value)) {
            throw new Services_Apns_Exception(
                'The value must be null or a string type.'
            );
        }
        $this->badge = $value;
        return $this;
    }
    
    /**
    * Gets the badge
    * 
    * @return string|null
    */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
    * Set custom arguments to be forwarded to your app
    * 
    * @param array|null $args The value to assign
    * 
    * @return Services_Apns_Alert
    */
    public function setCustomArgs(array $args)
    {
        if (!is_null($args) && !is_array($args)) {
            throw new Services_Apns_Exception(
                'The given args value must be an array or a null value.'
            );
        }
        
        $this->customArgs = $args;
        return $this;
    }
    
    /**
    * Gets the message custom args
    * 
    * @return array
    */
    public function getCustomArgs()
    {
        return $this->customArgs;
    }


    /**
    * Set the sound file (in your app folder) that will
    * be fired when the message received.
    * 
    * @param string|null $value The value to assign
    * 
    * @return Services_Apns_Alert
    */
    public function setSoundFile($value)
    {
        if (!is_null($value) && !is_string($value)) {
            throw new Services_Apns_Exception(
                'The value must be null or a string type.'
            );
        }
        $this->soundFile = $value;
        return $this;
    }
    
    /**
    * Gets the sound file
    * 
    * @return string|null
    */
    public function getSoundFile()
    {
        return $this->soundFile;
    }
    
    /// }}}
    
    /// {{{ getPayload()
    
    /**
    * Gets the payload as array
    * 
    * @return array
    */
    public function getPayload()
    {
        if (is_null($this->deviceToken)) {
            throw new Services_Apns_Exception(
                'You must set the device token before '
                . 'getting the payload and querying APNS.'
            );
        }
        
        if (is_null($this->expirationDate)) {
            throw new Services_Apns_Exception(
                'You must set the expiration date '
                . 'before getting the payload and querying APNS.'
            );
        }
        
        if (is_null($this->body)) {
            throw new Services_Apns_Exception(
                'You must set the message body.'
            );
        }
        
        $payload = array(
            'aps' => array()        
        );
        
        if ($this->body instanceof Services_Apns_Alert) {
            $payload['aps']['alert'] = $this->body->getPayload();
        } else {
            if (empty($this->body)) {
                throw new Services_Apns_Exception(
                    'The body value can not be empty.'
                );
            }
            
            $payload['aps']['alert'] = $this->body;
        }
        
        // Set badge
        if (!is_null($this->badge)) {
            $payload['aps']['badge'] = $this->badge;
        }
        
        // Set sound file
        if (!is_null($this->sound)) {
            $payload['aps']['sound'] = $this->soundFile;
        }
        
        // Add custom arguments, in case we have
        if (!is_null($this->customArgs) && count($this->customArgs) > 0) {
            $payload = array_merge($this->customArgs, $payload);
        }
        
        return $payload;
    }
    
    /// }}}
    
    /// {{{ getPayloadAsJson()
    
    /**
    * Get the payload array as a compressed json string
    * 
    * @return string
    */
    public function getPayloadAsJson()
    {
        $payload = $this->getPayload();
        
        // Use mb_strlen for multi-byte characters in case it's available
        if (defined('JSON_UNESCAPED_UNICODE') && extension_loaded('mbstring')) {
            $payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
            $length = mb_strlen($length, 'UTF-8');
        } else {
            $payload = json_encode($payload);
            $length = strlen($payload);
        }
        
        return chr(0) . chr(0) . chr(32)
        . pack('H*', str_replace(' ', '', $this->deviceToken)) . chr(0)
        . chr($length) . $payload;
    }
    
    /// }}}
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
*/