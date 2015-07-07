<?php

class Magestore_Giftvoucher_Block_Adminhtml_Product_View extends Mage_Catalog_Block_Product_View_Abstract {

    protected function _prepareLayout() {
        parent::_prepareLayout();
    }

    public function getGiftAmount($product) {

        $gift_value = Mage::helper('giftvoucher/giftproduct')->getGiftValue($product);
        $store = Mage::getSingleton('adminhtml/session_quote')->getStore();

        switch ($gift_value['type']) {
            case 'range':
                $gift_value['from'] = $this->convertPrice($product, $gift_value['from']);
                $gift_value['to'] = $this->convertPrice($product, $gift_value['to']);
                $gift_value['from_txt'] = $store->formatPrice($gift_value['from']);
                $gift_value['to_txt'] = $store->formatPrice($gift_value['to']);
                break;
            case 'dropdown':
                $gift_value['options'] = $this->_convertPrices($product, $gift_value['options']);
                $gift_value['prices'] = $this->_convertPrices($product, $gift_value['prices']);
                $gift_value['prices'] = array_combine($gift_value['options'], $gift_value['prices']);
                $gift_value['options_txt'] = $this->_formatPrices($gift_value['options']);
                break;
            case 'static':
                $gift_value['value'] = $this->convertPrice($product, $gift_value['value']);
                $gift_value['value_txt'] = $store->formatPrice($gift_value['value']);
                $gift_value['price'] = $this->convertPrice($product, $gift_value['gift_price']);
                break;
            default:
                $gift_value['type'] = 'any';
        }
        return $gift_value;
    }

    protected function _convertPrices($product, $basePrices) {
        //$store = Mage::app()->getStore();

        foreach ($basePrices as $key => $price)
            $basePrices[$key] = $this->convertPrice($product, $price);
        return $basePrices;
    }

    public function convertPrice($product, $price) {
        $includeTax = ( Mage::getStoreConfig('tax/display/type') != 1 );
        $store = Mage::getSingleton('adminhtml/session_quote')->getStore();

        $priceWithTax = Mage::helper('tax')->getPrice($product, $price, $includeTax);
        return $store->convertPrice($priceWithTax);
    }

    protected function _formatPrices($prices) {
        $store = Mage::getSingleton('adminhtml/session_quote')->getStore();
        foreach ($prices as $key => $price)
            $prices[$key] = $store->formatPrice($price, false);
        return $prices;
    }

    public function messageMaxLen() {
        return (int) Mage::helper('giftvoucher')->getInterfaceConfig('max');
    }

    public function enablePhysicalMail() {
        return Mage::helper('giftvoucher')->getInterfaceConfig('postoffice');
    }

    public function getFormConfigData() {
        $request = Mage::app()->getRequest();
        $action = $request->getRequestedRouteName() . '_' . $request->getRequestedControllerName() . '_' . $request->getRequestedActionName();
        if ($action == 'checkout_cart_configure' && $request->getParam('id')) {
            $request = Mage::app()->getRequest();
            $options = Mage::getModel('sales/quote_item_option')->getCollection()->addItemFilter($request->getParam('id'));
            $formData = array();
            foreach ($options as $option)
                $formData[$option->getCode()] = $option->getValue();
            return new Varien_Object($formData);
        }
        return new Varien_Object();
    }

    public function enableScheduleSend() {
        return Mage::helper('giftvoucher')->getInterfaceConfig('schedule');
    }

    public function getGiftAmountDescription() {
        if (!$this->hasData('gift_amount_description')) {
            $product = $this->getProduct();
            $this->setData('gift_amount_description', '');
            if ($product->getShowGiftAmountDesc()) {
                if ($product->getGiftAmountDesc()) {
                    $this->setData('gift_amount_description', $product->getGiftAmountDesc());
                } else {
                    $this->setData('gift_amount_description', Mage::helper('giftvoucher')->getInterfaceConfig('description')
                    );
                }
            }
        }
        return $this->getData('gift_amount_description');
    }

    public function getAvailableTemplate() {
        $templates = Mage::getModel('giftvoucher/gifttemplate')->getCollection()
                ->addFieldToFilter('status', '1');
        return $templates;
    }

    public function getAvailableTemplateAdmin() {
        $product = $this->getProduct();
        $product_template = $product->getGiftTemplateIds();
        if ($product_template) {
            $product_template = explode(',', $product_template);
        } else
            $product_template = array();

        $templates = Mage::getModel('giftvoucher/gifttemplate')->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('giftcard_template_id', array('in' => $product_template));

        return $templates->getData();
    }

    public function getPriceFormatJs() {
        $priceFormat = Mage::app()->getLocale()->getJsPriceFormat();
        return Mage::helper('core')->jsonEncode($priceFormat);
    }

    protected $_product;

    /**
     * Retrieve product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct() {
        if (!$this->hasData('product')) {
            $this->setData('product', Mage::registry('current_product'));
        }
        $product = $this->getData('product');
        if (is_null($product->getTypeInstance(true)->getStoreFilter($product))) {
            $product->getTypeInstance(true)->setStoreFilter(Mage::app()->getStore($product->getStoreId()), $product);
        }
        $this->_product = Mage::registry('haitv_product_' . $product->getId());
        return $product;
    }

    public function getOptionProduct($val) {
        if (!$this->_product) {
            $this->getProduct();
        }
        if ($this->_product) {
            $option = $this->_product->getCustomOptions();
            if ($option && isset($option[$val]) && $option[$val])
                return $option[$val]->getValue();
        }
        return '';
    }

    public function getAllowAttributes() {
        return Mage::helper('giftvoucher')->getFullGiftVoucherOptions();
    }

}
