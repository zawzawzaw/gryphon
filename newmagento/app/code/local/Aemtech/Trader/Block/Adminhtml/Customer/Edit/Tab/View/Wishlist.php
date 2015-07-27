<?php

/**
 * Adminhtml customer view wishlist block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Aemtech_Trader_Block_Adminhtml_Customer_Edit_Tab_View_Wishlist extends Mage_Adminhtml_Block_Customer_Edit_Tab_View_Wishlist
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_view_wishlist_grid');
        $this->setSortable(false);
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
        $getparams = $this->getRequest()->getParams();
        if(isset($getparams['fromdd'])){
            $this->setEmptyText(Mage::helper('customer')->__("There are no items in trader's wishlist at the moment"));
        } else{
            $this->setEmptyText(Mage::helper('customer')->__("There are no items in customer's wishlist at the moment"));
        }
    }

}
