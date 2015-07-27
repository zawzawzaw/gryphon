<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer edit block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Aemtech_Trader_Block_Adminhtml_Customer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'customer';
        
        $groups = Mage::getResourceModel('customer/group_collection')
                        ->addFieldToFilter('customer_group_id', array('gt' => 0))
                        ->addFieldToFilter('customer_group_code', array('in' => array('Trader-Temp', 'Trader-Regular', 'Trader-Priority', 'Trader-Premium')))
                        ->load()->toArray();
        foreach($groups['items'] as $value){
            $grps[] = $value['customer_group_id'];
        }
        $currentcustomergroup = Mage::registry('current_customer')->getGroupId();
        $getparams = $this->getRequest()->getParams();
        if(in_array($currentcustomergroup, $grps) || isset($getparams['fromdd'])){
            $backurl = $this->getUrl('adminhtml/trader/index');
        } else{
            $backurl = $this->getUrl('adminhtml/customer/index');
        }

//        $data = array(
//            'label' => 'Back',
//            'onclick' => 'setLocation(\'' . $backurl . '\')',
//            'class' => 'back'
//        );
//        $this->addButton('mback', $data, -1, 100, 'header');
        if($this->getCustomerId() &&
                Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/create')){
            $this->_addButton('order', array(
                'label' => Mage::helper('customer')->__('Create Order'),
                'onclick' => 'setLocation(\'' . $this->getCreateOrderUrl() . '\')',
                'class' => 'add',
                    ), 0);
        }
         
        parent::__construct();

        if(in_array($currentcustomergroup, $grps) || isset($getparams['fromdd'])){
            $this->_updateButton('save', 'label', Mage::helper('customer')->__('Save Trader'));
            $this->_updateButton('delete', 'label', Mage::helper('customer')->__('Delete Trader'));
            $deleteurl = Mage::helper('adminhtml')->getUrl('adminhtml/customer/delete') . 'id/' . Mage::registry('current_customer')->getId() . '/fromdd/true';
            $this->_removeButton('delete');
            $data = array(
                'label' => 'Delete Trader',
                'onclick' => 'setLocation(\'' . $deleteurl . '\')',
                'class' => 'delete'
            );
            $this->addButton('delete', $data, 0, 100, 'header');
        } else{
            $this->_updateButton('save', 'label', Mage::helper('customer')->__('Save Customer'));
            $this->_updateButton('delete', 'label', Mage::helper('customer')->__('Delete Customer'));
        }


        if(Mage::registry('current_customer')->isReadonly()){
            $this->_removeButton('save');
            $this->_removeButton('reset');
        }

        if(!Mage::registry('current_customer')->isDeleteable()){
            $this->_removeButton('delete');
        }
        $this->_removeButton('back');
        $data = array(
            'label' => 'Back',
            'onclick' => 'setLocation(\'' . $backurl . '\')',
            'class' => 'back'
        );
        $this->addButton('back', $data, -1, 100, 'header');
    }

    public function getCreateOrderUrl()
    {
        return $this->getUrl('*/sales_order_create/start', array('customer_id' => $this->getCustomerId()));
    }

    public function getCustomerId()
    {
        return Mage::registry('current_customer')->getId();
    }

    public function getHeaderText()
    {
        if(Mage::registry('current_customer')->getId()){
            return $this->escapeHtml(Mage::registry('current_customer')->getName());
        } else{
            $getparams = $this->getRequest()->getParams();
            if(isset($getparams['fromdd'])){

                return Mage::helper('customer')->__('New Trader');
            } else{
                return Mage::helper('customer')->__('New Customer');
            }
        }
    }

    /**
     * Prepare form html. Add block for configurable product modification interface
     *
     * @return string
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $html .= $this->getLayout()->createBlock('adminhtml/catalog_product_composite_configure')->toHtml();
        return $html;
    }

    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current' => true));
    }

    protected function _prepareLayout()
    {
        
        if(!Mage::registry('current_customer')->isReadonly()){
            $this->_addButton('save_and_continue', array(
                'label' => Mage::helper('customer')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit(\'' . $this->_getSaveAndContinueUrl() . '\')',
                'class' => 'save'
                    ), 10);
        }

        return parent::_prepareLayout();
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
                    '_current' => true,
                    'back' => 'edit',
                    'tab' => '{{tab_id}}'
        ));
    }

}
