<?php
 
/**
 * Description of TraderController
 *
 * @author niranjan
 */
class Aemtech_Trader_Adminhtml_TraderController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
         
        $this->_title($this->__('trader'))->_title($this->__('Manage Traders'));

        if($this->getRequest()->getQuery('ajax')){
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('trader/list');

        /**
         * Append customers block to content
         */
        $this->_addContent(
                $this->getLayout()->createBlock('trader/adminhtml_trader', 'trader')
        );

        /**
         * Add breadcrumb item
         */
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Customers'), Mage::helper('adminhtml')->__('Traders'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Customers'), Mage::helper('adminhtml')->__('Manage Traders'));

        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _initCustomer($idFieldName = 'id')
    {
        $this->_title($this->__('Customers'))->_title($this->__('Manage Customers'));

        $customerId = (int) $this->getRequest()->getParam($idFieldName);
        $customer = Mage::getModel('customer/customer');

        if($customerId){
            $customer->load($customerId);
        }

        Mage::register('current_customer', $customer);
        return $this;
    }

    public function newAction()
    {
        $this->_forward('edit','customer','adminhtml');
    }

    public function editAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->_addBreadcrumb(Mage::helper('trader')->__('Edit'), Mage::helper('trader')->__('Edit'));
        $this->_addContent($this->getLayout()->createBlock('trader/adminhtml_trader_edit'))
                ->_addLeft($this->getLayout()->createBlock('trader/adminhtml_trader_edit_tabs'));
        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::registry('current_customer');

        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getCustomerData(true);

        // restore data from SESSION
        if($data){
            $request = clone $this->getRequest();
            $request->setParams($data);

            if(isset($data['account'])){
                /* @var $customerForm Mage_Customer_Model_Form */
                $customerForm = Mage::getModel('customer/form');
                $customerForm->setEntity($customer)
                        ->setFormCode('adminhtml_customer')
                        ->setIsAjaxRequest(true);
                $formData = $customerForm->extractData($request, 'account');
                $customerForm->restoreData($formData);
            }

            if(isset($data['address']) && is_array($data['address'])){
                /* @var $addressForm Mage_Customer_Model_Form */
                $addressForm = Mage::getModel('customer/form');
                $addressForm->setFormCode('adminhtml_customer_address');

                foreach(array_keys($data['address']) as $addressId){
                    if($addressId == '_template_'){
                        continue;
                    }

                    $address = $customer->getAddressItemById($addressId);
                    if(!$address){
                        $address = Mage::getModel('customer/address');
                        $customer->addAddress($address);
                    }

                    $formData = $addressForm->setEntity($address)
                            ->extractData($request);
                    $addressForm->restoreData($formData);
                }
            }
        }

        $this->_title($customer->getId() ? $customer->getName() : $this->__('New Customer'));

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('trader');

        $this->renderLayout();
    }

    public function exportCsvAction()
    {
        $fileName = 'traders.csv';
        $content = $this->getLayout()->createBlock('trader/adminhtml_trader_grid')
                ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export customer grid to XML format
     */
    public function exportXmlAction()
    {
        $fileName = 'customers.xml';
        $content = $this->getLayout()->createBlock('trader/adminhtml_trader_grid')
                ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

}
