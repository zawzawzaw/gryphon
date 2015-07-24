<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomerController
 *
 * @author niranjan
 */
require_once 'Mage/Customer/controllers/AccountController.php';

class Aemtech_Trader_Customer_AccountController extends Mage_Customer_AccountController
{

    /**
     * Login post action
     */
    public function loginPostAction()
    {
        
        if(!$this->_validateFormKey()){
            $this->_redirect('*/*/');
            return;
        }

        if($this->_getSession()->isLoggedIn()){
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if($this->getRequest()->isPost()){
            $login = $this->getRequest()->getPost('login');
            if(!empty($login['username']) && !empty($login['password'])){
                try{
                    $session->login($login['username'], $login['password']);
                    /* Check if the customer is in Trader-Temp group if so he can't login yet */
                    $groups = Mage::getSingleton('customer/group')->getCollection()->addFieldToFilter("customer_group_code", 'Trader-Temp');

                    $groupID = 1;
                    if($groups && count($groups) > 0){
                        foreach($groups as $preRegGroup){
                            $groupID = $preRegGroup->getId();
                            break;
                        }
                    }
                    $customergrp = $session->getCustomer()->getGroupId();
                    $IsActivated = $session->getCustomer()->getIsactivated();
                        
                    if($IsActivated === "0"){
                        $session->logout()->renewSession();
                        $session->addError($this->__('Your account has not been approved. We will send you an email once your registration has been approved.'));
                        $url = $this->_getUrl('*/*/index', array('_secure' => true));
                        $this->_redirectError($url);
                        return;
                    }

                    if($session->getCustomer()->getIsJustConfirmed()){
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
                }
                catch(Mage_Core_Exception $e){
                    switch($e->getCode()){
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = $this->_getHelper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = $this->_getHelper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    
                    $session->addError($message);
                    $session->setUsername($login['username']);
                }
                catch(Exception $e){
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else{
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }

    public function createPostAction()
    {
        //echo "Reached Here::Correct";die();
        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        if($session->isLoggedIn()){
            $this->_redirect('*/*/');
            return;
        }
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if(!$this->getRequest()->isPost()){
            $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
            $this->_redirectError($errUrl);
            return;
        }

        $customer = $this->_getCustomer();
        /**
         * Initialize customer group id
         */
        try{
            $usergroup = $this->getRequest()->getPost('group');

            if(!empty($usergroup) && $usergroup == 'Trader-Temp'){

                $groups = Mage::getSingleton('customer/group')->getCollection()->addFieldToFilter("customer_group_code", $usergroup);

                $groupID = 1;
                if($groups && count($groups) > 0){
                    foreach($groups as $preRegGroup){
                        $groupID = $preRegGroup->getId();
                        break;
                    }
                }

                $customer->setGroupId($groupID);
                $customer->setIsactivated(0);
                $taxvat = $this->getRequest()->getPost('taxvat');
                $customer->setTaxvat($taxvat);
            }

            //Mage::log("Customer Group set to".$groupID);
        }
        catch(Exception $ex){
            Mage::logException($ex); 
        }
        try{
            $errors = $this->_getCustomerErrors($customer);

            if(empty($errors)){
                $customer->save();
                $this->_dispatchRegisterSuccess($customer);
                $this->_successProcessRegistration($customer);
                return;
            } else{
                $this->_addSessionError($errors);
            }
        }
        catch(Mage_Core_Exception $e){
            $session->setCustomerFormData($this->getRequest()->getPost());
            if($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS){
                $url = $this->_getUrl('customer/account/forgotpassword');
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                $session->setEscapeMessages(false);
            } else{
                $message = $e->getMessage();
            }
            //$session->addError($message);
            Mage::getSingleton('core/session')->addError(Mage::helper('catalog')->__($message));
            if(!empty($usergroup) && $usergroup == 'Trader-Temp' && !empty($message)){
                $errUrl = $this->_getUrl('trader/index', array('_secure' => true));
            } else{
                $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
            }
            $this->_redirectError($errUrl);
        }
        catch(Exception $e){
            $session->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
        }
        if(empty($message)){
            if(!empty($usergroup) && $usergroup == 'Trader-Temp' && !empty($errors)){
                $errUrl = $this->_getUrl('trader/index', array('_secure' => true));
            } else{
                $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
            }
        } else{
            $this->_addSessionError($message);
        } 
                        
        $this->_redirectError($errUrl);
    }

    /**
     * Success Registration
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return Mage_Customer_AccountController
     */
    protected function _successProcessRegistration(Mage_Customer_Model_Customer $customer)
    {
        $session = $this->_getSession();

        $groups = Mage::getSingleton('customer/group')->getCollection()->addFieldToFilter("customer_group_code", "Trader-Temp");
        $groupID = 1;
        if($groups && count($groups) > 0){
            foreach($groups as $preRegGroup){
                $groupID = $preRegGroup->getId();
                break;
            }
        }
        if($customer->isConfirmationRequired() && $customer->getGroupId() != $groupID){
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store */
            $store = $app->getStore();
            $customer->sendNewAccountEmail(
                    'confirmation', $session->getBeforeAuthUrl(), $store->getId()
            );
            $customerHelper = $this->_getHelper('customer');
            
            $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.', $customerHelper->getEmailConfirmationUrl($customer->getEmail())));
            $url = $this->_getUrl('*/*/index', array('_secure' => true));
        } elseif($customer->getGroupId() == $groupID){
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store */
            $store = $app->getStore();
            $customer->sendNewAccountEmail(
            'traderregister', $session->getBeforeAuthUrl(), $store->getId()
            );
            Mage::getSingleton('core/session')->getMessages(true);
            $session->addSuccess($this->__('Account Successfully Created. You will be notified by E-Mail when the account is activated.'));
            $url = $this->_getUrl('*/*/index', array('_secure' => true));
        } else{
            $session->setCustomerAsLoggedIn($customer);
            $url = $this->_welcomeCustomer($customer);
        }
        $this->_redirectSuccess($url);
        return $this;
    }

}
