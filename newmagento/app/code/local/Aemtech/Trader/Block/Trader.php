<?php 

class Aemtech_Trader_Block_Trader extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function _prepareLayout()
    {
        $this->currentcat = Mage::registry('current_category')->getId();
        $this->_group_list = Mage::getModel('customer/group')
                ->getCollection()
                ->addFieldToFilter('customer_group_code', array('like' => 'Trader%'))
                ->addFieldToFilter('customer_group_code', array('neq' => 'Trader-Temp'));
        $this->setTemplate('trader/trader.phtml');
        return parent::_prepareLayout();
    }

    public function getTrader()
    {
        
        if(!$this->hasData('trader')){
            $this->setData('trader', Mage::registry('trader'));
        }
        return $this->getData('trader');
    }

}
