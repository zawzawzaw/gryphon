<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Subscriptions
 *
 * @author mumate
 */
class Aemtech_Subscriptions_Model_Mysql4_Subscriptions extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
//        parent::_construct();
        $this->_init('subscriptions/subscriptions', 'customersubscription_id');
    }

}
