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
 * Customer account form block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Aemtech_Trader_Block_Adminhtml_Customer_Edit_Tab_View extends Mage_Adminhtml_Block_Customer_Edit_Tab_View implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('trader/tab/view.phtml');
    }

    public function getTabLabel()
    {
        $getparams = $this->getRequest()->getParams();
        if(isset($getparams['fromdd'])){
            return Mage::helper('customer')->__('Trader View');
        } else{
            return Mage::helper('customer')->__('Customer View');
        }
    }

    public function getTabTitle()
    {
        $getparams = $this->getRequest()->getParams();
        if(isset($getparams['fromdd'])){
            return Mage::helper('customer')->__('Trader View');
        } else{
            return Mage::helper('customer')->__('Customer View');
        }
    }

}
