<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file contains client class used to
 * query Apple Push Notifications Feedback gateway.
 *
 * This file contaions client class which allows to query
 * Apple Push Notifications Feedback Service for app feedback.
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

require_once 'Services/Apns/Client.php';

/// }}}

// {{{ Services_Apns_Client_Feedback


/**
 * Client class used to query Apple Push Notifications Service feedback gateway.
 * 
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version   Release: 0.1.0
 * @link      https://github.com/YahavGB/Services_APNS
 */
class Services_Apns_Client_Feedback extends Services_Apns_Client
{
    /// {{{ getApnsProductionUri()
    
    /**
     * (non-PHPdoc)
     * 
     * @return string
     */
    protected function getApnsProductionUri()
    {
        return 'ssl://feedback.push.apple.com:2196';
    }
    
    /// }}}
    
    /// {{{ getApnsSandboxUri()
    
    /**
     * (non-PHPdoc)
     * 
     * @return string
    */
    protected function getApnsSandboxUri()
    {
        return 'ssl://feedback.sandbox.push.apple.com:2196';
    }
     
    /// }}}
    
    /// {{{ getFeedback()
    
    /**
     * Gets the feedback query data
     * 
     * @return array
     */
    public function getFeedback()
    {
        $connected = $this->isConnected();
        if (!$connected) {
            $this->connect();
        }
    
        $tokens = array();
        while (($token = $this->read(38)) !== false) {
            $tokens[] = unpack('Ntime/nlength/H*token', $token);
        }
        
        if (!$connected) {
            $this->close();
        }
        
        return $tokens;
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
