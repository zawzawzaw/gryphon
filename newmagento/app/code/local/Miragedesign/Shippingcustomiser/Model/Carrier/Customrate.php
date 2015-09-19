<?php
/** Modified By:: PG **/
/**
 * Miragedesign Web Development
 *
 * @category    Miragedesign
 * @package     Miragedesign_Shippingcustomiser
 * @copyright   Copyright (c) 2011 Miragedesign (http://miragedesign.net)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Miragedesign_Shippingcustomiser_Model_Carrier_Customrate
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * code name
     *
     * @var string
     */
    protected $_code = 'customrate';

    /**
     * Default condition name
     *
     * @var string
     */
    protected $_default_condition_name = 'package_weight';

    /**
     * Condition names
     *
     * @var array
     */
    protected $_conditionNames = array();

    public function __construct()
    {
        parent::__construct();
        foreach ($this->getCode('condition_name') as $k => $v) {
            $this->_conditionNames[] = $k;
        }
    }

    /**
     * Enter description here...
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $freeShipping = false;

        $destCountry = $request->getDestCountryId();
        $mainCountry = $this->getConfigData('main_country');
        $result = Mage::getModel('shipping/rate_result');
        $method = Mage::getModel('shipping/rate_result_method');
		
		//PG::Start
		$GroupName = "";		
		$ShippingCharge = '';		
		$minimumFreeShippingAmount = '';
		
		$login = Mage::getSingleton( 'customer/session' )->isLoggedIn(); //Check if User is Logged In
		if($login)
		{
			$groupId = Mage::getSingleton('customer/session')->getCustomerGroupId(); //Get Customers Group ID
			$group = Mage::getModel('customer/group')->load($groupId);		
			$GroupName = $group->getCode();

		}
		if($GroupName != "")
		{
			$cart_sub_total = $request->getPackageValue();		
			
			$minimumFreeShippingAmountColl = Mage::getModel('shippingcustomiser/customfreeshippingrate')->getCollection()			
			->addFieldToFilter('groupname', $GroupName)
			->addFieldToFilter('OrderAmountFrom', array('lteq' => $cart_sub_total));
			
			$minimumFreeShippingAmountColl->getSelect()->order('OrderAmountFrom DESC');
			$minimumFreeShippingAmountColl->getSelect()->limit(1);
						
			$freeShippingAmountData = $minimumFreeShippingAmountColl->getFirstItem();
			$ShippingCharge = isset($freeShippingAmountData['ShippingCharge'])?$freeShippingAmountData['ShippingCharge']:'';			
		}
		if($ShippingCharge == "")
		{
			$minimumFreeShippingAmount = $this->getConfigData('free_shipping_threshold');
		}		
		//PG::End

        if ($destCountry == $mainCountry) {
			
			if($ShippingCharge != "" && $ShippingCharge>0)
			{
				$freeShipping = false;				
			}
			else if($ShippingCharge != "" && $ShippingCharge==0)
			{
				$freeShipping = true;				
			}
			else
			{
				if (is_numeric($minimumFreeShippingAmount) &&
                $minimumFreeShippingAmount > 0 &&
                $request->getPackageValue() > $minimumFreeShippingAmount
				) {
					$freeShipping = true;
				}
				$ShippingCharge = $this->getConfigData('flatrate_shipping');
			}           

            if ($freeShipping) {
                $method->setCarrier($this->_code);
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethod('customrate_free');
                $method->setPrice('0.00');
                $method->setCost('0.00');
                $method->setMethodTitle($this->getConfigData('free_method_text'));
                $method->setDeliveryTime($this->getConfigData('free_method_delivery_time'));
                $method->setMethodDescription($this->getConfigData('free_method_delivery_time'));
                $result->append($method);
            } else {
                $method->setCarrier($this->_code);
                $method->setCarrierTitle($this->getConfigData('title'));
                $method->setMethod('customrate_flatrate');
                //$method->setPrice($this->getConfigData('flatrate_shipping'));
                //$method->setCost($this->getConfigData('flatrate_shipping'));
				$method->setPrice($ShippingCharge);
                $method->setCost($ShippingCharge);
                $method->setMethodTitle($this->getConfigData('flatrate_shipping_text'));
                $method->setDeliveryTime($this->getConfigData('flatrate_shipping_delivery_time'));
                $method->setMethodDescription($this->getConfigData('flatrate_shipping_delivery_time'));
                $result->append($method);
            }

            return $result;
        }

        if (!$request->getConditionName()) {
            $request->setConditionName($this->getConfigData('condition_name') ? $this->getConfigData('condition_name') : $this->_default_condition_name);
        }

        $rateValue = $this->getRate($request);

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod('customrate_tablerate');
        $method->setMethodTitle($this->getConfigData('title'));
        $method->setMethodDescription($rateValue['delivery_time']);

        $shippingPrice = $this->getFinalPriceWithHandlingFee($rateValue['price']);

        //$method->setCost($rateValue['cost']);
        $method->setDeliveryTime($rateValue['delivery_time']);
        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);
        $result->append($method);

        return $result;
    }

    public function getRate(Mage_Shipping_Model_Rate_Request $request)
    {
        return Mage::getResourceModel('shippingcustomiser/carrier_customrate')->getNewRate($request);
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array(
            'customrate_free' => $this->getConfigData('free_method_text'),
            'customrate_flatrate' => $this->getConfigData('flatrate_shipping_text'),
            'customrate_tablerate' => $this->getConfigData('title')
        );
    }

    public function getCode($type, $code = '')
    {
        $codes = array(
            'condition_name' => array(
                'package_weight' => Mage::helper('shipping')->__('Weight vs. Destination'),
            ),
            'condition_name_short' => array(
                'package_weight' => Mage::helper('shipping')->__('Weight'),
            ),
        );

        if (!isset($codes[$type])) {
            throw Mage::exception('Mage_Shipping', Mage::helper('shipping')->__('Invalid Custom Rate code type: %s', $type));
        }

        if ('' === $code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            throw Mage::exception('Mage_Shipping', Mage::helper('shipping')->__('Invalid Custom Rate code for type %s: %s', $type, $code));
        }

        return $codes[$type][$code];
    }
}
