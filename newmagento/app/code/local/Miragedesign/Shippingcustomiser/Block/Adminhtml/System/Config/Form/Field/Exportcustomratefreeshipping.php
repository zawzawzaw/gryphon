<?php
/** Author:: PG **/
class Miragedesign_Shippingcustomiser_Block_Adminhtml_System_Config_Form_Field_Exportcustomratefreeshipping extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $buttonBlock = $this->getLayout()->createBlock('adminhtml/widget_button');

        $params = array(
            'website' => $buttonBlock->getRequest()->getParam('website')
        );

        $data = array(
            'label' => Mage::helper('adminhtml')->__('Export Free Shipping Rate CSV'),
            'onclick' => 'setLocation(\'' . Mage::helper('adminhtml')->getUrl("*/*/exportcustomratefreeshipping", $params) . 'freeshippingrates.csv\' )',
            'class' => '',
        );

        $html = $buttonBlock->setData($data)->toHtml();

        return $html;
    }
}
