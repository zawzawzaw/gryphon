<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Customercustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Customercustomiser_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_countryCollection;
    protected $_subscription;

    public function getCountryHtmlSelect()
    {
        $countryId = ($this->getAddress()) ? $this->getAddress()->getCountryId() : null;

        if (is_null($countryId)) {
            $countryId = Mage::helper('core')->getDefaultCountry();
        }

        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName('country_id')
            ->setId('country_id')
            ->setTitle(Mage::helper('checkout')->__('Country'))
            ->setClass('validate-select')
            ->setValue($countryId)
            ->setOptions($this->getCountryOptions());

        return $select->getHtml();
    }

    public function getCountryOptions()
    {
        $options    = false;
        $useCache   = Mage::app()->useCache('config');
        if ($useCache) {
            $cacheId    = 'DIRECTORY_COUNTRY_SELECT_STORE_' . Mage::app()->getStore()->getCode();
            $cacheTags  = array('config');
            if ($optionsCache = Mage::app()->loadCache($cacheId)) {
                $options = unserialize($optionsCache);
            }
        }

        if ($options == false) {
            $options = $this->getCountryCollection()->toOptionArray();
            if ($useCache) {
                Mage::app()->saveCache(serialize($options), $cacheId, $cacheTags);
            }
        }
        return $options;
    }

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function getAddress()
    {
        return $this->getCustomer()->getPrimaryBillingAddress();
    }

    public function getCountryCollection()
    {
        if (!$this->_countryCollection) {
            $this->_countryCollection = Mage::getSingleton('directory/country')->getResourceCollection()
                ->loadByStore();
        }
        return $this->_countryCollection;
    }

    public function getSubscriptionObject()
    {
        if(is_null($this->_subscription)) {
            $this->_subscription = Mage::getModel('newsletter/subscriber')->loadByCustomer($this->getCustomer());
        }

        return $this->_subscription;
    }

    public function getIsSubscribed()
    {
        return $this->getSubscriptionObject()->isSubscribed();
    }

    public function getGender($customer)
    {
        return $customer->getResource()
            ->getAttribute('gender')
            ->getSource()
            ->getOptionText($customer->getGender());
    }
}
