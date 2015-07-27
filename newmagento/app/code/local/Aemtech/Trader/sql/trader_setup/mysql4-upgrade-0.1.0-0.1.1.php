<?php

$installer = $this;
$installer->startSetup();

$installer->updateAttribute('customer', 'isactivated', array(
    'sort_order' => '100'
));
$installer->updateAttribute('customer', 'companyname', array(
    'sort_order' => '110'
));
$installer->updateAttribute('customer', 'registrationnumber', array(
    'sort_order' => '120'
));
$installer->updateAttribute('customer', 'registration_date_day', array(
    'sort_order' => '130'
));
$installer->updateAttribute('customer', 'registration_date_month', array(
    'sort_order' => '140'
));
$installer->updateAttribute('customer', 'registration_date_year', array(
    'sort_order' => '150'
));
$installer->updateAttribute('customer', 'type_of_establishment', array(
    'sort_order' => '160'
));
$installer->updateAttribute('customer', 'role_within_organization', array(
    'sort_order' => '170'
));
$installer->updateAttribute('customer', 'website', array(
    'sort_order' => '180'
));
$installer->updateAttribute('customer', 'tea_consumption', array(
    'sort_order' => '190'
));
$installer->updateAttribute('customer', 'security_question', array(
    'sort_order' => '200'
));
$installer->updateAttribute('customer', 'answer', array(
    'sort_order' => '210'
));
$installer->updateAttribute('customer', 'message', array(
    'sort_order' => '220'
));
$installer->updateAttribute('customer', 'type_of_mkt_sup_provided', array(
    'sort_order' => '230'
));
$installer->updateAttribute('customer', 'dofcontract', array(
    'sort_order' => '240'
));
$installer->updateAttribute('customer', 'tenure_of_contract_from', array(
    'sort_order' => '250'
));
$installer->updateAttribute('customer', 'tenure_of_contract_to', array(
    'sort_order' => '260'
));
$installer->updateAttribute('customer', 'mobile_no_2', array(
    'sort_order' => '270'
));
$installer->updateAttribute('customer', 'istrader', array(
    'sort_order' => '280'
));

//create shoping cart rule to apply 2% discount for traders 

$groups = Mage::getResourceModel('customer/group_collection')
                ->addFieldToFilter('customer_group_id', array('gt' => 0))
                ->addFieldToFilter('customer_group_code', array('in' => array('Trader-Temp', 'Trader-Regular', 'Trader-Priority', 'Trader-Premium')))
                ->load()->toArray();
foreach ($groups['items'] as $value) {
    $tradergrps[] = $value['customer_group_id'];
}

$data = array(
    'product_ids' => null,
    'name' => sprintf('Traders - 2%% discount'),
    'description' => null,
    'is_active' => 1,
    'website_ids' => array(1),
    'customer_group_ids' => $tradergrps,
    'coupon_type' => 1,
    'from_date' => null,
    'to_date' => null,
    'sort_order' => null,
    'is_rss' => 1,
    'rule' => array(
        'conditions' => array(
            array(
                'type' => 'salesrule/rule_condition_combine',
                'aggregator' => 'all',
                'value' => 1,
                'new_child' => null
            )
        )
    ),
    'simple_action' => 'by_percent',
    'discount_amount' => 2,
    'discount_qty' => 0,
    'discount_step' => null,
    'apply_to_shipping' => 0,
    'simple_free_shipping' => 0,
    'stop_rules_processing' => 0,
    'rule' => array(
        'actions' => array(
            array(
                'type' => 'salesrule/rule_condition_product_combine',
                'aggregator' => 'all',
                'value' => 1,
                'new_child' => null
            )
        )
    ),
    'store_labels' => array('2% Traders discount')
);

$model = Mage::getModel('salesrule/rule');
//$data = $this->_filterDates($data, array('from_date', 'to_date'));

$validateResult = $model->validateData(new Varien_Object($data));

if ($validateResult == true) {

    if (isset($data['simple_action']) && $data['simple_action'] == 'by_percent' && isset($data['discount_amount'])) {
        $data['discount_amount'] = min(100, $data['discount_amount']);
    }

    if (isset($data['rule']['conditions'])) {
        $data['conditions'] = $data['rule']['conditions'];
    }

    if (isset($data['rule']['actions'])) {
        $data['actions'] = $data['rule']['actions'];
    }

    unset($data['rule']);

    $model->loadPost($data);

    $model->save();
}

$this->removeAttribute('customer_address', 'mobile_no_2');
$installer->addAttribute('customer', 'mobile_no', array(
    'label' => 'Mobile Number',
    'type' => 'varchar',
    'input' => 'text',
    'sort_order' => '290',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'required' => false,
    'user_defined' => true,
));

Mage::getSingleton('eav/config')
        ->getAttribute('customer', 'mobile_no')
        ->setData('used_in_forms', array(
            'customer_account_edit', 'customer_account_create', 'adminhtml_customer'
        ))
        ->save();
$installer->endSetup();
