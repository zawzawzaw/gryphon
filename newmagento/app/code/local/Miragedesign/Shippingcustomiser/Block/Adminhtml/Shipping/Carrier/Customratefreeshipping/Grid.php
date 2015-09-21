<?php
/** Author:: PG **/
class Miragedesign_Shippingcustomiser_Block_Adminhtml_Shipping_Carrier_Customratefreeshipping_Grid extends Mage_Adminhtml_Block_Shipping_Carrier_Tablerate_Grid
{
    /**
     * Define grid properties
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('shippingCustomratefreeshippingGrid');
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
        $collection = Mage::getResourceModel('shippingcustomiser/carrier_freeshippingrates_collection');
        $collection->setWebsiteFilter($this->getWebsiteId());

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
        $this->addColumn('groupname', array(
            'header'    => Mage::helper('adminhtml')->__('GroupName'),
            'index'     => 'groupname',
            'default'   => '*',
        ));
        $this->addColumn('OrderAmountFrom', array(
            'header'    => Mage::helper('adminhtml')->__('OrderAmountFrom'),
            'index'     => 'OrderAmountFrom',
        ));
		$this->addColumn('OrderAmountTo', array(
            'header'    => Mage::helper('adminhtml')->__('OrderAmountTo'),
            'index'     => 'OrderAmountTo',
        ));
		$this->addColumn('ShippingCharge', array(
            'header'    => Mage::helper('adminhtml')->__('ShippingCharge'),
            'index'     => 'ShippingCharge',
        ));
        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }

}