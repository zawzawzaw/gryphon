<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Shippingcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Shippingcustomiser_Model_Adminhtml_System_Config_Source_Shipping_Customrate
{
    public function toOptionArray()
    {
        $tableRate = Mage::getSingleton('shippingcustomiser/carrier_customrate');
        $arr = array();

        foreach ($tableRate->getCode('condition_name') as $k=>$v) {
            $arr[] = array('value' => $k, 'label' => $v);
        }

        return $arr;
    }
}
