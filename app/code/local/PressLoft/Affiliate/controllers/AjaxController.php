<?php

class PressLoft_Affiliate_AjaxController extends Mage_Core_Controller_Front_Action
{
    /**
     * @return Mage_Core_Controller_Response_Http
     */
    public function setAffiliateCookieAction()
    {
        $result = ['success' => false, 'message' => $this->__('Affiliate cookie not added.')];
        $helper = Mage::helper('pressloft_affiliate');

        if(!$helper->isEnabled()) {
            $result['message'] = $this->__('Affiliate is disabled.');
            return $this->getResponse()->setBody(Zend_Json::encode($result));
        }

        if ($this->getRequest()->isPost() && $this->isAjax()) {
            $token = $this->getRequest()->getParam(PressLoft_Affiliate_Block_Affiliate::TOKEN_NAME);
            $expires = $helper->getCookieExpires($token);
            if ($expires) {
                $cookie = Mage::getSingleton('core/cookie');
                $cookie->set(PressLoft_Affiliate_Block_Affiliate::TOKEN_NAME, $token, $expires * 24 * 60 * 60, '/');
            }

            $result = ['success' => true, 'message' => $this->__('Affiliate cookie has been added.')];
        }

        return $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    /**
     * @return bool
     */
    protected function isAjax()
    {
        return $this->getRequest()->isXmlHttpRequest() || $this->getRequest()->getParam('isAjax');
    }
}