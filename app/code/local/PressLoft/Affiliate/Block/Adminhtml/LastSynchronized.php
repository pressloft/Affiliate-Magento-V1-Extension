<?php

class PressLoft_Affiliate_Block_Adminhtml_LastSynchronized extends Mage_Core_Block_Template
{
    /**
     * Get last synchronized time for template
     *
     * @return mixed
     */
    public function getLastSynchronizedTime()
    {
        $helper = Mage::helper('pressloft_affiliate');
        $data = $helper->getAffiliateHeartbeatData();

        if (!empty($data) && isset($data['time'])) {
            return $data['time'];
        }
        return '';
    }
}