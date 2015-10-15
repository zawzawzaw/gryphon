<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Ajaxsearch
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Ajaxsearch_Model_Advanced extends Mage_CatalogSearch_Model_Advanced
{
    protected $_categoryIds;
    
    public function setCategoryId($categoryId) {
        if (stripos($categoryId, ",")) {
            $this->_categoryIds = explode(",", $categoryId);
        } else {
            $this->_categoryIds = $categoryId;
        }
    }
    
    public function getCategoryId() {
        return $this->_categoryIds;
    }

    /**
     * Retrieve advanced search product collection
     *
     * @return Mage_CatalogSearch_Model_Resource_Advanced_Collection
     */
    public function getProductCollection($filters=null){
        $feature_filter = false;
        if($filters!=null){
            foreach($filters as $key => $filter) {
                if($filter['attribute']=='is_featured') {
                    $feature_filter = true;
                }
            }
        }
        if (is_null($this->_productCollection)) {
            $collection = $this->_engine->getAdvancedResultCollection();
            $this->prepareProductCollection($collection, $feature_filter);
            if (!$collection) {
                return $collection;
            }
            $this->_productCollection = $collection;
        }

        return $this->_productCollection;
    }
    
    /**
     * Prepare product collection
     *
     * @param Mage_CatalogSearch_Model_Mysql4_Advanced_Collection $collection
     * @return Mage_Catalog_Model_Layer
     */
    public function prepareProductCollection($collection, $feature_filter)
    {
        // print_r($collection->getData()); exit();
        if($feature_filter) {
            $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addAttributeToSelect("external_link")
            ->setStore(Mage::app()->getStore())            
            ->addTaxPercents()
            ->addStoreFilter();
        }
        else {
            $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addAttributeToSelect("external_link")
                ->setStore(Mage::app()->getStore())
                ->addMinimalPrice()
                ->addTaxPercents()
                ->addStoreFilter();
        }

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);

        // Add category filter
        $categoriesId = $this->getCategoryId();

        if (is_array($categoriesId)) {
        	$collection->addCategoriesFilter($categoriesId);
        } else if ($categoriesId && !is_array($categoriesId)) {
            //Mage::getSingleton("catalog/layer")->setCurrentCategory(Mage::getModel('catalog/category')->load($categoriesId));
            $collection->addCategoryFilter(Mage::getModel('catalog/category')->load($categoriesId));//->setIsAnchor(true));
        }

        $collection->getSelect()
            ->group('e.entity_id');

        return $this;
    }
}
