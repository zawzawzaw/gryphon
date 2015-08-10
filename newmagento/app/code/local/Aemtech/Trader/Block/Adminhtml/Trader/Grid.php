<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Trader
 *
 * @author niranjan
 */
class Aemtech_Trader_Block_Adminhtml_Trader_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('trader_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $groups = Mage::getResourceModel('customer/group_collection')
                        ->addFieldToFilter('customer_group_id', array('gt' => 0))
                        ->addFieldToFilter('customer_group_code', array('in' => array('Trader-Temp', 'Trader-Regular', 'Trader-Priority', 'Trader-Premium')))
                        ->load()->toArray();
        foreach($groups['items'] as $value){
            $grps[] = $value['customer_group_id'];
        }
        $collection = Mage::getResourceModel('customer/customer_collection')
                ->addNameToSelect()
                ->addAttributeToSelect('email')
				->addAttributeToSelect('isactivated')
                ->addAttributeToSelect('created_at')
                ->addAttributeToSelect('group_id')
                ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
                ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
                ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
                ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
                ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
                ->addFieldToFilter('group_id', array('in', $grps));
        // Mage::log((string) $collection->getSelect());
        $this->setCollection($collection);

        return;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('customer')->__('ID'),
            'width' => '50px',
            'index' => 'entity_id',
            'type' => 'number',
        ));
        $this->addColumn('name', array(
            'header' => Mage::helper('customer')->__('Name'),
            'index' => 'name'
        ));
        $this->addColumn('email', array(
            'header' => Mage::helper('customer')->__('Email'),
            'width' => '150',
            'index' => 'email'
        ));

        $groups = Mage::getResourceModel('customer/group_collection')
                ->addFieldToFilter('customer_group_id', array('gt' => 0))
                ->addFieldToFilter('customer_group_code', array('in' => array('Trader-Temp', 'Trader-Regular', 'Trader-Priority', 'Trader-Premium')))
                ->load()
                ->toOptionHash();

        $this->addColumn('group', array(
            'header' => Mage::helper('customer')->__('Group'),
            'width' => '100',
            'index' => 'group_id',
            'type' => 'options',
            'options' => $groups,
        ));

        $this->addColumn('Telephone', array(
            'header' => Mage::helper('customer')->__('Telephone'),
            'width' => '100',
            'index' => 'billing_telephone'
        ));

        $this->addColumn('billing_postcode', array(
            'header' => Mage::helper('customer')->__('ZIP'),
            'width' => '90',
            'index' => 'billing_postcode',
        ));

        $this->addColumn('billing_country_id', array(
            'header' => Mage::helper('customer')->__('Country'),
            'width' => '100',
            'type' => 'country',
            'index' => 'billing_country_id',
        ));

        $this->addColumn('billing_region', array(
            'header' => Mage::helper('customer')->__('State/Province'),
            'width' => '100',
            'index' => 'billing_region',
        ));

        $this->addColumn('customer_since', array(
            'header' => Mage::helper('customer')->__('Trader Since'),
            'type' => 'datetime',
            'align' => 'center',
            'index' => 'created_at',
            'gmtoffset' => true
        ));
		
		$isactivatedOptions = array(
			1 => 'Yes',
			0 => 'No'
		); // or you can fetch dynamically
		$this->addColumn('isactivated', array(
			'header'    => Mage::helper('customer')->__('Approved'),
			'index'     => 'isactivated',
			'type'      => 'options',
			'options'   => $isactivatedOptions,
			'align'     => 'left',
		));

        if(!Mage::app()->isSingleStoreMode()){
            $this->addColumn('website_id', array(
                'header' => Mage::helper('customer')->__('Website'),
                'align' => 'center',
                'width' => '80px',
                'type' => 'options',
                'options' => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(true),
                'index' => 'website_id',
            ));
        }
        $link = Mage::helper('adminhtml')->getUrl('adminhtml/customer/edit') . 'id/$entity_id/fromdd/true';
        $this->addColumn('action', array(
            'header' => Mage::helper('trader')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('trader')->__('Edit'),
                    'url' => $link,
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));


        $this->addExportType('trader/trader/exportCsv', Mage::helper('customer')->__('CSV'));
        $this->addExportType('trader/trader/exportXml', Mage::helper('customer')->__('Excel XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('customer');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('customer')->__('Delete'),
            'url' => $this->getUrl('adminhtml/customer/massDelete/fromdd/1'),
            'confirm' => Mage::helper('customer')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('newsletter_subscribe', array(
            'label' => Mage::helper('customer')->__('Subscribe to Newsletter'),
            'url' => $this->getUrl('adminhtml/customer/massSubscribe/fromdd/1')
        ));

        $this->getMassactionBlock()->addItem('newsletter_unsubscribe', array(
            'label' => Mage::helper('customer')->__('Unsubscribe from Newsletter'),
            'url' => $this->getUrl('adminhtml/customer/massUnsubscribe/fromdd/1')
        ));

        //$groups = $this->helper('customer')->getGroups()->toOptionArray();
        $groups = Mage::getResourceModel('customer/group_collection')
                        ->addFieldToFilter('customer_group_id', array('gt' => 0))
                        ->addFieldToFilter('customer_group_code', array('in' => array('Trader-Regular', 'Trader-Priority', 'Trader-Premium')))
                        ->load()->toOptionArray();
        array_unshift($groups, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('assign_group', array(
            'label' => Mage::helper('customer')->__('Assign a Customer Group'),
            'url' => $this->getUrl('adminhtml/customer/massAssignGroup/fromdd/1'),
            'additional' => array(
                'visibility' => array(
                    'name' => 'group',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('customer')->__('Group'),
                    'values' => $groups
                )
            )
        ));

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('trader/trader/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
         $link = Mage::helper('adminhtml')->getUrl('adminhtml/customer/edit') . 'id/$entity_id';
        return $this->getUrl('adminhtml/customer/edit', array('id' => $row->getId()))."fromdd/true";
    }

}
