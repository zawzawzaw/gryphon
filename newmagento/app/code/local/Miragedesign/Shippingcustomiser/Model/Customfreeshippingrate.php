<?php
/** Author:: PG **/
class Miragedesign_Shippingcustomiser_Model_Customfreeshippingrate extends Mage_Core_Model_Abstract
{
    public function _construct() {
        parent::_construct();
        $this->_init('shippingcustomiser/customfreeshippingrate');
    }  
}