<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('shippingcustomiser/customfreeshippingrate')}
	ADD COLUMN `OrderAmountFrom` decimal(12,4) NOT NULL default '0.0000',ADD COLUMN `OrderAmountTo` decimal(12,4) NOT NULL default '0.0000',ADD COLUMN `ShippingCharge` decimal(12,4) NOT NULL default '0.0000';
	");
$installer->endSetup();