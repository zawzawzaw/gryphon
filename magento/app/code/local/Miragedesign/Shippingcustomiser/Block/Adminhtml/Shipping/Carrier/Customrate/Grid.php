<?php

/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Shippingcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Miragedesign_Shippingcustomiser_Block_Adminhtml_Shipping_Carrier_Customrate_Grid extends Mage_Adminhtml_Block_Shipping_Carrier_Tablerate_Grid
{
    /**
     * Define grid properties
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('shippingCustomrateGrid');
        $this->_exportPageSize = 10000;
    }

    /**
     * Prepare shipping table rate collection
     *
     * @return Miragedesign_Shippingcustomiser_Block_Adminhtml_Shipping_Carrier_Customrate_Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection Miragedesign_Shippingcustomiser_Model_Mysql4_Carrier_Customrate_Collection */
        $collection = Mage::getResourceModel('shippingcustomiser/carrier_customrate_collection');
        $collection->setConditionFilter($this->getConditionName())
            ->setWebsiteFilter($this->getWebsiteId());

        $this->setCollection($collection);

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    /**
     * Prepare table columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('dest_country', array(
            'header'    => Mage::helper('adminhtml')->__('Country'),
            'index'     => 'dest_country',
            'default'   => '*',
        ));

        $this->addColumn('dest_region', array(
            'header'    => Mage::helper('adminhtml')->__('Region/State'),
            'index'     => 'dest_region',
            'default'   => '*',
        ));

        $this->addColumn('dest_zip', array(
            'header'    => Mage::helper('adminhtml')->__('Zip/Postal Code'),
            'index'     => 'dest_zip',
            'default'   => '*',
        ));

        $label = Mage::getSingleton('shippingcustomiser/carrier_customrate')
            ->getCode('condition_name_short', $this->getConditionName());

        $this->addColumn('condition_value', array(
            'header'    => $label,
            'index'     => 'condition_value',
        ));

        $this->addColumn('price', array(
            'header'    => Mage::helper('adminhtml')->__('Shipping Price'),
            'index'     => 'price',
        ));

        $this->addColumn('delivery_time', array(
            'header'    => Mage::helper('adminhtml')->__('Delivery Time'),
            'index'     => 'delivery_time',
        ));

        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }

}