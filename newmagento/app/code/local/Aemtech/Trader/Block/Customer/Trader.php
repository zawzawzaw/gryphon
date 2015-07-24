<?php

class Aemtech_Trader_Block_Customer_Trader extends Mage_Customer_Block_Form_Register
{

    public function _prepareLayout()
    { 
        $this->setTemplate('trader/trader.phtml');
        return parent::_prepareLayout();
    }

    public function getTrader()
    {
        if(!$this->hasData('trader'))
        {
            $this->setData('trader', Mage::registry('trader'));
        }
        return $this->getData('trader');
    }

}
