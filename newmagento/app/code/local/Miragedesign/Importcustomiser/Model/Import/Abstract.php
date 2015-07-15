<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Importcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

abstract class Miragedesign_Importcustomiser_Model_Import_Abstract
{
    /**
     * cache options
     * @var null|array
     */
    protected $_options = null;
    
    /**
     * event name dispatched when getting the options
     * @var string
     */
    protected $_eventName = 'importcustomiser_get_action_options';

    /**
     * get options as array: var[] = array('value'=>value, 'label'=>label)
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public abstract function getAllOptions($withEmpty);
    /**
     * getter for event name
     * @access public
     * @return mixed
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public abstract function getEventName();

    /**
     * get options as array: var[key] = value
     * @access public
     * @param bool $withEmpty
     * @return array
     */
    public function getOptionsAsArray($withEmpty = false)
    {
        $options = array();

        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }

    /**
     * dispatch event for altering the options
     * @access protected
     * @return $this
     */
    protected function _dispatchEvent()
    {
        $eventName = $this->getEventName();

        if ($eventName) {
            $obj = new Varien_Object(array('options'=>$this->_options));
            Mage::dispatchEvent($this->getEventName(), array('data_object'=>$obj));
            $this->_options = $obj->getOptions();
        }

        return $this;
    }
}