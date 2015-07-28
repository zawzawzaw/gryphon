<?php

#$installer = $this;
$installer = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');
$installer->startSetup();


////$setup = Mage::getResourceModel('customer_address/setup', 'default_setup');
try {

    $installer->addAttribute('customer', 'isactivated', array(
        'type' => 'int',
        'input' => 'select',
        'label' => 'Is Activated',
        'sort_order' => 100,
        'source' => 'eav/entity_attribute_source_boolean',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
        'default' => 1,
        'option' => array(
            'values' =>
            array(
                0 => 'Yes',
                1 => 'No'
            )
        ),
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'isactivated')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'companyname', array(
        'label' => 'Company Name',
        'type' => 'varchar',
        'sort_order' => 101,
        'input' => 'text',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'companyname')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();


    $installer->addAttribute('customer', 'registrationnumber', array(
        'label' => 'Business Registration Number',
        'type' => 'varchar',
        'input' => 'text',
        'sort_order' => 102,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'registrationnumber')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();



    $installer->addAttribute('customer', 'registration_date_day', array(
        'type' => 'int',
        'input' => 'select',
        'sort_order' => 103,
        'label' => 'Bussiness Registration Day',
        'source' => 'eav/entity_attribute_source_table',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
        'option' => array(
            'values' =>
            array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31)
        ),
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'registration_date_day')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();


    $installer->addAttribute('customer', 'registration_date_month', array(
        'type' => 'int',
        'input' => 'select',
        'sort_order' => 104,
        'label' => 'Bussiness Registration Month',
        'source' => 'eav/entity_attribute_source_table',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
        'option' => array(
            'values' =>
            array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec')
        ),
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'registration_date_month')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'registration_date_year', array(
        'type' => 'int',
        'input' => 'select',
        'sort_order' => 105,
        'label' => 'Bussiness Registration Year',
        'source' => 'eav/entity_attribute_source_table',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
        'option' => array(
            'values' =>
            array(1990, 1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999, 2000, 2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014, 2015)
        ),
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'registration_date_year')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'type_of_establishment', array(
        'type' => 'varchar',
        'input' => 'select',
        'sort_order' => 106,
        'label' => 'Type of Establishment',
        'source' => 'eav/entity_attribute_source_table',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
        'option' => array(
            'values' =>
            array(
                'Cafe' => 'Cafe',
                'Hotel' => 'Hotel',
                'Restaurant' => 'Restaurant',
                'Retail' => 'Retail',
                'Wholesale' => 'Wholesale',
                'Others' => 'Others',
            )
        ),
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'type_of_establishment')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'role_within_organization', array(
        'type' => 'varchar',
        'input' => 'select',
        'sort_order' => 107,
        'label' => 'Role within Organization',
        'source' => 'eav/entity_attribute_source_table',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
        'option' => array(
            'values' =>
            array(
                'Management_Owners' => 'Management / Owners',
                'Marketing_Sales' => 'Marketing / Sales',
                'Restaurant' => 'Restaurant',
                'Operations' => 'Operations',
                'Purchasing' => 'Purchasing',
            )
        ),
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'role_within_organization')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'website', array(
        'label' => 'Website',
        'type' => 'varchar',
        'sort_order' => 108,
        'input' => 'text',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'website')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'tea_consumption', array(
        'label' => 'Estimated Monthly Tea Consumption (Number of Cups)',
        'type' => 'int',
        'input' => 'text',
        'sort_order' => 109,
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'tea_consumption')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'security_question', array(
        'type' => 'varchar',
        'input' => 'select',
        'sort_order' => 110,
        'label' => 'Security Question',
        'source' => 'eav/entity_attribute_source_table',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
        'option' => array(
            'values' =>
            array(
                'pet_name' => 'What is your pet name?',
                'mother_maiden_name' => 'What is your mothers maiden name?',
                'first_school' => 'What is your first school name?',
            )
        ),
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'security_question')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'answer', array(
        'label' => 'Answer',
        'type' => 'varchar',
        'sort_order' => 111,
        'input' => 'text',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'answer')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'message', array(
        'label' => 'Message / Enquiry',
        'type' => 'text',
        'input' => 'textarea',
        'sort_order' => 112,
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'message')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'type_of_mkt_sup_provided', array(
        'label' => 'Type of Marketing support provided',
        'type' => 'varchar',
        'input' => 'textarea',
        'sort_order' => 113,
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'type_of_mkt_sup_provided')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'dofcontract', array(
        'label' => 'Date of contract',
        'input' => 'date',
        'type' => 'datetime',
        'sort_order' => 114,
        'source' => 'eav/entity_attribute_backend_datetime',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'dofcontract')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'tenure_of_contract_from', array(
        'label' => 'Tenure of Contract From',
        'input' => 'date',
        'type' => 'datetime',
        'sort_order' => 115,
        'backend' => 'eav/entity_attribute_backend_datetime',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'tenure_of_contract_from')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'tenure_of_contract_to', array(
        'label' => 'Tenure of Contract To',
        'input' => 'date',
        'sort_order' => 116,
        'type' => 'datetime',
        'backend' => 'eav/entity_attribute_backend_datetime',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'tenure_of_contract_to')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer_address', 'mobile_no_2', array(
        'label' => 'Mobile Number',
        'type' => 'varchar',
        'input' => 'text',
        'sort_order' => 118,
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer_address', 'mobile_no_2')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();

    $installer->addAttribute('customer', 'istrader', array(
        'type' => 'int',
        'input' => 'select',
        'sort_order' => 130,
        'label' => 'Is Trader',
        'source' => 'eav/entity_attribute_source_table',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required' => false,
        'user_defined' => true,
        'option' => array(
            'values' =>
            array(
                0 => 'No',
                1 => 'Yes'
            )
        ),
    ));

    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'website')
            ->setData('used_in_forms', array(
                'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
            ))
            ->save();
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}



$installer->run("

 DROP TABLE IF EXISTS {$installer->getTable('trader')};
CREATE TABLE {$installer->getTable('trader')} (
  `trader_id` int(11) unsigned NOT NULL auto_increment,
  `category_id` int(11) NOT NULL default '0',
  `customer_grp_id` int(11) NOT NULL default '0',
  `discount` float(22,2) NOT NULL default '0',
  `price_rule_id` int(11) NOT NULL default '0',
  `apply_to_sub_cat` enum('Yes', 'No') NOT NULL default 'No',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`trader_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
//Store Trader-Regular customer group 
$code = "Trader-Temp";
$collection = Mage::getModel('customer/group')->getCollection() //get a list of groups
        ->addFieldToFilter('customer_group_code', $code); // filter by group code
$group = Mage::getModel('customer/group')->load($collection->getFirstItem()->getId()); //load the first group with the required code - this may not be neede but it's a good idea in order to make magento dispatch all events and call all methods that usually calls when loading an object

$group->setCode($code); //set the code
$group->setTaxClassId(3); //set tax class
$group->save(); //save group
//Store Trader-Regular customer group 
$code = "Trader-Regular";
$collection = Mage::getModel('customer/group')->getCollection() //get a list of groups
        ->addFieldToFilter('customer_group_code', $code); // filter by group code
$group = Mage::getModel('customer/group')->load($collection->getFirstItem()->getId()); //load the first group with the required code - this may not be neede but it's a good idea in order to make magento dispatch all events and call all methods that usually calls when loading an object

$group->setCode($code); //set the code
$group->setTaxClassId(3); //set tax class
$group->save(); //save group
//Store Trader-Priority customer group  
$code = "Trader-Priority";
$collection = Mage::getModel('customer/group')->getCollection() //get a list of groups
        ->addFieldToFilter('customer_group_code', $code); // filter by group code
$group = Mage::getModel('customer/group')->load($collection->getFirstItem()->getId()); //load the first group with the required code - this may not be neede but it's a good idea in order to make magento dispatch all events and call all methods that usually calls when loading an object

$group->setCode($code); //set the code
$group->setTaxClassId(3); //set tax class
$group->save(); //save group 
//Store Trader-Premium customer group  
$code = "Trader-Premium";
$collection = Mage::getModel('customer/group')->getCollection() //get a list of groups
        ->addFieldToFilter('customer_group_code', $code); // filter by group code
$group = Mage::getModel('customer/group')->load($collection->getFirstItem()->getId()); //load the first group with the required code - this may not be neede but it's a good idea in order to make magento dispatch all events and call all methods that usually calls when loading an object

$group->setCode($code); //set the code
$group->setTaxClassId(3); //set tax class
$group->save(); //save group  





$installer->endSetup();
