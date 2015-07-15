<?php
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Checkoutcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Checkoutcustomiser_Block_Onepage extends Mage_Checkout_Block_Onepage
{
    /**
     * Get 'one step checkout' step data
     *
     * @return array
     */
    public function getSteps()
    {
        $steps = array();
        $stepCodes = $this->_getStepCodes();

        if ($this->isCustomerLoggedIn()) {
            //$stepCodes = array_diff($stepCodes, array('login'));
        }

        // We merge billing & shipping in one step
        if (($key = array_search('shipping', $stepCodes)) !== false ||
            ($key = array_search('shipping_method', $stepCodes)) !== false ||
            ($key = array_search('payment_method', $stepCodes)) !== false) {
            unset($stepCodes[$key]);
            $stepCodes = array_values($stepCodes);
        }

        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }

        return $steps;
    }

    /**
     * Get active step
     *
     * @return string
     */
    public function getActiveStep()
    {
        return $this->isCustomerLoggedIn() ? 'billing' : 'billing';
    }

}
