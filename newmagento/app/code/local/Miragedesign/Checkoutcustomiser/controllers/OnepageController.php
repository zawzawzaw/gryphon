<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Checkoutcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'Mage/Checkout/controllers/OnepageController.php';

class Miragedesign_Checkoutcustomiser_OnepageController extends Mage_Checkout_OnepageController
{
    public function saveBillingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {

            $billingData = $this->getRequest()->getPost('billing', array());
            $customerBillingAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($billingData['email'])) {
                $billingData['email'] = trim($billingData['email']);
            }
			
			//PG: Validation of Shipping Country before Checkout
			$result = array();			
			$cart = $this->getOnepage()->getQuote();
			$countryID = $billingData['country_id'];
			foreach ($cart->getAllItems() as $item) {
				$productSku = $item->getProduct()->getSku();
				$productSku = explode('-', $productSku);
				$pSku = $productSku[0];
				if(strtolower($pSku) == 'sgp' && 'sg' != strtolower($countryID))
				{
					$result['success'] = false;
					$result['error'] = true;
					$result['message'] = $this->__('The selected country is not valid for this subscription.');	
					$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
				}
				if(strtolower($pSku) == 'int' && 'sg' == strtolower($countryID))
				{
					$result['success'] = false;
					$result['error'] = true;
					$result['message'] = $this->__('The selected country is not valid for this subscription.');
					$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
				}
			}
			//PG: end			
			
			$result = $this->getOnepage()->saveBilling($billingData, $customerBillingAddressId);        

            if (!isset($result['error'])) {

                $shippingData = $this->getRequest()->getPost('shipping', array());
                $customerShippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
                $result = $this->getOnepage()->saveShipping($shippingData, $customerShippingAddressId);

                if (!isset($result['error'])) {
                    $this->saveShippingMethod();
                    $this->savePaymentMethod();

                    if ($this->getOnepage()->getQuote()->isVirtual()) {
                        $result['goto_section'] = 'review';
                        $result['update_section'] = array(
                            'name' => 'review',
                            'html' => $this->_getReviewHtml()
                        );
                    } else {
                        $result['goto_section'] = 'review';
                        $result['update_section'] = array(
                            'name' => 'review',
                            'html' => $this->_getReviewHtml()
                        );

                        $result['allow_sections'] = array('billing');
                        //$result['duplicateBillingInfo'] = 'false';
                    }
                }
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

    public function saveShippingMethod()
    {
        //$method = 'flatrate_flatrate';
        $method = $this->getRequest()->getPost('md_shipping_method', 'customrate_customrate_flatrate');
        $result = $this->getOnepage()->saveShippingMethod($method);
        // $result will contain error data if shipping method is empty
        if (!$result) {
            Mage::dispatchEvent(
                'checkout_controller_onepage_save_shipping_method',
                array(
                    'request' => $this->getRequest(),
                    'quote'   => $this->getOnepage()->getQuote()));
            $this->getOnepage()->getQuote()->collectTotals();
        }
        // After setting the shipping method automatically, collect shipping rates again
        $this->getOnepage()->getQuote()->getShippingAddress()
            ->setCollectShippingRates(true)
            ->collectShippingRates();
        // Collect totals again (because shipping rates can be changed)
        $this->getOnepage()->getQuote()->setTotalsCollectedFlag(false);
        $this->getOnepage()->getQuote()->collectTotals()->save();
    }

    public function savePaymentMethod()
    {
        $method = array('method' => 'paypal_standard');
        $result = $this->getOnepage()->savePayment($method);
    }

    /**
     * Create order action
     */
    public function saveOrderAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*');
            return;
        }

        if ($this->_expireAjax()) {
            return;
        }

        $result = array();
        try {
            $requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds();
            if ($requiredAgreements) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                $diff = array_diff($requiredAgreements, $postedAgreements);
                if ($diff) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }

            // Payment method always is paypal standard
            //$data = $this->getRequest()->getPost('payment', array());
            $data = array('method' => 'paypal_standard');

            if ($data) {
                $data['checks'] = Mage_Payment_Model_Method_Abstract::CHECK_USE_CHECKOUT
                    | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_COUNTRY
                    | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_CURRENCY
                    | Mage_Payment_Model_Method_Abstract::CHECK_ORDER_TOTAL_MIN_MAX
                    | Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL;
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }

            $this->getOnepage()->saveOrder();

            /////
            $outputMessage = Mage::getSingleton('core/session')->getSpecialMessage();

            $last_order_increment_id = Mage::getModel("sales/order")->getCollection()->getLastItem()->getIncrementId();

            $order = Mage::getModel('sales/order')->loadByIncrementId($last_order_increment_id);
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
            }

            $giftMessage = Mage::getModel('giftmessage/message'); 
            if(isset($customerData)) $giftMessage->setCustomerId($customerData->getId()); 
            $giftMessage->setSender(''); 
            $giftMessage->setRecipient(''); 
            $giftMessage->setMessage($outputMessage);
            $giftObj = $giftMessage->save(); 

            $order->setGiftMessageId($giftObj->getId()); 
            $order->save();
            /////

            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error']   = false;
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if (!empty($message)) {
                $result['error_messages'] = $message;
            }
            $result['goto_section'] = 'payment';
            $result['update_section'] = array(
                'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();

            $gotoSection = $this->getOnepage()->getCheckout()->getGotoSection();
            if ($gotoSection) {
                $result['goto_section'] = $gotoSection;
                $this->getOnepage()->getCheckout()->setGotoSection(null);
            }
            $updateSection = $this->getOnepage()->getCheckout()->getUpdateSection();
            if ($updateSection) {
                if (isset($this->_sectionUpdateFunctions[$updateSection])) {
                    $updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
                    $result['update_section'] = array(
                        'name' => $updateSection,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->getOnepage()->getCheckout()->setUpdateSection(null);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success']  = false;
            $result['error']    = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
        }
        $this->getOnepage()->getQuote()->save();
        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Get order review step html
     *
     * @return string
     */
    protected function _getReviewHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_review');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }

    public function saveGiftMessageAction()
    {        
        if ($this->getRequest()->isPost()) {
    
            $special_message = $this->getRequest()->getPost('special_message', array());
    
            if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
            }
    
    
            Mage::getSingleton('core/session')->setSpecialMessage($special_message);
    
            return Mage::getSingleton('core/session')->getSpecialMessage();
    
            // $giftMessage = Mage::getModel('giftmessage/message'); 
            // $giftMessage->setCustomerId($customerData->getId()); 
            // $giftMessage->setSender(''); 
            // $giftMessage->setRecipient(''); 
            // $giftMessage->setMessage($special_message); 
            // $giftObj = $giftMessage->save(); 
    
            // print_r($giftObj);
            // $order->setGiftMessageId($giftObj->getId()); 
            // $order->save(); 
    
        } 
    }
}
