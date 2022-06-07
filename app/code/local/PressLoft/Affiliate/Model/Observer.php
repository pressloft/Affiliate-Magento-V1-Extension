<?php

class PressLoft_Affiliate_Model_Observer
{
    /**
     * If present, than save Affiliate Token to Order and remove Affiliate cookie
     *
     * @param   Varien_Event_Observer $observer
     */
    public function affiliateCreation($observer)
    {
        $helper = Mage::helper('pressloft_affiliate');

        if ($helper->isEnabled()) {
            try {
                $cookie = Mage::getSingleton('core/cookie');

                if ($plToken = $cookie->get(PressLoft_Affiliate_Block_Affiliate::TOKEN_NAME)) {
                    $order = $observer->getEvent()->getOrder();
                    $order->setAffiliateToken($plToken);

                    $affiliateSchedule = Mage::getResourceModel('affiliate/schedule');
                    $data = [
                        'order_id' => $order->getId(),
                        'token' => $plToken,
                        'status' => PressLoft_Affiliate_Model_Schedule::STATUS_NEW,
                    ];
                    $affiliateSchedule->saveAffiliateSchedule($data);

                    $cookie->delete(PressLoft_Affiliate_Block_Affiliate::TOKEN_NAME);
                }
            } catch (Exception $exception) {
                return $this;
            }
        }

        return $this;
    }
}