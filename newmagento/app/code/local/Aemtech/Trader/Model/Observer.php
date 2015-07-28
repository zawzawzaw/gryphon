<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Observer
 *
 * @author niranjan
 */
class Aemtech_Trader_Model_Observer {

    public function saveCategoryData($observer) {
        $catIDs = "";
        $category = $observer->getEvent()->getDataObject()->getId();
        $postparams = Mage::app()->getFrontController()->getRequest()->getParams();

        //Mage::log($category);
        //Mage::log($postparams);
        if (isset($postparams['applytosubcats'])) {
            $applyToSubCat = "Yes";
        } else {
            $applyToSubCat = "No";
        }
        $catIDs = $category . ",";
        if ($applyToSubCat == 'Yes') {
            $collection = Mage::getModel('catalog/category')->getCollection()
                    ->addAttributeToFilter('is_active', 1) //only active categories
                    ->addAttributeToFilter('parent_id', $category);
            foreach ($collection as $subcategory) {
                $catIDs .= $subcategory->getId() . ",";
            }
            $catIDs = substr($catIDs, 0, strlen($catIDs) - 1);
        } else {
            $catIDs = $category;
        }

        foreach ($postparams['customer_group_discount'] as $key => $value) {
            $rulename = "Aemtech_DD_" . $catIDs . "_" . $key;
            if ((float) $value == 0) {
                $ruleid = $this->checkIfRuleExists($key, $value, $catIDs);
                if ($ruleid >= 1) {
                    $ruleDef = $this->getRuleDefinition($ruleid);
                    if ($catIDs == $ruleDef['catinids']) {
                        $this->removeCR($ruleid);
                    } else {
                        $this->addNotInCatToCR($ruleid, $catIDs, $rulename);
                    }
                    $this->removeTrader($catIDs, $key);
                }
            } else {


                $ruleId = $this->createCRule($rulename, $key, $value, $catIDs);
                $subcatlist = explode(",", $catIDs);
                if (count($subcatlist) >= 2) {
                    foreach ($subcatlist as $value1) {
                        $model = Mage::getModel('trader/trader')
                                ->setCategoryId($value1)
                                ->setCustomerGrpId($key)
                                ->setDiscount($value)
                                ->setPriceRuleId($ruleId)
                                ->setApplyToSubCat($applyToSubCat)
                                ->setUpdateTime(now())
                                ->save();
                    }
                } else {
                    $model = Mage::getModel('trader/trader')
                            ->setCategoryId($category)
                            ->setCustomerGrpId($key)
                            ->setDiscount($value)
                            ->setPriceRuleId($ruleId)
                            ->setApplyToSubCat($applyToSubCat)
                            ->setUpdateTime(now())
                            ->save();
                }
            }
        }
        $CatalogPriceRule = Mage::getModel('catalogrule/rule')->getCollection();
        $CatalogPriceRule->applyAll();
        //save the data for trader discount
    }

    protected function removeTrader($categoryid, $customergrpid) {
        if (strpos($categoryid, ",")) {
            $catids = explode(",", $categoryid);
            foreach ($catids as $catids) {
                $model = Mage::getModel('trader/trader')->getCollection()
                                ->addFieldToFilter('category_id', array('eq' => $catids))
                                ->addFieldToFilter('customer_grp_id', array('eq' => $customergrpid))->getFirstItem();
                Mage::log($model->getSelect());
                $model->delete();
            }
        }
    }

    protected function removeCR($ruleid) {
        $CatalogPriceRule = Mage::getModel('catalogrule/rule')->getCollection()
                        ->addFieldToFilter('rule_id', $ruleid)->getFirstItem();
        $CatalogPriceRule->delete();
        $CatalogPriceRule->applyAll();
    }

    protected function checkIfRuleExists($customergrpid, $discount, $categoryid) {
        $model = Mage::getModel('trader/trader')->getCollection()
                ->addFieldToFilter('category_id', array('in' => explode(",", $categoryid)))
                ->addFieldToFilter('customer_grp_id', array('eq' => $customergrpid));
        if (count($model)) {
            foreach ($model as $value) {
                return $value->getPriceRuleId();
            }
        } else {
            return 0;
        }
    }

    protected function getRuleDefinition($ruleId) {
        $CatalogPriceRule = Mage::getModel('catalogrule/rule')->load($ruleId);
        $ruleDef = array(
            'name' => $CatalogPriceRule->getName(),
            'catinids' => unserialize($CatalogPriceRule->getConditionsSerialized()),
        );
        return $ruleDef;
    }

    protected function addNotInCatToCR($ruleid, $catid, $rulename) {
        $CatalogPriceRule = Mage::getModel('catalogrule/rule')->getCollection()
                        ->addFieldToFilter('rule_id', $ruleid)->getFirstItem();
        if ($CatalogPriceRule->getName() == $rulename) {
            $CatalogPriceRule->delete();
        } else {
            $catCondition = Mage::getModel('catalogrule/rule_condition_product')
                    ->setType('catalogrule/rule_condition_product')
                    ->setAttribute('category_ids')
                    ->setAggrigator('all')
                    ->setOperator('!()')
                    ->setValue($catid);
            $CatalogPriceRule->getConditions()->addCondition($catCondition);
            $CatalogPriceRule->save();
            $CatalogPriceRule->applyAll();
        }
    }

