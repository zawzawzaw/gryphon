<?php

$installer = $this;
$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS  {$installer->getTable('subscriptions')} (
  `customersubscription_id` int(11) unsigned NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `location` varchar(25) NOT NULL,
  `subscription_period` int NOT NULL,
  `subscription_category` varchar(100) NOT NULL,
  `start_date` date NULL,
  `end_date` date NULL,
  PRIMARY KEY (`customersubscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();
