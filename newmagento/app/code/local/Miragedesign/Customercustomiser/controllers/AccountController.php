<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Customercustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
require_once 'Mage/Customer/controllers/AccountController.php';

class Miragedesign_Customercustomiser_AccountController extends Mage_Customer_AccountController
{
    /**
     * Change customer password action
     */
    public function editPostAction()
    {
        require_once(Mage::getBaseDir('lib') . '/Mailchimpapi/MailChimp.php');

        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getSession()->getCustomer();

            /** @var $customerForm Mage_Customer_Model_Form */
            $customerForm = Mage::getModel('customer/form');
            $customerForm->setFormCode('customer_account_edit')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());
            $errors = array();
            $customerErrors = $customerForm->validateData($customerData);
            if ($customerErrors !== true) {
                $errors = array_merge($customerErrors, $errors);
            } else {
                $customerForm->compactData($customerData);
                $errors = array();

                // If password change was requested then add it to common validation scheme
                if ($this->getRequest()->getParam('change_password')) {
                    $currPass   = $this->getRequest()->getPost('current_password');
                    $newPass    = $this->getRequest()->getPost('password');
                    $confPass   = $this->getRequest()->getPost('confirmation');

                    $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
                    if ( $this->_getHelper('core/string')->strpos($oldPass, ':')) {
                        list($_salt, $salt) = explode(':', $oldPass);
                    } else {
                        $salt = false;
                    }

                    if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                        if (strlen($newPass)) {
                            /**
                             * Set entered password and its confirmation - they
                             * will be validated later to match each other and be of right length
                             */
                            $customer->setPassword($newPass);
                            $customer->setPasswordConfirmation($confPass);
                        } else {
                            $errors[] = $this->__('New password field cannot be empty.');
                        }
                    } else {
                        $errors[] = $this->__('Invalid current password');
                    }
                }

                // Validate account and compose list of errors if any
                $customerErrors = $customer->validate();
                if (is_array($customerErrors)) {
                    $errors = array_merge($errors, $customerErrors);
                }
            }

            /* @var $address Mage_Customer_Model_Address */
            $address  = Mage::getModel('customer/address');
            $addressId = ($customer->getPrimaryBillingAddress()) ? $customer->getPrimaryBillingAddress()->getId() : null;

            // Set subscribe newsletter
            $customer->setIsSubscribed((boolean)$this->getRequest()->getPost('is_subscribed', false));
            
            if((boolean)$this->getRequest()->getPost('is_subscribed', false)) {
                $MailChimp = new \Drewm\MailChimp('3f4cca784b2f700c1d129e00b8300115-us2');

                //55c5f5090f
                $result = $MailChimp->call('lists/subscribe', array(
                    'id'                => '55c5f5090f',
                    'email'             => array('email'=>$customerData['email']),
                    'merge_vars'        => array('FNAME'=>$customerData['firstname'], 'LNAME'=>$customerData['lastname']),
                    'double_optin'      => false,
                    'update_existing'   => true,
                    'replace_interests' => false,
                    'send_welcome'      => false,
                ));
            }
            

            $defaultAddress = false;

            if ($addressId) {
                $address->setId($addressId);
            } else {
                $defaultAddress = true;
            }

            /* @var $addressForm Mage_Customer_Model_Form */
            $addressForm = Mage::getModel('customer/form');
            $addressForm->setFormCode('customer_address_edit')
                ->setEntity($address);
            $addressData    = $addressForm->extractData($this->getRequest());
            $addressErrors  = $addressForm->validateData($addressData);

            if ($addressErrors !== true) {
                $errors = array_merge($errors, $addressErrors);
            }

            $addressForm->compactData($addressData);
            $address->setCustomerId($customer->getId());

            if ($defaultAddress) {
                $address->setIsDefaultBilling($defaultAddress)
                        ->setIsDefaultShipping($defaultAddress);
            }

            $addressErrors = $address->validate();

            if ($addressErrors !== true) {
                $errors = array_merge($errors, $addressErrors);
            }

            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }

            try {
                $customer->cleanPasswordsValidationData();

                // Save customer information
                $customer->save();
                // Save address
                $address->save();

                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('The account information has been saved.'));

                $this->_redirect('customer/account');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
            }
        }

        $this->_redirect('*/*/edit');
    }
}
