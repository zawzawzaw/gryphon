<?php
$installer = $this;
$installer->startSetup();

//Add new column to the 'wishlist_item' table
$installer->getConnection()->addColumn(
        $this->getTable('subscriptions'), //table name
        'order_id',      //column name
        'int(11) DEFAULT 0'  //datatype definition
        );
$installer->getConnection()->addColumn(
        $this->getTable('subscriptions'), //table name
        'customer_email',      //column name
        'varchar(250) DEFAULT ""'  //datatype definition
        );
$installer->getConnection()->addColumn(
        $this->getTable('subscriptions'), //table name
        'item_options',      //column name
        'text DEFAULT ""'  //datatype definition
        );


$installer->endSetup();