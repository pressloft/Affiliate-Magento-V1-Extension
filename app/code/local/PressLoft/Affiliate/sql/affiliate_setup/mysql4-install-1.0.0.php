<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('affiliate/schedule'))
    ->addColumn(
        'id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'ID'
    )
    ->addColumn(
        'order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'identity' => false,
            'unsigned' => true,
            'nullable' => false,
        ), 'Order Id'
    )
    ->addColumn('token', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Affiliate Token')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Status')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
    ), 'Updated At')
    ->addColumn(
        'failures_num', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'identity' => false,
            'unsigned' => true,
            'nullable' => false,
            'default' => 0
        ), 'Failures Num'
    )
    ->addIndex(
        $installer->getIdxName(
            'affiliate/schedule',
            array('order_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('order_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addForeignKey(
        'SALES_ORDER_AFFILIATE_ORDER_ID_SALES_ORDER_ENTITY_ID',
        'order_id',
        'sales_flat_order',
        'entity_id'
    );

if (!$installer->getConnection()->isTableExists($table->getName())) {
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();