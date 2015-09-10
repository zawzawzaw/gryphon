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
        $isUpdate = 0;

        try {
            if ($page = $this->getRequest()->getParam("p")) {
                if ($page > 1) {
                    $isUpdate = 1;
                }
            }

            Mage::getSingleton('catalogsearch/advanced')->setCategoryId($this->getRequest()->getParam("categoryId"));
            Mage::getSingleton('catalogsearch/advanced')->addFilters($arrQuery);
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
