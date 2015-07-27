<?php

/**
 * Customer register form block
 *
 * @author Niranjan
 */
class Aemtech_Trader_Block_Customer_Form_Register extends Mage_Customer_Block_Form_Register
{

    protected function _prepareLayout()
    {
        $this->setTemplate('trader/register.phtml');
        return parent::_prepareLayout();
    }

}
