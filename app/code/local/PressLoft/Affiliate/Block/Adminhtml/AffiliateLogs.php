<?php

class PressLoft_Affiliate_Block_Adminhtml_AffiliateLogs extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * PressLoft_Affiliate_Block_Adminhtml_AffiliateLogs constructor.
     */
    public function __construct()
    {
        $this->_blockGroup = 'pressloft_affiliate';
        $this->_controller = 'adminhtml_affiliateLogs';
        $this->_headerText = $this->__('Press Loft Affiliate Logs');

        parent::__construct();
        $this->_removeButton('add');
    }
}
