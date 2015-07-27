<?php

class Aemtech_Trader_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    { 
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

}
