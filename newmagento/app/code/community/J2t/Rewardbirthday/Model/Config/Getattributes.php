<?php

class J2t_Rewardbirthday_Model_Config_Getattributes {


    public function toOptionArray()
    {

        $config = $customerAttributes = Mage::getResourceModel('customer/address_attribute_collection');
        $arr_select = array();
        foreach($config as $conf){
            if ($conf->getFrontendInput() == 'text'){
                $arr_select[] = array('value' => $conf->getAttributeCode(), 'label'=>Mage::helper('j2trewardbirthday')->__($conf->getFrontendLabel()));
            }
        }


        return $arr_select;
    }

}
