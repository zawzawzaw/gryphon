<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Ajaxsearch
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Ajaxsearch_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function resultAction()
    {
        ini_set('display_errors',1);
        ini_set('display_startup_errors',1);
        error_reporting(-1);
        $result = array();
        $ajaxSearchHelper = Mage::helper("ajaxsearch");
        $arrQuery = $ajaxSearchHelper->verifyParams($this->getRequest()->getParams());
        // print_r($arrQuery); exit();
        $isUpdate = 0;

        // Mage::getSingleton('catalogsearch/advanced')->setCategoryId(56);        
        // print_r(Mage::getSingleton('catalogsearch/advanced')->getProductCollection()->addAttributeToFilter(
        //         array(
        //             array('attribute'=> 'is_new', 'finset' => 1),
        //             array('attribute'=> 'is_featured', 'finset' => 1),
        //             array('attribute'=> 'is_best_selling', 'finset' => 1),
        //             array('attribute'=> 'is_promotion', 'finset' => 1),
        //         )
        // )->getData());
        // exit();

        $filter = array();
        foreach ($arrQuery as $key => $value) {
            if($key!=='categoryId') {
                $filter['attribute'] = $key;
                $filter['finset'] = $value;    

                $filters[] = $filter;
            }           
        }

        try {
            if ($page = $this->getRequest()->getParam("p")) {
                if ($page > 1) {
                    $isUpdate = 1;
                }
            }

            Mage::getSingleton('catalogsearch/advanced')->setCategoryId($this->getRequest()->getParam("categoryId"));
            if(isset($filters) && !empty($filters))
                Mage::getSingleton('catalogsearch/advanced')->getProductCollection($filters)->addAttributeToFilter($filters)->getData();            

            // $arrQueryKeys = array_keys($arrQuery);
            // Mage::getSingleton('catalogsearch/advanced')->getProductCollection()->addFieldToFilter(
            //     array(
            //         array('attribute'=> $arrQueryKeys[0], '=' => $arrQuery[0]),
            //         array('attribute'=> $arrQueryKeys[1], '=' => $arrQuery[1]),
            //         array('attribute'=> $arrQueryKeys[2], '=' => $arrQuery[2])
            //     )
            // );
            //$resultNumber = Mage::getSingleton('catalogsearch/advanced')->getProductCollection()->getSize();
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('catalogsearch/session')->addError($e->getMessage());
            $this->_redirectError(Mage::getURL('*/*/'));
        }
        $this->loadLayout();

        if ($page > 1) {
            $this->getLayout()->getBlock("search_result_list")
                ->setTemplate('miragedesign/catalog/product/list.phtml');
        }

        $resultContent = $this->getLayout()->getBlock("content")->toHtml();

        // reset columns, order and limitation conditions

        $result["result_text"] = $resultContent;
        $result["no_result"] = Mage::getSingleton('catalogsearch/advanced')->getNoResult();
        $result["update"] = $isUpdate;

        if ($result["no_result"]) {
            $result["no_result_text"] = $this->__("Your query has not any result, we keep the last results");
        }

        @header('Content-type: application/json');
        echo json_encode($result);
        die();
    }
}
