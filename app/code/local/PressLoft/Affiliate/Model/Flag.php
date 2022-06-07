<?php

class PressLoft_Affiliate_Model_Flag extends Mage_Core_Model_Flag
{
    const AFFILIATE_HEARTBEAT_FLAG_CODE = 'pressloft_affiliate_heartbeat';

    /**
     * @param string $code
     * @return $this
     */
    public function setAffiliateFlagCode($code)
    {
        $this->_flagCode = $code;
        return $this;
    }
}