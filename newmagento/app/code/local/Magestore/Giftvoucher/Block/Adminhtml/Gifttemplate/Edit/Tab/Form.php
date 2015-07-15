<?php

class Magestore_Giftvoucher_Block_Adminhtml_Gifttemplate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('gifttemplate_form', array('legend' => Mage::helper('giftvoucher')->__('General Information')));
        if (Mage::getSingleton('adminhtml/session')->getGifttemplateData()) {
            $data = Mage::getSingleton('adminhtml/session')->getGifttemplateData();
            Mage::getSingleton('adminhtml/session')->setGifttemplateData(null);
        } elseif (Mage::registry('gifttemplate_data')) {
            $data = Mage::registry('gifttemplate_data')->getData();
        }
        $fieldset->addField('template_name', 'text', array(
            'label' => Mage::helper('giftvoucher')->__('Template name'),
            'required' => true,
            'name' => 'template_name',
        ));
        $fieldset->addField('status', 'select', array('label' => Mage::helper('giftvoucher')->__('Status'),
            'name' => 'status',
            'values' => Mage::getModel('giftvoucher/statusgifttemplate')->getOptionHash(),
        ));
        $pattern = isset($data['design_pattern']) ? $data['design_pattern'] : 1;
        $fieldset->addField('design_pattern', 'select', array('label' => Mage::helper('giftvoucher')->__('Template design'),
            'name' => 'design_pattern',
            'values' => Mage::getModel('giftvoucher/designpattern')->getOptions(),
            'onchange' => 'changePattern()',
            'after_element_html' => '
                <div id="demo_pattern" style="left: 530px;position: absolute;"><img id="pattern_demo" style="width:95%" src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'giftvoucher/template/pattern/GC_' . $pattern . '.jpg' . '" /> <div>'
            . '<script type="text/javascript">
                    function viewdemo() {
                        value=$("design_pattern").value;
                        new Ajax.Request("'
            . $this->getUrl('*/*/viewdemo', array('_current' => true))
            . '", {
                            parameters: {
                                         form_key: FORM_KEY,
                                         value: value,
                                         
                                         },
                            evalScripts: true,
                            onSuccess: function(transport) {
                                TINY.box.show("");
                                $("tinycontent").update(transport.responseText);
                            }
                        });
                    }
                </script>',
        ));
        $fieldset->addField('caption', 'text', array('label' => Mage::helper('giftvoucher')->__('Title'),
            'required' => true,
            'name' => 'caption',
            'note' => 'Title of Gift Cards using this template.',
        ));
        if (isset($data['caption']) && !$data['caption'])
            $data['caption'] = Mage::helper('giftvoucher')->__('Gift Card');
        $fieldset->addField('style_color', 'text', array('label' => Mage::helper('giftvoucher')->__('Style color'),
            'required' => true,
            'name' => 'style_color',
            'class' => 'color {required:false, adjust:false, hash:true}',
            'note' => 'Choose color of texts in Gift Cart title, value and gift code fields.',
        ));

        $fieldset->addField('text_color', 'text', array('label' => Mage::helper('giftvoucher')->__('Text color'),
            'required' => true,
            'name' => 'text_color',
            'class' => 'color {required:false, adjust:false, hash:true}',
            'note' => 'Choose color of other texts (fields’ title, notes, etc.).',
        ));
//        $fieldset->addField('images', 'image', array(
//            'name' => 'images[]', //declare this as array. Otherwise only one image will be uploaded
//            'multiple' => 'multiple', //declare input as 'multiple'
//            'label' => Mage::helper('giftvoucher')->__('Images'),
//            'title' => Mage::helper('giftvoucher')->__('Images'),
//            'required' => true,
//            'after_element_html' => '<p class="note" id="note_background_img"><span>' . Mage::helper('giftvoucher')->__('You could be choice multi file') . '</p></td><td><button class="scalable add" type="button" onClick="uploadFile()" class="scalable add add-select-row"><span>
//            ' . Mage::helper("giftvoucher")->__("Upload") . '</span>
//            </button>' . '<script type="text/javascript">
//                   
//                    function uploadFile() {
////                        alert("demo");
//                        data=$("edit_form").serialize();
//                        new Ajax.Request("'
//            . $this->getUrl('*/*/upload', array('_current' => true))
//            . '", {
//                            parameters: {
//                                         form_key: FORM_KEY,
//                                         data:data,
//                                         
//                                         },
//                            evalScripts: true,
//                            onSuccess : function(transport) {
//                              alert(transport.responseText);
//                            }
//                        });
//                    
//                        };
//                </script>',
//        ));

        $fieldset->addField('background_img', 'image', array('label' => Mage::helper('giftvoucher')->__('Background image'),
            'required' => false,
            'name' => 'background_img',
            'note' => Mage::helper('giftvoucher')->__('Support jpg, jpeg, gif, png files.'),
//            'onchange' => 'readURL(this)',
        ));
//        $fieldset->addField('background_upload', 'image', array(
//            'label' => Mage::helper('giftvoucher')->__('Background Upload'),
//            'name' => 'background_upload', //declare this as array. Otherwise only one image will be uploaded
//            'style' => 'display:none',
//        ));
        $fieldset->addField('notes', 'textarea', array('label' => Mage::helper('giftvoucher')->__('Notes'),
            'required' => false,
            'name' => 'notes',
            'note' => Mage::helper('giftvoucher')->__('{store_name}: your store\'s name<br/>
{store_url}: your store\'s url<br/>
{store_address}: your store\'s address'),
        ));
        if (isset($data['notes']) && !$data['notes'])
            $data['notes'] = Mage::helper('giftvoucher')->__('Please note that: Converting to cash is not allowed. You can use the Gift Card code or redeem it to credit balance to pay for your order at 
{store_url}');

        if (isset($data['background_img']) && $data['background_img']) {
            $dir_background = Mage::getBaseDir('media') . DS . 'giftvoucher' . DS . 'template' . DS . 'background' . DS . $data ['background_img'];

            if (file_exists($dir_background)) {
                $type = '';
                switch ($data['design_pattern']) {
                    case Magestore_Giftvoucher_Model_Designpattern::PATTERN_LEFT:
                        $type = 'left/';
                        break;
                    case Magestore_Giftvoucher_Model_Designpattern::PATTERN_TOP:
                        $type = 'top/';
                        break;
                    case Magestore_Giftvoucher_Model_Designpattern::PATTERN_CENTER:
                        $type = '';
                        break;
                }
                $data['background_img'] = Mage::getBaseUrl('media') . 'giftvoucher/template/background/' . $type . $data['background_img'];
            } else
                $data['background_img'] = '';
        }
        $form->setValues($data);
        return parent::_prepareForm();
    }

}
