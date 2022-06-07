<?php

class PressLoft_Affiliate_Block_Adminhtml_AffiliateLogs_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * PressLoft_Affiliate_Block_Adminhtml_AffiliateLogs_Grid constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('pressloft_affiliate_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return PressLoft_Affiliate_Block_Adminhtml_AffiliateLogs_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('affiliate/schedule_collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return PressLoft_Affiliate_Block_Adminhtml_AffiliateLogs_Grid
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'id'
            )
        );

        $this->addColumn('order_id',
            array(
                'header'=> $this->__('Order Id'),
                'index' => 'order_id'
            )
        );
        $this->addColumn('token',
            array(
                'header'=> $this->__('Token'),
                'index' => 'token'
            )
        );
        $this->addColumn('status',
            array(
                'header'=> $this->__('Status'),
                'index' => 'status'
            )
        );
        $this->addColumn('created_at',
            array(
                'header'=> $this->__('Created At'),
                'index' => 'created_at'
            )
        );
        $this->addColumn('updated_at',
            array(
                'header'=> $this->__('Updated At'),
                'index' => 'updated_at'
            )
        );
        $this->addColumn('failures_num',
            array(
                'header'=> $this->__('Failures Num'),
                'index' => 'failures_num'
            )
        );

        return parent::_prepareColumns();
    }
}