<?php

/**
 * Description of Trader
 *
 * @author niranjan
 */
class Aemtech_Trader_Block_Adminhtml_Trader extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {   
        $this->_controller = 'adminhtml_trader';
        $this->_blockGroup = 'trader';
        $this->_headerText = Mage::helper('trader')->__('Manage Trader');
        //$this->_addButtonLabel = Mage::helper('trader')->__('Add New Trader');

        $this->_addButton('btnAdd', array(
            'label' => Mage::helper('trader')->__('Add New Trader'),
            'onclick' => "setLocation('" . $this->getUrl('adminhtml/customer/new/fromdd/true', array('page_key' => 'collection')) . "')",
            'class' => 'add'
        ));
        parent::__construct();
        //Remove original add button
        $this->_removeButton('add');
    }

}
