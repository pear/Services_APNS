<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Short description for file
 *
 * Long description for file (if any)...
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
 * @link      http://github.com/yahavgb/PEAR/Services_Apns
 */

/// {{{ Load Services_ApnsAbstractGateway

/**
 * Load the abstraction client layer
 */
require_once 'Services/Services_Apns/Apns/ApnsAbstractClient.php';

/// }}}

/// {{{ Load Services_Apns_Message

/**
 * Load the message entity
 */
require_once 'Services/Services_Apns/Apns/Message.php';

/// }}}

// {{{ Services_ApnsGateway


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
 * @link      http://github.com/yahavgb/PEAR/Services_Apns
 */
class Services_ApnsMessageClient extends Services_ApnsAbstractClient
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
