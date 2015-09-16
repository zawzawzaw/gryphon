<?php
/** Author:: PG **/
class Miragedesign_Shippingcustomiser_Model_Mysql4_Customfreeshippingrate_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct() {
        parent::_construct();
        $this->_init('shippingcustomiser/customfreeshippingrate');
    }
}