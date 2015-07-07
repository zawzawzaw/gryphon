<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Importcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Importcustomiser_Block_Adminhtml_Import_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'importcustomiser';
        $this->_controller = 'adminhtml_import';
        $this->_updateButton('save', 'label', Mage::helper('importcustomiser')->__('Submit'));
        $this->_removeButton('delete');
        $this->_removeButton('back');

        $this->_formScripts[] = "
            function refreshOptions(){
                var importType = $('type').value;
                var actionType = $('action').value;
                if (importType == ".Miragedesign_Importcustomiser_Model_Import_Type::IMPORT_TYPE_UPLOAD."){
                    $('import_file').up(1).show();
                    if (!$('import_file').hasClassName('required-entry')){
                        $('import_file').addClassName('required-entry');
                    }

                    $('related').up(1).hide();
                    $('related').removeClassName('required-entry');
                }
                else{
                    $('import_file').removeClassName('required-entry');
                    $('import_file').up(1).hide();

                    $('related').up(1).show();
                    if (!$('related').hasClassName('required-entry')){
                        $('related').addClassName('required-entry');
                    }
                }
            }
            document.observe(\"dom:loaded\", function() {
                refreshOptions();
            });
        ";
    }

    /**
     * get the edit form header
     * @access public
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('importcustomiser')->__('Import product relations');
    }
}