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
class Aemtech_Subscriptions_Model_Subscriptions extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('subscriptions/subscriptions');
    }

    public function getGourmetSingaporeSubscriptions()
    {
        $skus = array('SGP-GUR-1M','SGP-GUR-3M','SGP-GUR-6M','SGP-GUR-12M');
        //use these SKU to load products
        $collection = Mage::getModel('catalog/product')
                ->getCollection()     
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('sku', array('in' => $skus))->setOrder('price', 'ASC');
        return $collection;
    }
    
    public function getGourmetInternationalSubscriptions()
    {
        $skus = array('INT-GUR-1M','INT-GUR-3M','INT-GUR-6M','INT-GUR-12M');
        //use these SKU to load products
        $collection = Mage::getModel('catalog/product')
                ->getCollection()     
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('sku', array('in' => $skus))->setOrder('price', 'ASC');
        return $collection;
    }
    
    public function getArtisanSingaporeSubscriptions()
    {
        $skus = array('SGP-ART-1M','SGP-ART-3M','SGP-ART-6M','SGP-ART-12M');
        //use these SKU to load products
        $collection = Mage::getModel('catalog/product')
                ->getCollection()     
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('sku', array('in' => $skus))->setOrder('price', 'ASC');
        return $collection;
    }
    
    public function getArtisanInternationalSubscriptions()
    {
        $skus = array('INT-ART-1M','INT-ART-3M','INT-ART-6M','INT-ART-12M');
        //use these SKU to load products
        $collection = Mage::getModel('catalog/product')
                ->getCollection()     
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('sku', array('in' => $skus))->setOrder('price', 'ASC');
        return $collection;
    }
}
