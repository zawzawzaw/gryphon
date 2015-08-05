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


class Mirasvit_Email_Model_Rule_Condition_Order extends Mirasvit_Email_Model_Rule_Condition_Abstract
{
    public function loadAttributeOptions()
    {
        $attributes = array(
            'summary_qty'     => Mage::helper('email')->__('Order: Total quantity of products'),
            'summary_count'   => Mage::helper('email')->__('Order: Total count of products'),
            'grand_total'     => Mage::helper('email')->__('Order: Grand Total'),
            'shipping_method' => Mage::helper('email')->__('Order: Shipping Method'),
        );

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

    public function validate(Varien_Object $object)
    {
        $attrCode = $this->getAttribute();
        
        if ($object->getData('order_id')) {
            $order = Mage::getModel('sales/order')->load($object->getData('order_id'));

            $qty         = 0;
            $count       = 0;

            foreach ($order->getAllItems() as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getProductId());

                $qty += $item->getQtyOrdered();
                $count += 1;
            }

            $object->setData('summary_qty', $qty)
                ->setData('summary_count', $count)
                ->setData('grand_total', $order->getGrandTotal())
                ->setData('shipping_method', $order->getShippingMethod());
        } else {
            $object->setData('summary_qty', 0)
                ->setData('summary_count', 0)
                ->setData('grand_total', 0)
                ->setData('shipping_method', null);
        }

        $value = $object->getData($attrCode);

        return $this->validateAttribute($value);
    }
}