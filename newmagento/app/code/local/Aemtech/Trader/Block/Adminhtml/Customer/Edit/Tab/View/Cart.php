<?php

/**
 * Adminhtml customer cart items grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Aemtech_Trader_Block_Adminhtml_Customer_Edit_Tab_View_Cart extends Mage_Adminhtml_Block_Customer_Edit_Tab_View_Cart
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_view_cart_grid');
        $this->setDefaultSort('added_at', 'desc');
        $this->setSortable(false);
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
        $getparams = $this->getRequest()->getParams();
        if(isset($getparams['fromdd'])){
            $this->setEmptyText(Mage::helper('customer')->__('There are no items in trader\'s shopping cart at the moment'));
        } else{
            $this->setEmptyText(Mage::helper('customer')->__('There are no items in customer\'s shopping cart at the moment'));
        }
        
    }

}
