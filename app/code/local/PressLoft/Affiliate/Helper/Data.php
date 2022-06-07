<?php

class PressLoft_Affiliate_Helper_Data extends Mage_Core_Helper_Abstract
{
    const DEFAULT_COOKIE_LIFETIME = 30;
    const CONFIG_PATH_ENABLED = 'pressloft_affiliate/general/enable';
    const CONFIG_PATH_AFFILIATE_ID = 'pressloft_affiliate/general/affiliate_id';

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)Mage::getStoreConfig(self::CONFIG_PATH_ENABLED);
    }

    /**
     * @return mixed
     */
    public function getAffiliateId()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_AFFILIATE_ID);
    }

    /**
     * @param string $token
     * @return int|null
     */
    public function getCookieExpires($token)
    {
        $apiRequest = Mage::getSingleton('affiliate/request');

        $responseData = $apiRequest->sendRequest(
            PressLoft_Affiliate_Model_Request::CHECK_TOKEN_ENDPOINT,
            PressLoft_Affiliate_Model_Request::TYPE_GET,
            ['token' => $token]
        );

        if($responseData['code'] == 200) {
            return $responseData['response']['cookiePeriod'];
        } elseif (substr((string)$responseData['code'], 0, 1) == 4) {
            return null;
        } else {
            return self::DEFAULT_COOKIE_LIFETIME;
        }
    }

    /**
     * @return mixed
     */
    public function getAffiliateHeartbeatData()
    {
        return Mage::getModel('affiliate/flag')
            ->setAffiliateFlagCode(PressLoft_Affiliate_Model_Flag::AFFILIATE_HEARTBEAT_FLAG_CODE)
            ->loadSelf()
            ->getFlagData();
    }
}
