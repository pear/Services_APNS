<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file contains client class used to send push
 * notifications using Apple Push Notifications Service gateway.
 *
 * This file contaions client class which allows to send
 * Notifications to iOS Based device using the
 * Apple Push Notifications Message Gateway.
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
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      https://github.com/YahavGB/Services_APNS
 */

/// {{{ Load Services_Apns_Client

/**
 * Load the abstraction client layer
 */
require_once 'Services/Apns/Client.php';

/// }}}

/// {{{ Load Services_Apns_Message

/**
 * Load the message entity
 */
require_once 'Services/Apns/Message.php';

/// }}}

// {{{ Services_Apns_Client_Message

/**
 * Client class used to query Apple Push Notifications Service with messages.
 * Messages that'll be transfferd by this class will be received on
 * the registered clients devices.
 *
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version   Release: 0.1.0
 * @link      https://github.com/YahavGB/Services_APNS
 */
class Services_Apns_Client_Message extends Services_Apns_Client
{
    /// {{{ getApnsProductionUri()
    
    /**
    * (non-PHPdoc)
    *
    * @see Services_ApnsAbstractGateway::getApnsProductionUri()
    * @return string
    */
    protected function getApnsProductionUri()
    {
        return 'ssl://gateway.push.apple.com:2195';
    }
    
    /// }}}
    
    /// {{{ getApnsSandboxUri()
    
    /**
    * (non-PHPdoc)
    * 
    * @see Services_ApnsAbstractGateway::getApnsSandboxUri()
    * @return string
    */
    protected function getApnsSandboxUri()
    {
        return 'ssl://gateway.sandbox.push.apple.com:2195';
    }
    
    /// }}}
    
    /// {{{ sendMessage($message)
    
    /**
     * Send a message to APNS gateway
     * 
     * @param Services_Apns_Message $message The message to assign
     * 
     * @return array
     */
    public function sendMessage(Services_Apns_Message $message)
    {
        $connected = $this->isConnected();
        if (!$connected) {
            $this->connect();
        }
    
        $ret = $this->write($message->getPayloadAsJson());
        if ($ret === false) {
            throw new Services_Apns_Exception(
                'Apple Push Notifications Service is not avialable.'
            );
        }
        
        $ret = unpack('Ccmd/Cerrno/Nid', $this->read());
        
        if (!$connected) {
            $this->close();
        }
        
        return $ret;
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
