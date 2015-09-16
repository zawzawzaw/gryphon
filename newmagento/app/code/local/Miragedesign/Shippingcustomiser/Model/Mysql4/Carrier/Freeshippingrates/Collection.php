<?php
/** Author:: PG **/
class Miragedesign_Shippingcustomiser_Model_Mysql4_Carrier_Freeshippingrates_Collection extends Varien_Data_Collection_Db
{
    protected $_shipTable;

    public function __construct()
    {
        parent::__construct(Mage::getSingleton('core/resource')->getConnection('shipping_read'));
        $this->_shipTable = Mage::getSingleton('core/resource')->getTableName('shippingcustomiser/customfreeshippingrate');
       
        $this->_select->from(array("s" => $this->_shipTable))            
            ->order(array("groupname"));
        $this->_setIdFieldName('pk');
        return $this;
    }

    public function setWebsiteFilter($websiteId)
    {
        $this->_select->where("website_id = ?", $websiteId);

        return $this;
    }
}