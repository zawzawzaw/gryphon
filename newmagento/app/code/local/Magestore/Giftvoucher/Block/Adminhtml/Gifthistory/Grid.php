<?php

class Magestore_Giftvoucher_Block_Adminhtml_Gifthistory_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('gifthistoryGrid');
        $this->setDefaultSort('history_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('giftvoucher/history')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('history_id', array(
            'header' => Mage::helper('giftvoucher')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'history_id',
        ));
        $this->addColumn('created_at', array(
            'header' => Mage::helper('giftvoucher')->__('Created Time'),
            'align' => 'left',
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '160px',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('giftvoucher')->__('Action'),
            'align' => 'left',
            'index' => 'action',
            'type' => 'options',
            'options' => Mage::getSingleton('giftvoucher/actions')->getOptionArray(),
        ));

        $this->addColumn('amount', array(
            'header' => Mage::helper('giftvoucher')->__('Value'),
            'align' => 'left',
            'index' => 'amount',
            'type' => 'currency',
            'currency' => 'currency',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('giftvoucher')->__('Status'),
            'align' => 'left',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('giftvoucher/status')->getOptionArray(),
        ));

        $this->addColumn('order_increment_id', array(
            'header' => Mage::helper('giftvoucher')->__('Order'),
            'align' => 'left',
            'index' => 'order_increment_id',
        ));

        $this->addColumn('comments', array(
            'header' => Mage::helper('giftvoucher')->__('Comment'),
            'align' => 'left',
            'index' => 'comments',
        ));

        $this->addColumn('extra_content', array(
            'header' => Mage::helper('giftvoucher')->__('Action Created by'),
            'align' => 'left',
            'index' => 'extra_content',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('giftvoucher')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('giftvoucher')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('history_id');
        $this->getMassactionBlock()->setFormFieldName('gifthistory');
        return $this;
    }

    public function getRowUrl($row) {
        return false;
    }

//    public function getGridUrl() {
//        return $this->getUrl('*/*/historygrid', array(
//                    '_current' => true,
//                    'id' => $this->getGiftvoucher(),
//        ));
//    }

}