    protected function createCRule($rulename, $customergrpid, $discount, $categoryid) {
        $ruleid = $this->checkIfRuleExists($customergrpid, $discount, $categoryid);
        if ($ruleid >= 1) {
            $this->addNotInCatToCR($ruleid, $categoryid, $rulename);
            $this->removeTrader($categoryid, $customergrpid);
        }
        $name = $rulename; // name of Shopping Cart Price Rule
        $websiteId = 1;
        $customerGroupId = $customergrpid;
        $actionType = 'by_percent'; // discount by percentage 
        $discount = $discount; // percentage discount 

        $CatalogPriceRule = Mage::getModel('catalogrule/rule');
        $CatalogPriceRule
                ->setName($name)
                ->setDescription('')
                ->setIsActive(1)
                ->setWebsiteIds(array($websiteId))
                ->setCustomerGroupIds(array($customerGroupId))
                ->setFromDate('')
                ->setToDate('')
                ->setSortOrder('')
                ->setSimpleAction($actionType)
                ->setDiscountAmount($discount)
                ->setStopRulesProcessing(1);

        $catCondition = Mage::getModel('catalogrule/rule_condition_product')
                ->setType('catalogrule/rule_condition_product')
                ->setAttribute('category_ids')
                ->setAggrigator('all')
                ->setOperator('()')
                ->setValue($categoryid);
        try {
            $CatalogPriceRule->getConditions()->addCondition($catCondition);
            $CatalogPriceRule->save();
            $CatalogPriceRule->applyAll();
            return $CatalogPriceRule->getId();
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError(Mage::helper('catalog')->__($e->getMessage()));
            return;
        }
    }

    /**
     * Check Captcha On Register User Page
     *
     * @param Varien_Event_Observer $observer
     * @return Mage_Captcha_Model_Observer
     */
    public function checkUserCreate($observer) {
        $formId = 'trader_register';
        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
        if ($captchaModel->isRequired()) {
            $controller = $observer->getControllerAction();
            $postparams = Mage::app()->getFrontController()->getRequest()->getParams();
            $formtype = $postparams['formtype'];
            $userCaptcha = $this->_getCaptchaString($controller->getRequest(), $formId);
            if (!$captchaModel->isCorrect($userCaptcha)) {
                //Mage::getSingleton('customer/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                Mage::getSingleton('customer/session')->setCustomerFormData($controller->getRequest()->getPost());
                $session = Mage::getSingleton("customer/session");
                $session->addError("Incorrect Captcha");
                if ($formtype == "trader") {  
                    $controller->getResponse()->setRedirect(Mage::getUrl('trader'));
                } else {
                    $controller->getResponse()->setRedirect(Mage::getUrl('*/*/create'));
                }
            }
        }
        return $this;
    }

    /**
     * Get Captcha String
     *
     * @param Varien_Object $request
     * @param string $formId
     * @return string
     */
    protected function _getCaptchaString($request, $formId) {
        $captchaParams = $request->getPost(Mage_Captcha_Helper_Data::INPUT_NAME_FIELD_VALUE);
        return $captchaParams[$formId];
    }

    public function customerSaveBefore($observer) {
        $customer = $observer->getRequest();
        $customerdata = Mage::app()->getRequest()->getPost();
        $customerID = $customerdata['customer_id'];

        $customerModel = Mage::getModel('customer/customer')
                ->load($customerID);

        $custdata = array('customerid' => $customerID, 'groupid' => $customerModel->getGroupId(), 'isactivated' => $customerModel->getIsactivated());

        Mage::getSingleton('core/session')->setRSPLDDPresaveCustdata(json_encode($custdata));
    }

    public function customerSaveAfter($observer) {
        $customer = $observer->getRequest();
        $customerdata = Mage::app()->getRequest()->getPost();
        $customerID = $customerdata['customer_id'];
        $customerModel = Mage::getModel('customer/customer')->load($customerID);

        Mage::log(print_r("Customer ID::" . $customerID, true));
        Mage::log(print_r("Pre Save Customer Data::" . Mage::getSingleton('core/session')->getRSPLDDPresaveCustdata(), true));
        $presaveCustData = json_decode(Mage::getSingleton('core/session')->getRSPLDDPresaveCustdata());
        //Mage::log(print_r("Pre Save Customer Is Activated::" . Mage::getSingleton('core/session')->getPresaveIsActive(), true));
        $postsaveCustData = array('customerid' => $customerID, 'groupid' => $customerModel->getGroupId(), 'isactivated' => $customerModel->getIsactivated());
        Mage::log(print_r("Post Save Customer Data::" . json_encode($postsaveCustData), true));

        $groups = Mage::getResourceModel('customer/group_collection')
                        ->addFieldToFilter('customer_group_id', array('gt' => 0))
                        ->addFieldToFilter('customer_group_code', array('in' => array('Trader-Temp', 'Trader-Regular', 'Trader-Priority', 'Trader-Premium')))
                        ->load()->toArray();
        foreach ($groups['items'] as $value) {
            $tradergrps[] = $value['customer_group_id'];
        }
        if ($presaveCustData->isactivated == 0 && $postsaveCustData['isactivated'] == 1) {
            //customer has been activated
            //check if the customer is from trader groups
            if (in_array($presaveCustData->groupid, $tradergrps)) {
                //yes the customer modified is trader group so need to send activation email.
                $customerModel->sendTraderActivatedEmail();
            }
        }

        //Mage::log(print_r("Post Save Customer Is Activated::" . $postsaveIsActive, true));
    }

}
