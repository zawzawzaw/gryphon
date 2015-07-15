<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Catalogcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Catalogcustomiser_Block_Sidebar extends Mage_Core_Block_Template
{
    const SPLIT_COLUMN_NUMBER = 6;

    public function getCategories()
    {
        $allCategories = array();
        $parentCategory = Mage::getModel('catalog/category')->load(56); // Root Store category
        $categories = $parentCategory->getChildrenCategories();
        $allowedCategoryIds = array(4, 5, 6, 7, 88, 93, 98); // Only Collections, Types, Region, Caffeine Level, Moods, Aroma & Proficiency allowed

        foreach ($categories as $category) {
            if (in_array($category->getId(), $allowedCategoryIds)) {
                $allCategories[$category->getId()] = array('category' => $category, 'subCategories' => array());
                $subCategories = $category->getChildrenCategories();
                foreach ($subCategories as $subCategory) {
                    $allCategories[$category->getId()]['subCategories'][] = $subCategory;
                }
            }
        }

        return $allCategories;
    }

    public function getSplitColumnNumber()
    {
        return self::SPLIT_COLUMN_NUMBER;
    }
}
