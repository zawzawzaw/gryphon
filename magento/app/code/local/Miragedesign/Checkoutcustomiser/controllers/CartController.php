<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Checkoutcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'Mage/Checkout/controllers/CartController.php';

class Miragedesign_Checkoutcustomiser_CartController extends Mage_Checkout_CartController
{
    /**
     * Delete shoping cart item action
     */
    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)
                    ->save();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove the item.'));
                Mage::logException($e);
            }
        }
        $this->getResponse()->setRedirect(Mage::getUrl('*/*'));
        return $this;
    }
}
