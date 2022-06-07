<?php
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->addAttribute('order', 'affiliate_token', array('type'=>'varchar'));
$installer->addAttribute('quote', 'affiliate_token', array('type'=>'varchar'));

$installer->endSetup();