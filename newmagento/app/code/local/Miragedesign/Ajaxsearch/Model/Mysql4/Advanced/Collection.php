<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Ajaxsearch
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Ajaxsearch_Model_Mysql4_Advanced_Collection
    extends Mage_CatalogSearch_Model_Mysql4_Advanced_Collection
{
	public function addCategoriesFilter($categories)
    {
        $this->_productLimitationFilters['category_ids'] = $categories;
		
        if ($this->getStoreId() == Mage_Core_Model_App::ADMIN_STORE_ID) {
            $this->_applyZeroStoreProductLimitations();
        } else {
            $this->_applyProductLimitations();
        }

        return $this;
    }

    /**
     * Apply limitation filters to collection
     * Method allows using one time category product index table (or product website table)
     * for different combinations of store_id/category_id/visibility filter states
     * Method supports multiple changes in one collection object for this parameters
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _applyProductLimitations()
    {
        Mage::dispatchEvent('catalog_product_collection_apply_limitations_before', array(
            'collection'  => $this,
            'category_id' => isset($this->_productLimitationFilters['category_id'])
                ? $this->_productLimitationFilters['category_id']
                : null,
        ));
        $this->_prepareProductLimitationFilters();
        $this->_productLimitationJoinWebsite();
        $this->_productLimitationJoinPrice();
        $filters = $this->_productLimitationFilters;

//      if (!isset($filters['category_id']) && !isset($filters['visibility'])) {
//          return $this;
//      }
        // Addition: support for filtering multiple categories.
        if (!isset($filters['category_id']) && !isset($filters['category_ids']) && !isset($filters['visibility'])) {
            return $this;
        }

        $conditions = array(
            'cat_index.product_id=e.entity_id',
            $this->getConnection()->quoteInto('cat_index.store_id=?', $filters['store_id'])
        );
        if (isset($filters['visibility']) && !isset($filters['store_table'])) {
            $conditions[] = $this->getConnection()
                ->quoteInto('cat_index.visibility IN(?)', $filters['visibility']);
        }

        // Addition: support for filtering multiple categories.
        if (!isset($filters['category_ids'])) {
            if (!$this->getFlag('disable_root_category_filter')) {
                $conditions[] = $this->getConnection()->quoteInto('cat_index.category_id = ?', $filters['category_id']);
            }

            if (isset($filters['category_is_anchor'])) {
                $conditions[] = $this->getConnection()
                    ->quoteInto('cat_index.is_parent=?', $filters['category_is_anchor']);
            }
        } else {
            $conditions[] = $this->getConnection()->quoteInto('cat_index.category_id IN(' . implode(',', $filters['category_ids']) . ')', "");
        }


        $joinCond = join(' AND ', $conditions);
        $fromPart = $this->getSelect()->getPart(Zend_Db_Select::FROM);
        if (isset($fromPart['cat_index'])) {
            $fromPart['cat_index']['joinCondition'] = $joinCond;
            $this->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
        }
        else {
            $this->getSelect()->join(
                array('cat_index' => $this->getTable('catalog/category_product_index')),
                $joinCond,
                array('cat_index_position' => 'position')
            );
        }

        $this->_productLimitationJoinStore();

        Mage::dispatchEvent('catalog_product_collection_apply_limitations_after', array(
            'collection' => $this
        ));

        return $this;
    }

    /**
     * Apply limitation filters to collection base on API
     * Method allows using one time category product table
     * for combinations of category_id filter states
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _applyZeroStoreProductLimitations()
    {
        $filters = $this->_productLimitationFilters;

//        $conditions = array(
//            'cat_pro.product_id=e.entity_id',
//            $this->getConnection()->quoteInto('cat_pro.category_id=?', $filters['category_id'])
//        );
        // Addition: supprot for filtering multiple categories.
        $categoryCondition = null;
        if (!isset($filters['category_ids'])) {
            $categoryCondition = $this->getConnection()->quoteInto('cat_pro.category_id=?', $filters['category_id']);
        } else {
            $categoryCondition = $this->getConnection()->quoteInto('cat_pro.category_id IN(' . implode(',', $filters['category_ids']) . ')', "");
        }

        $conditions = array(
            'cat_pro.product_id=e.entity_id',
            $categoryCondition
        );

        $joinCond = join(' AND ', $conditions);

        $fromPart = $this->getSelect()->getPart(Zend_Db_Select::FROM);
        if (isset($fromPart['cat_pro'])) {
            $fromPart['cat_pro']['joinCondition'] = $joinCond;
            $this->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
        }
        else {
            $this->getSelect()->join(
                array('cat_pro' => $this->getTable('catalog/category_product')),
                $joinCond,
                array('cat_index_position' => 'position')
            );
        }
        $this->_joinFields['position'] = array(
            'table' => 'cat_pro',
            'field' => 'position',
        );

        return $this;
    }
}
