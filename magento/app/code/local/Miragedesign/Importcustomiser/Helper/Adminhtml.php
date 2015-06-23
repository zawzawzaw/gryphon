<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Importcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Importcustomiser_Helper_Adminhtml extends Mage_Core_Helper_Abstract
{
    /**
     * tooltip block
     * @var Mage_Adminhtml_Block_Template
     */
    protected $_tooltipBlock = null;

    /**
     * get the tooltip html
     * @access public
     * @param string $title
     * @param string $text
     * @param $width
     * @return string
     */
    public function getTooltipHtml($title, $text, $width = '300px')
    {
        return $this->getTooltipBlock()->setTitle($title)->setMessage($text)->setWidth($width)->toHtml();
    }

    /**
     * get the tooltip block for help messages
     * @access public
     * @return Mage_Adminhtml_Block_Template
     */
    public function getTooltipBlock()
    {
        if (is_null($this->_tooltipBlock)) {
            $this->_tooltipBlock = Mage::app()->getLayout()->createBlock('adminhtml/template')->setTemplate('importcustomiser/tooltip.phtml');
        }

        return $this->_tooltipBlock;
    }
}
