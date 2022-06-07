<?php

class PressLoft_Affiliate_Model_Schedule extends Mage_Core_Model_Abstract
{
    /**
     * Statuses
     */
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_MISSED = 'missed';
    const STATUS_ERROR = 'error';

    /**
     * Maximum number of errors
     */
    const MAX_FAILURES_NUM = 3;

    /**
     * Init resource model
     */
    public function _construct()
    {
        $this->_init('affiliate/schedule');
    }
}
