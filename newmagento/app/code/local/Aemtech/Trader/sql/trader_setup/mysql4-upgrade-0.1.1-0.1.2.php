<?php

    $installer = $this;
    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
    $installer->startSetup();

    //Create Attribute
    $installer->addAttribute('catalog_product', 'package', array(
    'group'             => 'General',
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Package Label',
    'input'             => 'select',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false,
    'used_in_product_listing' => true,
    'sort_order'        => 500,
    ));

    //Add initial basic color options
    $initialOptions = array('Box','Carton');
    $entityTypeId     = $installer->getEntityTypeId('catalog_product');
    $code ='package';
    $attributeId = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'package')->getId();
    foreach($initialOptions as $k=>$v){
    $installer->addAttributeOption(array(
    'attribute_id' => $attributeId,
    'order' => array($k),
    'value' => array(array($v)
    )
    ));

    }

    //Remove
    //$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
    //$setup->removeAttribute( 'catalog_product', 'package' );

    $installer->endSetup();
