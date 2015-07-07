<?php

$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('shippingcustomiser/customrate')} (
  pk int(10) unsigned NOT NULL auto_increment,
  website_id int(11) NOT NULL default '0',
  dest_country_id varchar(4) NOT NULL default '0',
  dest_region_id int(10) NOT NULL default '0',
  dest_zip varchar(10) NOT NULL default '',
  condition_name varchar(20) NOT NULL default '',
  condition_value decimal(12,4) NOT NULL default '0.0000',
  price decimal(12,4) NOT NULL default '0.0000',
  cost decimal(12,4) NOT NULL default '0.0000',
  delivery_time varchar(255) NOT NULL default '',
  PRIMARY KEY(`pk`),
  UNIQUE KEY `dest_country` (`website_id`,`dest_country_id`,`dest_region_id`,`dest_zip`,`condition_name`,`condition_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

   ");

$installer->endSetup();


