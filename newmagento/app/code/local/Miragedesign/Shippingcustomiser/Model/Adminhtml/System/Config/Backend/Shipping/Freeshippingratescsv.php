<?php
/** Author:: PG **/
class Miragedesign_Shippingcustomiser_Model_Adminhtml_System_Config_Backend_Shipping_Freeshippingratescsv extends Mage_Core_Model_Config_Data
{
    public function _afterSave()
    {
        Mage::getResourceModel('shippingcustomiser/carrier_freeshippingrates')->uploadAndImport($this);
    }
}