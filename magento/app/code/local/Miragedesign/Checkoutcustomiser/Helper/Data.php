<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Checkoutcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Checkoutcustomiser_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get url for reorder action
     *
     * @param Mage_Sales_Order $order
     * @return string
     */
    public function getReorderUrl($order)
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return Mage::getUrl('sales/guest/reorder', array('order_id' => $order->getId()));
        }
        return Mage::getUrl('sales/order/reorder', array('order_id' => $order->getId()));
    }
}