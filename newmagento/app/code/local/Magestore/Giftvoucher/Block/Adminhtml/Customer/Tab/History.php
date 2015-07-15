<?php

class Magestore_Giftvoucher_Block_Adminhtml_Customer_Tab_History extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('historyGrid');
        $this->setDefaultSort('history_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $customerId = $this->getRequest()->getParam('customer_id');
        // $sort = $this->getRequest()->getParam('sort');
        if (!$customerId)
            $customerId = Mage::registry('current_customer')->getId();
        $collection = Mage::getModel('giftvoucher/credithistory')->getCollection()
                ->addFieldToFilter('customer_id', $customerId);
//        if (!$sort)
//            $collection->setOrder('history_id', 'DESC');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('history_id', array(
            'header' => Mage::helper('giftvoucher')->__('ID'),
            'align' => 'left',
            'width' => '50px',
            'type' => 'number',
            'index' => 'history_id',
        ));
        $this->addColumn('action', array(
            'header' => Mage::helper('giftvoucher')->__('Action'),
            'align' => 'left',
            'index' => 'action',
            'type' => 'options',
            'options' => Mage::getSingleton('giftvoucher/creditaction')->getOptionArray(),
        ));

        $this->addColumn('balance_change', array(
            'header' => Mage::helper('giftvoucher')->__('Balance Change'),
            'align' => 'left',
            'index' => 'balance_change',
            'type' => 'currency',
            'currency' => 'currency',
        ));
        $this->addColumn('giftcard_code', array(
            'header' => Mage::helper('giftvoucher')->__('Gift Card Code'),
            'align' => 'left',
            'index' => 'giftcard_code',
        ));
        $this->addColumn('order_number', array(
            'header' => Mage::helper('giftvoucher')->__('Order'),
            'align' => 'left',
            'index' => 'order_number',
            'renderer' => 'giftvoucher/adminhtml_customer_tab_renderer',
        ));
        $this->addColumn('currency_balance', array(
            'header' => $this->__('Current Balance'),
            'align' => 'left',
            'index' => 'currency_balance',
            'type' => 'currency',
            'currency' => 'currency',
        ));
        $this->addColumn('created_date', array(
            'header' => $this->__('Created Time'),
            'align' => 'left',
            // 'type' => DateTime,
            'type' => 'datetime',
            'index' => 'created_date',
        ));


        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('giftvoucheradmin/adminhtml_giftvoucher/history', array(
                    '_current' => true,
                    'customer_id' => Mage::registry('current_customer')->getId(),
        ));
    }

}
