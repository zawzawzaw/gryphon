<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Importcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Importcustomiser_Model_Import_Identifier extends Miragedesign_Importcustomiser_Model_Import_Abstract
{
    /**
     * use product id
     */
    const ID    = 1;

    /**
     * use product sku
     */
    const SKU   = 2;

    /**
     * cache options
     * @var null|array
     */
    protected $_options = null;

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
                'label' => Mage::helper('importcustomiser')->__('Product id'),
                'value' => self::ID,
            );
            $this->_options[] = array(
                'label' => Mage::helper('importcustomiser')->__('Product SKU'),
                'value' => self::SKU,
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
        return 'importcustomiser_get_options_identifier';
    }
}