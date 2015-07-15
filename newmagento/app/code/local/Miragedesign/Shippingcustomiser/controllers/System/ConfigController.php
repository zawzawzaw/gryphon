<?php

/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Shippingcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Shippingcustomiser_System_ConfigController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Export shipping table rates in csv format
     *
     */
    public function exportcustomrateAction()
    {
        $fileName = 'customrates.csv';
        /** @var $gridBlock Mage_Adminhtml_Block_Shipping_Carrier_Tablerate_Grid */
        $gridBlock = $this->getLayout()->createBlock('shippingcustomiser/adminhtml_shipping_carrier_customrate_grid');
        $website = Mage::app()->getWebsite($this->getRequest()->getParam('website'));

        if ($this->getRequest()->getParam('conditionName')) {
            $conditionName = $this->getRequest()->getParam('conditionName');
        } else {
            $conditionName = $website->getConfig('carriers/customrate/condition_name');
        }

        $gridBlock->setWebsiteId($website->getId())->setConditionName($conditionName);
        $content = $gridBlock->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
}