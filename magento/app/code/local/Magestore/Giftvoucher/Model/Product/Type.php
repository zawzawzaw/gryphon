<?php

class Magestore_Giftvoucher_Model_Product_Type extends Mage_Catalog_Model_Product_Type_Abstract {

    protected $_canConfigure = true;

    public function prepareForCart(Varien_Object $buyRequest, $product = null) {
        if (version_compare(Mage::getVersion(), '1.5.0', '>='))
            return parent::prepareForCart($buyRequest, $product);
        if (is_null($product))
            $product = $this->getProduct();
        $result = parent::prepareForCart($buyRequest, $product);
        if (is_string($result))
            return $result;
        reset($result);
        $product = current($result);
        $result = $this->_prepareGiftVoucher($buyRequest, $product);
        return $result;
    }

    protected function _prepareProduct(Varien_Object $buyRequest, $product, $processMode) {
        if (version_compare(Mage::getVersion(), '1.5.0', '<'))
            return parent::_prepareProduct($buyRequest, $product, $processMode);
        if (is_null($product))
            $product = $this->getProduct();
        if (!$buyRequest->getData('send_friend')) {
            $fields = array('recipient_name', 'recipient_email', 'message', 'day_to_send', 'timezone_to_send', 'recipient_address', 'notify_success');
            foreach ($fields as $field) {
                if ($buyRequest->getData($field))
                    $buyRequest->unsetData($field);
            }
        }
        $result = parent::_prepareProduct($buyRequest, $product, $processMode);
        if (is_string($result))
            return $result;
        reset($result);
        $product = current($result);
        $result = $this->_prepareGiftVoucher($buyRequest, $product);
        return $result;
    }

    protected function _prepareGiftVoucher(Varien_Object $buyRequest, $product) {
        if (Mage::app()->getStore()->isAdmin())
            $store = Mage::getSingleton('adminhtml/session_quote')->getStore();
        else
            $store = Mage::app()->getStore();

        $amount = $buyRequest->getAmount();          
        $baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        $baseCurrency = Mage::getModel('directory/currency')->load($baseCurrencyCode);
        $currentCurrency = Mage::getModel('directory/currency')->load($currentCurrencyCode);
        $baseAmount = $baseCurrency->convert($amount, $currentCurrency);              
        $baseValue = $amount;
        $fnPrice = 0;
        if ($amount) {
            $giftAmount = Mage::helper('giftvoucher/giftproduct')->getGiftValue($product);
            switch ($giftAmount['type']) {
                case 'range':
                    if ($amount < $this->convertPrice($product, $giftAmount['from'])) {
                        $amount = $this->convertPrice($product, $giftAmount['from']);
                        $baseValue = $giftAmount['from'];
                    } elseif ($amount > $this->convertPrice($product, $giftAmount['to'])) {
                        $amount = $this->convertPrice($product, $giftAmount['to']);
                        $baseValue = $giftAmount['to'];
                    } else {
                        $baseCurrencyCode = $store->getBaseCurrencyCode();
                        $currentCurrencyCode = $store->getCurrentCurrencyCode();

                        $baseCurrency = Mage::getModel('directory/currency')->load($baseCurrencyCode);
                        $currentCurrency = Mage::getModel('directory/currency')->load($currentCurrencyCode);

                        // convert price from current currency to base currency
                        if ($amount > 0) {
                            $amount = $amount * $amount / $baseCurrency->convert($amount, $currentCurrency);
                            $baseValue = $amount;
                        } else {
                            $amount = 0;
                            $baseValue = 0;
                        }
                    }

                    $fnPrice = $amount;
                    if ($giftAmount['gift_price_type'] == 'percent') {
                        $fnPrice = $fnPrice * $giftAmount['gift_price_options'] / 100;
                    }
                    break;
                case 'dropdown':
                    if (!empty($giftAmount['options'])) {
                        $check = false;
                        $giftDropdown = array();
                        for ($i = 0; $i < count($giftAmount['options']); $i++) {
                            $giftDropdown[$i] = $this->convertPrice($product, $giftAmount['options'][$i]);
                            if ($amount == $giftDropdown[$i]) {
                                $check = true;
                                $baseValue = $giftAmount['options'][$i];
                            }
                        }
                        if (!$check) {
                            $amount = $giftAmount['options'][0];
                            $baseValue = $giftAmount['options'][0];
                        }

                        $fnPrices = array_combine($giftDropdown, $giftAmount['prices']);
                        $fnPrice = $fnPrices[$amount];
                    }
                    break;
                case 'static':
                    if ($amount != $this->convertPrice($product, $giftAmount['value'])) {
                        $amount = $giftAmount['value'];
                    }
                    $baseValue = $giftAmount['value'];
                    $fnPrice = $giftAmount['gift_price'];
                    break;
                default:
                    return Mage::helper('giftvoucher')->__('Please enter Gift Card information.');
            }
        } else
            return Mage::helper('giftvoucher')->__('Please enter Gift Card information.');

        $buyRequest->setAmount($amount);
        $product->addCustomOption('base_gc_value', $baseValue);
        $product->addCustomOption('base_gc_currency', Mage::app()->getStore()->getBaseCurrencyCode());
        $product->addCustomOption('gc_product_type', $giftAmount['type']);
        $product->addCustomOption('price_amount', $fnPrice);

        foreach (Mage::helper('giftvoucher')->getFullGiftVoucherOptions() as $key => $label) {
            if ($value = $buyRequest->getData($key))
                $product->addCustomOption($key, $value);
        }
        if (!Mage::registry('haitv_product_' . $product->getId()))
            Mage::register('haitv_product_' . $product->getId(), $product);
        return array($product);
    }

    public function isVirtual($product = null) {
        if (is_null($product))
            $product = $this->getProduct();
        if (!Mage::helper('giftvoucher')->getInterfaceConfig('postoffice', $product->getStoreId()))
            return true;

        $productOption = $this->getProduct($product)->getCustomOption('recipient_ship');
        if (!$productOption)
            return true;
        else
            return false;
    }

    public function hasRequiredOptions($product = null) {
        return true;
    }

    public function canConfigure($product = null) {
        return TRUE;
    }

    public function convertPrice($product, $price) {
        $includeTax = ( Mage::getStoreConfig('tax/display/type') != 1 );
        if (Mage::app()->getStore()->isAdmin())
            $store = Mage::getSingleton('adminhtml/session_quote')->getStore();
        else
            $store = Mage::app()->getStore();

        $priceWithTax = Mage::helper('tax')->getPrice($product, $price, $includeTax);
        return $store->convertPrice($priceWithTax);
    }

}
