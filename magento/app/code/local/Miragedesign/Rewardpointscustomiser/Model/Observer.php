<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Rewardpointscustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Rewardpointscustomiser_Model_Observer extends Rewardpoints_Model_Observer
{
    public function setPointsOnProductPages(Varien_Event_Observer $observer)
    {
        //$this->appendAdminBlocks($observer);
        /* @var $block Mage_Core_Block_Abstract */
        
        if (!Mage::helper('rewardpoints')->isModuleActive()){
            return true;
        }
        
        $block              = $observer->getBlock();
        
        $show_info = Mage::getStoreConfig('rewardpoints/product_page/show_information', Mage::app()->getStore()->getId());
        $show_list_info = Mage::getStoreConfig('rewardpoints/product_page/show_list_points', Mage::app()->getStore()->getId());
        
        $show_duplicate = Mage::getStoreConfig('rewardpoints/product_page/duplicate_text_product_page', Mage::app()->getStore()->getId());
        $block_default = Mage::getStoreConfig('rewardpoints/product_page/block_default', Mage::app()->getStore()->getId());
        $block_default = (trim($block_default) != "") ? trim($block_default) : 'product.info.addtocart';
        $block_extra = Mage::getStoreConfig('rewardpoints/product_page/block_extra', Mage::app()->getStore()->getId());
        $block_extra = (trim($block_extra) != "") ? trim($block_extra) : 'product.info.configurable';
       
	    $block_default_array = explode("|", $block_default);
        $block_extra_array = explode("|", $block_extra);
 
        $arr_product_types = array("Mage_Catalog", "Mage_Bundle");
        
        if ($show_info){
            if (version_compare(Mage::getVersion(), '1.5.0', '>=')){
                $transport          = $observer->getTransport();
                $fileName           = $block->getTemplateFile();
                $thisClass          = get_class($block);
                //echo $block->getType();
                
                if ($block->getType() == 'catalog/product_price' || strpos($block->getType(), 'product_price') !== false){
                    if (in_array($block->getModuleName(), $arr_product_types) && (
                                (
                                    Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalog'
                                    && Mage::app()->getFrontController()->getRequest()->getControllerName() == 'category'
                                )
                                || // Add reward points to ajax search
                                (
                                    Mage::app()->getFrontController()->getRequest()->getRouteName() == 'ajaxsearch'
                                    && Mage::app()->getFrontController()->getRequest()->getControllerName() == 'ajax'
                                )
                                ||
                                (
                                    Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalogsearch'
                                    && (Mage::app()->getFrontController()->getRequest()->getControllerName() == 'result' || 'advanced')
                                )
                            )
                            && $show_list_info){ 
                    //if (in_array($block->getModuleName(), $arr_product_types) && Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalog'
                    //        && Mage::app()->getFrontController()->getRequest()->getControllerName() == 'category' && $show_list_info){
                        //echo $block->getTemplate();
                        //print_r($block->getProduct()->getEntityId());
                        if ($_product = $block->getProduct()){
                            $extraHtml = Mage::helper('rewardpoints/data')->getProductPointsText($_product, false, true);
                            $html = $transport->getHtml();
                            $transport->setHtml($html.$extraHtml);
                        }
                    }
                }
                if(
			($block->getNameInLayout() == $block_default || $block->getBlockAlias() == $block_default)
			|| (in_array($block->getNameInLayout(), $block_default_array) || in_array($block->getBlockAlias(), $block_default_array))	
		){
                    if (Mage::registry('current_product') && is_object(Mage::registry('current_product')) && Mage::registry('current_product')->getId()){
                        Mage::registry('current_product')->setPointDetails(NULL); 
                        Mage::registry('current_product')->setPointDetails(NULL);
                    }
                    
                    $html = $transport->getHtml();
                    $magento_block = Mage::getSingleton('core/layout');
                    $productsHtml = $magento_block->createBlock('rewardpoints/productpoints');
                    $productsHtml->setTemplate('rewardpoints/addtocart.phtml');
                    $extraHtml    = $productsHtml->toHtml();
                    
                    $transport->setHtml($extraHtml.$html);
                } else if(
			(($block->getNameInLayout() == $block_extra || $block->getBlockAlias() == $block_extra)
			|| (in_array($block->getNameInLayout(), $block_extra_array) || in_array($block->getBlockAlias(), $block_extra_array))	
			) && $show_duplicate){
                    $html = $transport->getHtml();
                    $extraHtml = '<div class="j2t-points-clone" id="j2t-points-clone" style="display:none;"></div>';
                    $transport->setHtml($html.$extraHtml);
                }
            } else {
                
                if ($block->getType() == 'catalog/product_price' || strpos($block->getType(), 'product_price') !== false){
                    if (in_array($block->getModuleName(), $arr_product_types) && (
                                (
                                    Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalog'
                                    && Mage::app()->getFrontController()->getRequest()->getControllerName() == 'category'
                                )
                                || 
                                (
                                    Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalogsearch'
                                    && (Mage::app()->getFrontController()->getRequest()->getControllerName() == 'result' || 'advanced')
                                )
                            )
                            && $show_list_info){ 
		    //if (in_array($block->getModuleName(), $arr_product_types) && Mage::app()->getFrontController()->getRequest()->getRouteName() == 'catalog'
                    //        && Mage::app()->getFrontController()->getRequest()->getControllerName() == 'category' && $show_list_info){
                        if ($_product = $block->getProduct()){
                            $extraHtml = Mage::helper('rewardpoints/data')->getProductPointsText($_product, false, true);
                            echo $extraHtml;
                        }
                    }
                }
                
		if(
                        ($block->getNameInLayout() == $block_default || $block->getBlockAlias() == $block_default)
                        || (in_array($block->getNameInLayout(), $block_default_array) || in_array($block->getBlockAlias(), $block_default_array))
                ){ 
                //if($block->getNameInLayout() == $block_default || $block->getBlockAlias() == $block_default){
                    $magento_block = Mage::getSingleton('core/layout');
                    $productsHtml = $magento_block->createBlock('rewardpoints/productpoints');
                    $productsHtml->setTemplate('rewardpoints/addtocart.phtml');
                    $extraHtml    = $productsHtml->toHtml();
                    echo $extraHtml;
                } else if(
                        (($block->getNameInLayout() == $block_extra || $block->getBlockAlias() == $block_duplicate)
                        || (in_array($block->getNameInLayout(), $block_extra_array) || in_array($block->getBlockAlias(), $block_extra_array))
                        ) && $show_duplicate){ 


			//if(($block->getNameInLayout() == $block_extra || $block->getBlockAlias() == $block_extra) && $show_duplicate){
                    echo '<div class="j2t-points-clone" id="j2t-points-clone" style="display:none;"></div>';
                }
            }
        } 
    }
}
