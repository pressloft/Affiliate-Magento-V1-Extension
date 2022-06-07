<?php

class PressLoft_Affiliate_Model_Cron_Observer
{
    /**
     * Fields name for params
     */
    const TOKEN = 'token';
    const AFFILIATE_ID = 'affiliate_id';
    const ORDER_ID = 'order_id';
    const ORDER_DETAILS = 'order_details';
    const CREATED_AT = 'order_datetime';
    const ORDER_CURRENCY = 'order_currency';
    const ORDER_SUBTOTAL = 'order_subtotal';
    const DISCOUNT = 'discount';
    const TAX = 'tax';
    const POSTAGE = 'postage';
    const ORDER_TOTAL = 'order_total';
    const ORDER_ITEMS = 'order_lines';
    const ORDER_ITEM_PREFIX = 'order_line_';
    const SKU = 'sku';
    const PRODUCT_NAME = 'product_name';
    const QTY = 'quantity';
    const PRICE = 'unit_price';
    const ROW_TOTAL = 'line_total';

    /**
     * @var int|null
     */
    protected $affiliateId;

    /**
     * @var Mage_Core_Helper_Abstract|null
     */
    protected $helper;

    /**
     * @var PressLoft_Affiliate_Model_Mysql4_Schedule
     */
    protected $resourceSchedule;

    /**
     * PressLoft_Affiliate_Model_Cron_Observer constructor.
     */
    public function __construct()
    {
        $this->helper = Mage::helper('pressloft_affiliate');
        $this->resourceSchedule = Mage::getResourceModel('affiliate/schedule');
    }

    /**
     * Prepare and send orders data to the PressLoft server
     */
    public function sendDataToPressLoft()
    {
        if ($this->helper->isEnabled()) {
            try {
                $this->affiliateId = $this->helper->getAffiliateId();
                $this->sendData();
            } catch (Exception $e) {
                Mage::log($e->getMessage(), null, 'affiliate.log', true);
            }
        }
    }

    /**
     * Verify and save the last attempt to verify that the affiliate ID on the PressLoft server is valid
     */
    public function usageCheck()
    {
        if ($this->helper->isEnabled()) {
            /** @var PressLoft_Affiliate_Model_Request $apiRequest */
            $apiRequest = Mage::getSingleton('affiliate/request');

            $response = $apiRequest->sendRequest(
                PressLoft_Affiliate_Model_Request::HEARTBEAT_API_ENDPOINT,
                PressLoft_Affiliate_Model_Request::TYPE_GET,
                ['id' => $this->helper->getAffiliateId()]
            );
            if ($response['code'] == PressLoft_Affiliate_Model_Request::SUCCESS_STATUS_CODE) {
                $data = [
                    'status' => $response['code'],
                    'time' => date('Y-m-d H:i:s')
                ];

                Mage::getModel('core/flag', array('flag_code' => 'pressloft_affiliate_heartbeat'))
                    ->loadSelf()
                    ->setFlagData($data)
                    ->save();
            }
        }
    }

    /**
     * Send orders data to the PressLoft server
     */
    protected function sendData()
    {
        /** @var PressLoft_Affiliate_Model_Mysql4_Schedule_Collection $schedule */
        $schedule = Mage::getResourceModel('affiliate/schedule_collection');
        $items = $schedule->getItemsForSendData();

        foreach ($items as $item) {
            $this->processingData($item);
        }
    }

    /**
     * Processing data and receiving a response from the API
     *
     * @param $item
     */
    private function processingData($item)
    {
        $params = [
            self::TOKEN => $item->getToken(),
            self::AFFILIATE_ID => $this->affiliateId,
            self::ORDER_ID => $item->getOrderId(),
            self::ORDER_DETAILS => [
                self::CREATED_AT => $item->getOrder()->getCreatedAt(),
                self::ORDER_CURRENCY => $item->getOrder()->getOrderCurrencyCode(),
                self::ORDER_SUBTOTAL => $item->getOrder()->getSubtotalInclTax(),
                self::DISCOUNT => $item->getOrder()->getDiscountAmount(),
                self::TAX => $item->getOrder()->getTaxAmount(),
                self::POSTAGE => $item->getOrder()->getShippingAmount(),
                self::ORDER_TOTAL => $item->getOrder()->getGrandTotal(),
                self::ORDER_ITEMS => $this->getPreparedOrderItems($item->getOrder())
            ]
        ];

        /** @var PressLoft_Affiliate_Model_Request $apiRequest */
        $apiRequest = Mage::getSingleton('affiliate/request');

        $response = $apiRequest->sendRequest(
            PressLoft_Affiliate_Model_Request::SALE_API_ENDPOINT,
            PressLoft_Affiliate_Model_Request::TYPE_POST,
            $params
        );

        if ($response['code'] == PressLoft_Affiliate_Model_Request::SUCCESS_STATUS_CODE) {
            $item->setData('status', PressLoft_Affiliate_Model_Schedule::STATUS_SUCCESS);
        } else {
            $failuresNum = $item->getFailuresNum();
            if ($item->getFailuresNum() == PressLoft_Affiliate_Model_Schedule::MAX_FAILURES_NUM) {
                $item->setData('status', PressLoft_Affiliate_Model_Schedule::STATUS_MISSED);
            } else {
                $item->setData('status', PressLoft_Affiliate_Model_Schedule::STATUS_ERROR);
            }
            $item->setData('failures_num', ++$failuresNum);
        }
        $item->setData('updated_at', null);

        $this->resourceSchedule->save($item);
    }

    /**
     * Prepare order_lines for data
     *
     * @param Mage_Sales_Model_Order $order
     * @return array
     */
    private function getPreparedOrderItems($order)
    {
        $orderItems = $order->getAllVisibleItems();
        $counter = 0;
        $itemsData = [];

        foreach ($orderItems as $orderItem) {
            $counter++;
            $itemsData[self::ORDER_ITEM_PREFIX . $counter] = [
                self::SKU => $orderItem->getSku(),
                self::PRODUCT_NAME => $orderItem->getName(),
                self::QTY => $orderItem->getQtyOrdered(),
                self::PRICE => $orderItem->getPrice(),
                self::ROW_TOTAL => $orderItem->getRowTotal()
            ];
        }
        return $itemsData;
    }
}
