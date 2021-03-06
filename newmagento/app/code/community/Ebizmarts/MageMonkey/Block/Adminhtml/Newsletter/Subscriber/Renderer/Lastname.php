<?php
/**
 * Created by PhpStorm.
 * User: santisp
 * Date: 22/05/15
 * Time: 05:23 PM
 */
class Ebizmarts_MageMonkey_Block_Adminhtml_Newsletter_Subscriber_Renderer_Lastname extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{

    public function render(Varien_Object $row)
    {
        $subscriberLastName = $row->getData('subscriber_lastname');
        $customerLastName = $row->getData('customer_lastname');
        if($subscriberLastName){
            return $subscriberLastName;
        }elseif($customerLastName){
            return $customerLastName;
        }else{
            return '----';
        }
    }
}