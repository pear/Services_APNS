<?php

/**
 * Simple alert entity implementation for
 * Apple Push Notification Service (APNS) gateway 
 *
 * This file contains simple alert implementation
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

// {{{ Services_Apns_Alert

/**
 * Alert entity that can be used as a message body
 * to query Apple Push Notifications Service.
 * 
 * An alert is a plain message. However, you can use an alert to
 * specify a localization key from your plist file for localization.
 * 
 * In addition, you can send extra arguments,
 * in case you've used them in the localization strings.
 *
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version   Release: 0.1.0
 * @link      https://github.com/YahavGB/Services_APNS
 */
class Services_Apns_Alert
{
    /// {{{ Properties
    
    /**
    * The alert body
    * @var string|null
    */
    protected $body = null;

    /**
    * The action localized key
    * @var string|null
    */
    protected $bodyLocalizedKey = null;
    
    /**
    * Additional arguments for body localization
    * @var array
    */
    protected $bodyLocalizedArgs = array();
    
    /**
    * The action localized key
    * @var string|null
    */
    protected $actionLocalizedKey = null;

    /**
    * Path to a launch image to use
    * @var string|null
    */
    protected $launchImage = null;
    
    /// }}}
    
    /// {{{ ctor
    
    /**
    * Construct a new Alert message
    * 
    * @param string|null $body               The message body.
    * @param string|null $localizedBodyKey   The body localized key
    * @param array       $bodyLocalizedArgs  The body localized arguments
    * @param string|null $actionLocalizedKey The action localized key
    * @param string|null $launchImage        A launch image to display
    */
    public function __construct($body, $localizedBodyKey = null,
        array $bodyLocalizedArgs = array(), $actionLocalizedKey = null,
        $launchImage = null
    ) {
        $this->setBody($body);
        
        $this->setBodyLocalizedKey($localizedBodyKey);
        
        $this->setBodyLocalizationArgs($bodyLocalizedArgs);
        
        $this->setActionLocalizedKey($actionLocalizedKey);
        
        $this->setLaunchImage($launchImage);
    }
    
    /// }}}
    
    /// {{{ Getters & Setters
    
    /**
    * Set the alert body
    * 
    * @param string $value The body value to assign
    * 
    * @return Services_Apns_Message_Alert
    */
    public function setBody($value)
    {
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
    * Set the alert action localized key
    * 
    * @param string|null $value The localized action key string
    * 
    * @return Services_Apns_Message_Alert
    */
    public function setActionLocalizedKey($value)
    {
        if (!is_null($value) && !is_string($value)) {
            throw new Services_Apns_Exception(
                'The value must be null or a string type.'
            );
        }
        
        $this->actionLocalizedKey = $value;
        return $this;
    }
    
    /**
    * Gets the action localized key
    * 
    * @return string|null
    */
    public function getActionLocalizedKey()
    {
        return $this->actionLocalizedKey;
    }

    /**
    * Set the alert body localized key
    * 
    * @param string|null $value The alert body localized key
    * 
    * @return Services_Apns_Message_Alert
    */
    public function setBodyLocalizedKey($value)
    {
        if (!is_null($value) && !is_string($value)) {
            throw new Services_Apns_Exception(
                'The value must be null or a string type.'
            );
        }
        
        $this->bodyLocalizedKey = $value;
        return $this;
    }
    
    /**
    * Gets the body localized key
    *
    * @return string|null
    */
    public function getBodyLocalizedKey()
    {
        return $this->bodyLocalizedKey;
    }

    /**
    * Set the alert launch image to use
    * in case the user requested to open the App.
    * 
    * @param string|null $value The launch image file path
    * 
    * @return Services_Apns_Message_Alert
    */
    public function setLaunchImage($value)
    {
        if (!is_null($value) && !is_string($value)) {
            throw new Services_Apns_Exception(
                'The value must be null or a string type.'
            );
        }
        
        $this->launchImage = $value;
        return $this;
    }
    
    /**
    * Gets the launch image
    * 
    * @return string|null
    */
    public function getLaunchImage()
    {
        return $this->launchImage;
    }
    
    /**
    * Set the alert body localization args
    * 
    * @param array $args The alert body localized message extra args
    * 
    * @return Services_Apns_Message_Alert
    */
    public function setBodyLocalizationArgs(array $args)
    {
        $args = array_values($args);
        foreach ($args as $arg) {
            if (!is_scalar($arg)) {
                throw new Services_Apns_Exception(
                    'Each argument value must be a scalar type.'
                );
            }
        }
        
        $this->bodyLocalizedArgs = $args;
        return $this;
    }
    
    /**
    * Gets the body localization args
    * 
    * @return array
    */
    public function getBodyLocalizationArgs()
    {
        return $this->bodyLocalizedArgs;
    }
    
    /// }}}
    
    /// {{{ getPayload()
    
    /**
    * Get the alert message data payload as array
    * 
    * @return array
    */
    public function getPayload()
    {
        $payload = array();
        
        if (!is_null($this->actionLocalizedKey)) {
            $payload['loc_key'] = $this->actionLocalizedKey;
        }
        
        if (!is_null($this->bodyLocalizedKey)) {
            $payload['loc_key'] = $this->bodyLocalizedKey;
        } else if (!is_null($this->body)) {
            $payload['body'] = trim($this->body);
        }
        
        if (!is_null($this->launchImage)) {
            $payload['launch_image'] = $this->launchImage;
        }
        
        if (count($this->bodyLocalizedArgs) > 0) {
            $payload['loc_args'] = $this->bodyLocalizedArgs;
        }
        
        // If we didn't got any special argument, and we're not localizing this value
        // we don't need an nested array and can just return the plain raw value
        if (count($payload) === 1
            && !isset($payload['loc_key'])
        ) {
            return $payload['body'];
        }
        
        return $payload;
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
