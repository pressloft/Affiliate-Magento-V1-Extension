<?php

class PressLoft_Affiliate_Model_Mysql4_Schedule_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Init model
     */
    public function _construct()
    {
        $this->_init('affiliate/schedule');
    }

    /**
     * @return array
     */
    public function getItemsForSendData()
    {
        $collection = $this->addFieldToSelect('*');

        $collection->join(
            'sales/order',
            'order_id=`sales/order`.entity_id AND `sales/order`.base_total_due = 0.0000',
            array()
        );

        $collection->getSelect()
            ->where(
                'main_table.status IN (?)',
                array(PressLoft_Affiliate_Model_Schedule::STATUS_NEW, PressLoft_Affiliate_Model_Schedule::STATUS_ERROR)
            )
            ->where(
                'main_table.failures_num <= ?',
                PressLoft_Affiliate_Model_Schedule::MAX_FAILURES_NUM
            );
        $collection->load();

        $ids = $collection->getAllIds();
        $this->getResource()->tryLockItems($ids);

        return $this->addOrderToItems($collection);
    }

    /**
     * @param $scheduleCollection
     * @return mixed
     */
    private function addOrderToItems($scheduleCollection)
    {
        $orderIds = $scheduleCollection->getColumnValues('order_id');

        if (empty($orderIds)) {
            return $scheduleCollection;
        }

        $orderCollection = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('entity_id', ['in' => $orderIds]);

        foreach ($scheduleCollection as $schedule) {
            $order = $orderCollection->getItemById($schedule->getOrderId());
            $schedule->setOrder($order);
        }

        return $scheduleCollection;
    }
}
