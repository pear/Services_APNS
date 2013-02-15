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

require_once 'Services/Services_Apns/Apns/ApnsAbstractGateway.php';

/// }}}

// {{{ Services_ApnsFeedbackClient


/**
 * Client class used to query Apple Push Notifications Service feedback gateway.
 * 
 * @category  Services
 * @package   Services_Apns
 * @author    Yahav Gindi Bar <g.b.yahav@gmail.com>
 * @copyright 2013 Yahav Gindi Bar
 * @license   http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version   Release: 0.1.0
 * @link      http://github.com/yahavgb/PEAR/Services_Apns
 */
class Services_ApnsFeedbackClient extends Services_ApnsAbstractClient
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
