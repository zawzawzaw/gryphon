<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Shippingcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Shippingcustomiser_Model_Adminhtml_System_Config_Backend_Shipping_Customrate extends Mage_Core_Model_Config_Data
{
    public function _afterSave()
    {
		Mage::getResourceModel('shippingcustomiser/carrier_customrate')->uploadAndImport($this);
    }
}
