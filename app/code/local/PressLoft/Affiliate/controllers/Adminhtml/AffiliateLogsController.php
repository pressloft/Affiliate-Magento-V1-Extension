<?php

class PressLoft_Affiliate_Adminhtml_AffiliateLogsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/pressloft_affiliate');
    }

    /**
     * Render Layout
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('sales/sales');
        $this->renderLayout();
    }
}