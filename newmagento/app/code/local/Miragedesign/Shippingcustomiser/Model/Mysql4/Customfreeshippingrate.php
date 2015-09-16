<?php
/** Author:: PG **/
class Miragedesign_Shippingcustomiser_Model_Mysql4_Customfreeshippingrate extends Mage_Core_Model_Mysql4_Abstract
{  
	public function _construct() {
        $this->_init('shippingcustomiser/customfreeshippingrate', 'pk');
    }
}