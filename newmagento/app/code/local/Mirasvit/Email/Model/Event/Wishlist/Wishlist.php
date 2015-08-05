<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Follow Up Email
 * @version   1.0.2
 * @build     437
 * @copyright Copyright (C) 2015 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Email_Model_Event_Wishlist_Wishlist extends Mirasvit_Email_Model_Event_Abstract
{
    const EVENT_CODE = 'wishlist_wishlist|';

    public function getEventsGroup()
    {
        return Mage::helper('email')->__('Wishlist');
    }

    public function getEvents()
    {
        $result = array();

        $result[self::EVENT_CODE.'productadded'] = Mage::helper('email')->__('Product was added to wishlist');
        $result[self::EVENT_CODE.'shared']       = Mage::helper('email')->__('Wishlist shared');

        return $result;
    }

    public function findEvents($eventCode, $from)
    {
        return array();
    }

    public function observer($eventCode, $observer)
    {
        $key = array();
        if ($eventCode == self::EVENT_CODE.'productadded') {
            $product  = $observer->getDataObject()->getProduct();
            $wishlist = Mage::getModel('wishlist/wishlist')->load($observer->getDataObject()->getWishlistId());
            $customer = Mage::getModel('customer/customer')->load($wishlist->getCustomerId());

            $key[] = $customer->getEmail();
            $key[] = $wishlist->getId();
            $key[] = $product->getId();

            $args = array(
                'time'              => time(),
                'customer_email'    => $customer->getEmail(),
                'customer_name'     => $customer->getName(),
                'customer_id'       => $customer->getId(),
                'store_id'          => Mage::app()->getStore()->getId(),
                'product_id'        => $product->getId(),
                'wishlist_id'       => $wishlist->getId(),
            );

            $this->saveEvent($eventCode, implode('|', $key), $args);
        } elseif ($eventCode == self::EVENT_CODE.'shared') {
            $wishlist = $observer->getWishlist();
            $customer = Mage::getModel('customer/customer')->load($wishlist->getCustomerId());

            $key[] = $customer->getEmail();
            $key[] = $wishlist->getId();

            $args = array(
                'time'              => time(),
                'customer_email'    => $customer->getEmail(),
                'customer_name'     => $customer->getName(),
                'customer_id'       => $customer->getId(),
                'store_id'          => $wishlist->getStore()->getId(),
                'wishlist_id'       => $wishlist->getId(),
            );

            $this->saveEvent($eventCode, implode('|', $key), $args);
        }
    }
}