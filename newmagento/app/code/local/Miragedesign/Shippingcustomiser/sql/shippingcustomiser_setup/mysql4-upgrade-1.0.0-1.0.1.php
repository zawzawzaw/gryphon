<?php
/** Author:: PG **/
$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('shippingcustomiser/customfreeshippingrate')} (
  pk int(10) unsigned NOT NULL auto_increment,
  website_id int(11) NOT NULL default '0', 
  groupname varchar(250) NOT NULL default '', 
  amount decimal(12,4) NOT NULL default '0.0000',
  PRIMARY KEY(`pk`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

   ");

$installer->endSetup();


