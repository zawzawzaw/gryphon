<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Shippingcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Shippingcustomiser_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Get shipping information
     */
    public function ajaxGetShippingAction()
    {
//        if (!$this->_validateFormKey()) {
//            Mage::throwException('Invalid form key');
//        }

        $country = $this->getRequest()->getParam('country_id', null);
        $addressId = $this->getRequest()->getParam('address_id', null);
        $result = array();

        if ($country) {
            $postcode = (string)$this->getRequest()->getParam('estimate_postcode', '*');
            $city = $this->getRequest()->getParam('estimate_city', 0);
            $regionId = $this->getRequest()->getParam('region_id', 0);
            $region = $this->getRequest()->getParam('region', '');

            $this->_getQuote()->getShippingAddress()
                ->setCountryId($country)
                ->setCity($city)
                ->setPostcode($postcode)
                ->setRegionId($regionId)
                ->setRegion($region)
                ->setCollectShippingRates(true);
        } else if ($addressId) {
            $customerAddress = Mage::getModel('customer/address')->load($addressId);

            if ($customerAddress->getId()) {
                if ($customerAddress->getCustomerId() != $this->_getQuote()->getCustomerId()) {
                    $result = array('success' => 0,
                        'error' => Mage::helper('checkout')->__('Customer Address is not valid.')
                    );
                } else {
                    $this->_getQuote()->getShippingAddress()
                        ->setCountryId($customerAddress->getCountryId())
                        ->setCity($customerAddress->getCity())
                        ->setPostcode($customerAddress->getPostcode())
                        ->setRegionId($customerAddress->getRegionId())
                        ->setRegion($customerAddress->getRegion())
                        ->setCollectShippingRates(true);
                }
            }
        }

        if ($country || $addressId) {
            try {
                $this->_getQuote()->save();
                $this->loadLayout();
                $result['content'] = $this->getLayout()->getBlock('content')->toHtml();
                $result['success'] = 1;
            } catch (Exception $e) {
                echo $e->getMessage();
                $result['success'] = 0;
                //$result['error'] = $this->__('Can not get shipping information.');
                $result['error'] = $e->getMessage();
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

    /**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }
}