<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Importcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Importcustomiser_Model_Import_Action extends Miragedesign_Importcustomiser_Model_Import_Abstract
{
    /**
     * replace existing relations
     */
    const ACTION_REPLACE    = 1;

    /**
     * merge existing with new
     */
    const ACTION_MERGE      = 2;

    /**
     * get options as array: var[] = array('value'=>value, 'label'=>label)
     * @access public
     * @param bool $withEmpty
     * @return array
     */
    public function getAllOptions($withEmpty = false)
    {
        if (is_null($this->_options)) {
            $this->_options = array();
            $this->_options[] = array(
                'label' => Mage::helper('importcustomiser')->__('Replace existing relations'),
                'value' => self::ACTION_REPLACE,
            );
            $this->_options[] = array(
                'label' => Mage::helper('importcustomiser')->__('Merge with existing relations'),
                'value' => self::ACTION_MERGE,
            );
            $this->_dispatchEvent();
        }

        $options = $this->_options;

        if ($withEmpty) {
            array_unshift($options, array('label'=>'', 'value'=>''));
        }

        return $options;
    }

    /**
     * getter for event name
     * @access public
     * @return mixed
     */
    public function getEventName()
    {
        return 'importcustomiser_get_options_action';
    }
}