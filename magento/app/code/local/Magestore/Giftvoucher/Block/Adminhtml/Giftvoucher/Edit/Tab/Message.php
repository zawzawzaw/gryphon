<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_Edit_Tab_Message extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('customer_fieldset', array('legend' => Mage::helper('giftvoucher')->__('Customer')));

        $fieldset->addField('customer_name', 'text', array(
            'label' => Mage::helper('giftvoucher')->__('Customer Name'),
            'required' => false,
            'name' => 'customer_name',
        ));

        $fieldset->addField('customer_email', 'text', array(
            'label' => Mage::helper('giftvoucher')->__('Customer Email'),
            'required' => false,
            'name' => 'customer_email',
        ));

        $fieldset = $form->addFieldset('recipient_fieldset', array('legend' => Mage::helper('giftvoucher')->__('Recipient')));

        $fieldset->addField('recipient_name', 'text', array(
            'label' => Mage::helper('giftvoucher')->__('Recipient Name'),
            'required' => false,
            'name' => 'recipient_name',
        ));

        $fieldset->addField('recipient_email', 'text', array(
            'label' => Mage::helper('giftvoucher')->__('Recipient Email'),
            'required' => false,
            'name' => 'recipient_email',
        ));

         $fieldset = $form->addFieldset('shipping_address', array('legend' => Mage::helper('giftvoucher')->__('Shipping Address')));

        $fieldset->addField('recipient_address', 'editor', array(
            'label' => Mage::helper('giftvoucher')->__('Recipient Address'),
            'name' => 'recipient_address',
            'style' => 'height:75px;',
        ));

        $fieldset = $form->addFieldset('message_fieldset', array('legend' => Mage::helper('giftvoucher')->__('Message')));

        $fieldset->addField('message', 'editor', array(
            'label' => Mage::helper('giftvoucher')->__('Message'),
            'required' => false,
            'name' => 'message',
        ));

        if (Mage::registry('giftvoucher_data')) {
            $form->addValues(Mage::registry('giftvoucher_data')->getData());
        }

        return parent::_prepareForm();
    }

}