<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Importcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Importcustomiser_Adminhtml_Relations_ImportController extends Mage_Adminhtml_Controller_Action
{
    /**
     * default action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->_forward('edit');
    }

    /**
     * show import form
     * @access public
     * @return void
     */
    public function editAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('importcustomiser')->__('Import product relations'))
            ->_title(Mage::helper('importcustomiser')->__('Import product relations'));
        $this->renderLayout();
    }

    /**
     * handle for submit
     * @access public
     * @return void
     */
    public function saveAction()
    {
        try {
            $type = $this->getRequest()->getPost('type');
            $this->_validateInput($type, 'importcustomiser/import_type', Mage::helper('importcustomiser')->__('Import type is not valid'));

            $relation = $this->getRequest()->getPost('relation');
            $this->_validateInput($relation, 'importcustomiser/import_relation', Mage::helper('importcustomiser')->__('Relation type is not valid'));

            $action = $this->getRequest()->getPost('action');
            $this->_validateInput($action, 'importcustomiser/import_action', Mage::helper('importcustomiser')->__('Action type is not valid'));

            $identifier = $this->getRequest()->getPost('identifier');
            $this->_validateInput($identifier, 'importcustomiser/import_identifier', Mage::helper('importcustomiser')->__('Identifier is not valid'));

            $parse = $this->getRequest()->getPost('parse');
            $this->_validateInput($parse, 'importcustomiser/import_parse', Mage::helper('importcustomiser')->__('Import rule is not valid'));

            if ($type == Miragedesign_Importcustomiser_Model_Import_Type::IMPORT_TYPE_DIRECT) {
                $rawData = $this->getRequest()->getPost('related');
            } elseif ($type == Miragedesign_Importcustomiser_Model_Import_Type::IMPORT_TYPE_UPLOAD){
                $csvFile = $_FILES['import_file']['tmp_name'];
                //TODO: maybe use Varien_Io_File to read contents;
                $rawData = file_get_contents($csvFile);
            } else {
                throw new Miragedesign_Importcustomiser_Exception(Mage::helper('importcustomiser')->__('Import type is not valid'));
            }
            $log = Mage::getSingleton('importcustomiser/handler')->importRelations($rawData, $relation, $action, $parse, $identifier);
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('importcustomiser')->__('Import successful'));
            $session = Mage::getSingleton('adminhtml/session');
            foreach ($log as $type=>$messages) {
                $method = 'add'.ucfirst($type);
                if (method_exists($session, $method)) {
                    foreach ($messages as $message) {
                        $session->$method($message);
                    }
                }
            }
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setRelationImportData($this->getRequest()->getPost());
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('importcustomiser')->__('An error occurred'));
            Mage::getSingleton('adminhtml/session')->setRelationImportData($this->getRequest()->getPost());
        }

        $this->_redirectReferer();
    }

    /**
     * validate input data against available values
     * @access protected
     * @param $param
     * @param $allowedParamsModel
     * @param $errorMessage
     * @return bool
     * @throws Miragedesign_Importcustomiser_Exception
     */
    protected function _validateInput($param, $allowedParamsModel, $errorMessage)
    {
        $allowedParams = Mage::getSingleton($allowedParamsModel)->getOptionsAsArray();
        if (!isset($allowedParams[$param])) {
            throw new Miragedesign_Importcustomiser_Exception($errorMessage);
        }
        return true;
    }
}