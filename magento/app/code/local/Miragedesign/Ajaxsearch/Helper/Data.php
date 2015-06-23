<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Ajaxsearch
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Ajaxsearch_Helper_Data extends Mage_Core_Helper_Abstract
{
    public $allowedAttributeCodes = array("categoryId", "is_new", "is_featured", "is_best_selling", "is_promotion", "next_page");
    
    public function verifyParams($requestParams) {
        $arrData = $requestParams;
        $arrQuery = array();

        foreach ($arrData as $key => $value) {
            if (in_array($key, $this->allowedAttributeCodes)) {
                if (stripos($value, ",") && $key != "categoryId") {
                    $arrCondition = explode(",", $value);
                    $arrQuery[$key] = array("in" => $arrCondition);
                } else {
                    $arrQuery[$key] = $value;
                }
            }
        }

        return $arrQuery;
    }
    
	public function getAttributeIdByName($attributeName) {
		$attributeSetId = Mage::getModel('eav/entity_attribute_set')
			->load($attributeName, 'attribute_set_name')
			->getAttributeSetId();
		
		return $attributeSetId;
	}    
	
	public function getAllOptionsByAttributeCode ($attributeCode) {
		$attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
				->setCodeFilter($attributeCode)
				->getFirstItem();
		$arrTemp = $attributeInfo->getSource()->getAllOptions(false);
		$arrOptions = array();
		
		foreach ($arrTemp as $temp) {			
			$arrOptions[$temp['value']] = $temp['label'];
		}
		
		return $arrOptions;
	}
}