<?php

class PressLoft_Affiliate_Block_Affiliate extends Mage_Core_Block_Template
{
    const TOKEN_NAME = 'pltoken';

    /**
     * @return string
     */
    public function getTokenName()
    {
        return self::TOKEN_NAME;
    }

    /**
     * @return string
     */
    public function getExpiresCookieUrl()
    {
        return $this->getUrl('affiliate/ajax/setAffiliateCookie', array('_secure' => $this->_isSecure()));
    }
}
