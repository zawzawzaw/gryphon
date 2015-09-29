<?php
class Manic_Breadcrumb_Model_Observer {
    public function fullBreadcrumbCategoryPath(Varien_Event_Observer $observer) {
        $current_product = Mage::registry('current_product');

        if( $current_product ) {
            // print_r($current_product->getData()); exit();
            $categories = $current_product->getCategoryCollection()->addAttributeToSelect('name');
            // print_r($categories->getData());exit();
            foreach( $categories as $category ) {
                if($category->getData('parent_id')==5) {
                    Mage::unregister('current_category');
                    Mage::register('current_category', $category);
                }
            }
        }
    }
}
?>