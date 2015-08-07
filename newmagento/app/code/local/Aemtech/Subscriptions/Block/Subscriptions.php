<?php

class Aemtech_Subscriptions_Block_Subscriptions extends Mage_Core_Block_Template {

    public function getFormAction()
    {
        return $this->getUrl('*/*/save');
    }	

}
