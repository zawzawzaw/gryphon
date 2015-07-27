<?php

/**
 * Customer model
 *
 * @category    Aemtech
 * @package     Trader_Customer
 * @author      Niranjan Nimbalkar 
 */
class Aemtech_Trader_Model_Customer extends Mage_Customer_Model_Customer
{

    const XML_PATH_REGISTER_DEALER_EMAIL_TEMPLATE = 'trader_trader_account_email_template';
    const XML_PATH_ACTIVATED_DEALER_EMAIL_TEMPLATE = 'trader/trader_account_activated/email_template';

    public function sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0')
    {
        $types = array(
            'registered' => self::XML_PATH_REGISTER_EMAIL_TEMPLATE, // welcome email, when confirmation is disabled
            'confirmed' => self::XML_PATH_CONFIRMED_EMAIL_TEMPLATE, // welcome email, when confirmation is enabled
            'confirmation' => self::XML_PATH_CONFIRM_EMAIL_TEMPLATE, // email with confirmation link
            'traderregister' => self::XML_PATH_REGISTER_DEALER_EMAIL_TEMPLATE, // email with confirmation link
            'traderactivated' => self::XML_PATH_ACTIVATED_DEALER_EMAIL_TEMPLATE, // email with confirmation link
        );
        if(!isset($types[$type])){
            Mage::throwException(Mage::helper('customer')->__('Wrong transactional account email type'));
        }

        if(!$storeId){
            $storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
        }
        if($type == 'traderregister'){
            $this->sendTraderRegisterEmail($backUrl, $storeId);
        } else{
            $this->_sendEmailTemplate($types[$type], self::XML_PATH_REGISTER_EMAIL_IDENTITY, array('customer' => $this, 'back_url' => $backUrl), $storeId);
        }


        return $this;
    }

    public function sendTraderRegisterEmail($backUrl = '', $storeId = '')
    {
        $emailTemplate = Mage::getModel('core/email_template')
                ->loadDefault('trader_trader_account_email_template');

        //Create an array of variables to assign to template
        if(!$storeId){
            $storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
        }
        $emailTemplateVariables = array();
        $emailTemplateVariables['store url'] = $backUrl;
        $emailTemplateVariables['logo_alt'] = Mage::app()->getStore()->getFrontendName();
        $emailTemplateVariables['storename'] = Mage::app()->getStore()->getFrontendName();
        $emailTemplateVariables['customer'] = $this;
        $emailTemplate->setSenderName(Mage::app()->getStore()->getFrontendName());
        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email'));
        $emailTemplate->setTemplateSubject("Welcome to " . Mage::app()->getStore()->getFrontendName() . "! Trader registration");
        $emailTemplate->setStoreId($storeId);
        $emailTemplate->setTemplateParams(array('customer' => $this, 'back_url' => $backUrl, 'store' => Mage::app()->getStore()->getId()));
        $emailTemplate->send($this->getEmail(), $this->getName(), $emailTemplateVariables);
    }

    public function sendTraderActivatedEmail($backUrl = '', $storeId = '')
    {
        $emailTemplate = Mage::getModel('core/email_template')
                ->loadDefault('trader_trader_account_activated_email_template');

        //Create an array of variables to assign to template
        if(!$storeId){
            $storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
        }
        $emailTemplateVariables = array();
        $emailTemplateVariables['store url'] = $backUrl;
        $emailTemplateVariables['logo_alt'] = Mage::app()->getStore()->getFrontendName();
        $emailTemplateVariables['storename'] = Mage::app()->getStore()->getFrontendName();
        $emailTemplateVariables['customer'] = $this;
        $emailTemplate->setSenderName(Mage::app()->getStore()->getFrontendName());
        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email'));
        $emailTemplate->setTemplateSubject("Welcome to " . Mage::app()->getStore()->getFrontendName() . "! Trader Account activated");
        $emailTemplate->setStoreId($storeId);
        $emailTemplate->setTemplateParams(array('customer' => $this, 'back_url' => $backUrl, 'store' => Mage::app()->getStore()->getId()));
        $emailTemplate->send($this->getEmail(), $this->getName(), $emailTemplateVariables);
    }

    public function _sendNotificationEmail($to, $templateConfigPath = self::XML_PATH_EMAIL_ADMIN_QUOTE_NOTIFICATION)
    {
        if(!$to)
            return;

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $mailTemplate = Mage::getModel('core/email_template');
        /* @var $mailTemplate Mage_Core_Model_Email_Template */

        $template = Mage::getStoreConfig($templateConfigPath, Mage::app()->getStore()->getId());
        $sendTo = array();
        foreach($to as $recipient){
            if(is_array($recipient)){
                $sendTo[] = $recipient;
            } else{
                $sendTo[] = array(
                    'email' => $recipient,
                    'name' => null,
                );
            }
        }

        foreach($sendTo as $recipient){
            $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => Mage::app()->getStore()->getId()))
                    ->sendTransactional(
                            $template, Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY, Mage::app()->getStore()->getId()), $recipient['email'], $recipient['name'], array(
                        'customer' => $customer,
                        'quote' => $quote
                            )
            );
        }

        $translate->setTranslateInline(true);

        return $this;
    }

}
