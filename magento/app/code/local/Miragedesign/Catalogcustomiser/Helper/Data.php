<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Catalogcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Miragedesign_Catalogcustomiser_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_typesSubCategories = null;

	public function getTopCategoryByProduct($product)	
	{
		$categoryIds = $product->getCategoryIds();
		
		for ($i = 0; $i < count($categoryIds); $i++) {
			// Default Category has ID 2
			if ($categoryIds[$i] != 2) { 				
				return $categoryIds[$i];
				break;
			}			
		}
		
		return false;
	}

    public function getOriginalUrl()
    {
        return Mage::app()->getRequest()->getServer('HTTP_REFERER');
    }

    public function getOriginalSortUrl($url)
    {
        $baseUrl = Mage::getBaseUrl();
        $originUrl = $this->getOriginalUrl();

        if (!$originUrl) {
            return $url;
        }

        if (false === strpos($originUrl, $baseUrl) ||
            false === strpos($url, $baseUrl)) {
            return '';
        }

        if (false === strpos($url, '?')) {
            return $url;
        }

        $search = substr($url, 0, strpos($url, '?'));
        $replace = (false !== strpos($originUrl, '?')) ? substr($originUrl, 0, strpos($originUrl, '?')) : $originUrl;
        $finalUrl = str_replace($search, $replace, $url);

        return $finalUrl;
    }

    public function getTypesCategory($product, $defaultName = '')
    {
        if (is_null($this->_typesSubCategories)) {
            $typeCategory = Mage::getModel('catalog/category')->load(5);
            $this->_typesSubCategories = explode(',', $typeCategory->getChildren());
        }

        $belongsToCategories = $product->getCategoryIds();

        $matchedCategories = array_values(array_intersect($belongsToCategories, $this->_typesSubCategories));

        if (count($matchedCategories)) {
            $matchedCategory = Mage::getModel('catalog/category')->load($matchedCategories[0]);
            return $matchedCategory->getName();
        }

        return $defaultName;
    }

    public function getGiftcardType()
    {
        return 'giftvoucher';
    }

    public function isGiftcardType($product)
    {
        return ($this->getGiftcardType() == $product->getTypeId()) ? true : false;
    }
}
