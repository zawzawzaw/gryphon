<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Importcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Importcustomiser_Block_Adminhtml_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     * @access public
     * @return Miragedesign_Importcustomiser_Block_Adminhtml_Import_Edit_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('adminhtml/relations_import/save'), 'method' => 'post', 'enctype'=>'multipart/form-data')
        );
        $this->setForm($form);
        $fieldset = $form->addFieldset('import_form', array('legend'=>Mage::helper('importcustomiser')->__('Import relations')));

        $values = Mage::getSingleton('importcustomiser/import_type')->getAllOptions(true);
        $fieldset->addField('type', 'select', array(
            'label'     => Mage::helper('importcustomiser')->__('Import type'),
            'name'      =>'type',
            'values'    => $values,
            'onchange'  => 'refreshOptions()',
            'required'  => true,
            'after_element_html' => Mage::helper('importcustomiser/adminhtml')->getTooltipHtml(
                Mage::helper('importcustomiser')->__('Import type'),
                Mage::helper('importcustomiser')->__('You can upload a file or manually input the data')
            )
        ));
        $relations = Mage::getSingleton('importcustomiser/import_relation')->getAllOptions(true);
        $fieldset->addField('relation', 'select', array(
            'label'     =>Mage::helper('importcustomiser')->__('Relation type'),
            'name'      =>'relation',
            'values'    => $relations,
            'required'  => true,
            'after_element_html' => Mage::helper('importcustomiser/adminhtml')->getTooltipHtml(
                Mage::helper('importcustomiser')->__('Relation type'),
                Mage::helper('importcustomiser')->__('Select what you want to import.').'<br />'.
                    '<ul><li>'.implode('</li><li>', Mage::getSingleton('importcustomiser/import_relation')->getOptionsAsArray(false)).'</li></ul>')
        ));

        $actions = Mage::getSingleton('importcustomiser/import_action')->getAllOptions(true);
        $fieldset->addField('action', 'select', array(
            'label'     =>Mage::helper('importcustomiser')->__('Action'),
            'name'      =>'action',
            'values'    => $actions,
            'required'  => true,
            'after_element_html' => Mage::helper('importcustomiser/adminhtml')->getTooltipHtml(
                Mage::helper('importcustomiser')->__('Action'),
                Mage::helper('importcustomiser')->__('Select the import behaviour. Merge current existing relations or replace them with the new ones'))
        ));
        $identifiers = Mage::getSingleton('importcustomiser/import_identifier')->getAllOptions(true);
        $fieldset->addField('identifier', 'select', array(
            'label'     =>Mage::helper('importcustomiser')->__('Work with'),
            'name'      =>'identifier',
            'values'    => $identifiers,
            'required'  => true,
            'after_element_html' => Mage::helper('importcustomiser/adminhtml')->getTooltipHtml(
                Mage::helper('importcustomiser')->__('Work with'),
                Mage::helper('importcustomiser')->__('Specify if the values you enter are product ids or product SKUs'))
        ));
        $parseTypes = Mage::getSingleton('importcustomiser/import_parse')->getAllOptions(true);
        $fieldset->addField('parse', 'select', array(
            'label'     =>Mage::helper('importcustomiser')->__('Import rules'),
            'name'      =>'parse',
            'values'    => $parseTypes,
            'required'  => true,
            'after_element_html' => Mage::helper('importcustomiser/adminhtml')->getTooltipHtml(
                Mage::helper('importcustomiser')->__('Import rules'),
                Mage::helper('importcustomiser')->__('You can define from here how the import should work.<ul><li><strong>Relate all on one line to first product in line.</strong> - first product in the row will be considered as the main product. All others will be added as relations to it.</li><li><strong>Relate all products on one line</strong> - all products on one line will be added as relations to all other products on the same line.</li><li><strong>Relate all products among themselves</strong> - all products will be added as relations to all products regardless of the line they are in.</li></ul>'))
        ));

        $fieldset->addField('related', 'textarea', array(
            'label'     =>Mage::helper('importcustomiser')->__('Related products identifiers'),
            'name'      =>'related',
            'required'  => true,
            'after_element_html' => Mage::helper('importcustomiser/adminhtml')->getTooltipHtml(
                Mage::helper('importcustomiser')->__('Related products identifiers'),
                Mage::helper('importcustomiser')->__('Specify the product ids or SKUs to be imported. Use comma as a product separator and semicolon as a separator between product identifier and position. Example: 345,33:5,29. If you select import rule "Relate all on one line to first product in line" then products with identifiers 33 and 29 will be added as related to product 345. Product 33 will have the position 5 and 29 will have the default position 0 because it doesn\'t have one specified')),
            'note' => Mage::helper('importcustomiser')->__('')
        ));

        $fieldset->addField('import_file', 'file', array(
            'label'     =>Mage::helper('importcustomiser')->__('File to import'),
            'name'      =>'import_file',
            'required'  => true,
            'after_element_html' => Mage::helper('importcustomiser/adminhtml')->getTooltipHtml(
                Mage::helper('importcustomiser')->__('File to import'),
                Mage::helper('importcustomiser')->__('Same rules apply as for the "Related products identifiers". The difference is that the values are read from a csv file.'))
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        if ($data = Mage::getSingleton('adminhtml/session')->getRelationImportData(true)){
            $form->setValues($data);
        }
        return parent::_prepareForm();
    }
}