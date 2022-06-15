<?php

class PressLoft_Affiliate_Model_Mysql4_Schedule extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Init model id field
     */
    public function _construct()
    {
        $this->_init('affiliate/schedule', 'id');
    }

    /**
     * @param array $data
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function saveAffiliateSchedule($data)
    {
        $adapter = $this->_getWriteAdapter();

        try {
            $adapter->insertOnDuplicate($this->getMainTable(), $data);
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::throwException(Mage::helper('sales')->__('An error occurred while saving the Affiliate Schedule'));
        }

        return $this;
    }

    /**
     * @param array $ids
     * @return void
     * @throws Zend_Db_Adapter_Exception
     */
    public function tryLockItems(array $ids)
    {
        $adapter = $this->_getWriteAdapter();

        $adapter->update(
            $this->getMainTable(),
            ['status' => PressLoft_Affiliate_Model_Schedule::STATUS_PENDING],
            ['id IN (?)' => $ids]
        );
    }
}
