<?php

class Aemtech_Trader_Helper_Data extends Mage_Core_Helper_Data
{

    public function getRegisterUrl()
    {
        return $this->_getUrl('trader');
    }

}
