<?php

class Aemtech_Subscriptions_Model_Observer {
	
	public function catalogProductLoadAfter(Varien_Event_Observer $observer)
	{
		// set the additional options on the product
		
		$action = Mage::app()->getFrontController()->getAction();
		
		if($action->getFullActionName() == 'checkout_cart_add')
		{	
			$params = $action->getRequest()->getParams();
			$options = urldecode($params['pg']); 
			$product = $observer->getProduct();	
			$sku = $product->getData('sku');
                        
                        $allskus = array("SGP-ART-1M", "SGP-ART-3M", "SGP-ART-6M", "SGP-ART-12M", "SGP-GUR-1M", "SGP-GUR-3M", "SGP-GUR-6M", "SGP-GUR-12M","INT-ART-1M", "INT-ART-3M", "INT-ART-6M", "INT-ART-12M", "INT-GUR-1M", "INT-GUR-3M", "INT-GUR-6M", "INT-GUR-12M");
                        //$locationskuINTArray = array("INT-ART-1M", "INT-ART-3M", "INT-ART-6M", "INT-ART-12M", "INT-GUR-1M", "INT-GUR-3M", "INT-GUR-6M", "INT-GUR-12M");
			if(in_array($sku, $allskus))	
                        {
                            $sku_type = explode("-", $sku);
                            $sku_type = $sku_type[0];

                            $quote = Mage::getSingleton('checkout/session')->getQuote();
                            foreach ($quote->getAllVisibleItems() as $item) {
                                $itemsku = $item->getProduct()->getData('sku');//if you need it
                                $itemsku_type = explode("-", $itemsku);
                                $itemsku_type = $itemsku_type[0];
                                if($itemsku_type != $sku_type)
                                {
                                    Mage::getSingleton('core/session')->addError('Sorry, you either purchase  Singapore OR International Subscription not both.');

                                    //get URL model for cart/index
                                    $url = Mage::getModel('core/url')->getUrl('checkout/cart/index');

                                    //set redirect
                                    Mage::app()->getResponse()->setRedirect($url);

                                    //send redirect
                                    Mage::app()->getResponse()->sendResponse();

                                    //block further action
                                    exit;
                                }
                                    //your magic here.
                            }
                            //print_r($options);exit;
                            if($options)
                            {
                                    $options = str_replace(',}','}',$options);
                                    $options = json_decode($options, true);	


                                    // add to the additional options array
                                    $additionalOptions = array();
                                    if ($additionalOption = $product->getCustomOption('additional_options'))
                                    {
                                            $additionalOptions = (array) unserialize($additionalOption->getValue());
                                    }
                                    foreach ($options as $key => $value)
                                    {
                                            $additionalOptions[] = array(
                                                    'label' => $key,
                                                    'value' => $value,
                                            );
                                    }
                                    // add the additional options array with the option code additional_options
                                    $observer->getProduct()
                                            ->addCustomOption('additional_options', serialize($additionalOptions));
                            }
                        }
                        
		}
	}
	
	public function captureOrder(Varien_Event_Observer $observer) {
		
		$item_options = "";
		$location = "";
		$customer_id = "";
		$subscription_period = "";
		$subscription_category = "";
		$start_date = "";
		$end_date = "";
		$order_id = "";
		$customer_email = "";
		
        $_order     = $observer->getEvent()->getOrder();
        $orderObj   = Mage::getModel("sales/order")->load($_order->getId());

        $order_id   = $_order->getId();
        $items      = $orderObj->getAllItems();
		
        $billingAddress = $_order->getBillingAddress();
        $shipingAddress = $_order->getShippingAddress();
		
		if(Mage::getSingleton('customer/session')->isLoggedIn())
		{
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$customerData = Mage::getModel('customer/customer')->load($customer->getId());
			$customer_id = $customerData->getId();	
			$customer_email = $customerData->getEmail();
		}else
		{
			$customer_email = $billingAddress->getEmail();
			$customer_id = $_order->getCustomerId();
		}	

        foreach($items as $item) 
		{
			$product_type = $item->getProductType();
                        
			if($product_type == 'virtual')
			{
				$temp = $item->getProductOptions();
                                $item_options = $temp['info_buyRequest']['pg'];
                                $item_options = str_replace(",}", "}", $item_options);
//				if($item_options = $item->getCustomOption('pg'))
//				{
//                                    $item_options = (array) unserialize($item_options->getValue());
//                                    $item_options = json_decode($item_options);
//                                    //	$item_options = $item_options->getValue();
//				}
				
				$sku = $item->getData('sku');
				$qty        = (int) $item->getQtyOrdered();
				$price      = $item->getprice();
				
				$location = "";
				$locationskuSGPArray = array("SGP-ART-1M", "SGP-ART-3M", "SGP-ART-6M", "SGP-ART-12M", "SGP-GUR-1M", "SGP-GUR-3M", "SGP-GUR-6M", "SGP-GUR-12M");
				$locationskuINTArray = array("INT-ART-1M", "INT-ART-3M", "INT-ART-6M", "INT-ART-12M", "INT-GUR-1M", "INT-GUR-3M", "INT-GUR-6M", "INT-GUR-12M");
				
				if(in_array($sku, $locationskuSGPArray))
				{
				  $location = "singapore";
				}
				else if (in_array($sku, $locationskuINTArray))
				{
				  $location = "international";
				}
				
				$skuNew = explode("-",$sku);
				$skuNewNew = explode("M",$skuNew[2]);
				$subscription_period = $skuNewNew[0];
				
				if (strpos($sku,'ART') !== false) {
					$subscription_category = 'Artisan';
				}
				if (strpos($sku,'GUR') !== false) {
					$subscription_category = 'Gourmet';
				}
				
				$currentdate = date('d-m-Y');
				$currentDay =  date("d",strtotime($currentdate));
				
				if($currentDay < 17)
				{
					$start_date = date("Y-m-01 G:i:s");
				}else{
					
					$datetime2 = new DateTime();
					$datetime2->modify('+1 months');				 
					$start_date = $datetime2->format('Y-m-01 G:i:s');					
				}
				
				$datetime = new DateTime();
				$datetime->modify('+'.$subscription_period.' months');				 
				$end_date = $datetime->format('Y-m-01 G:i:s');				
				
				
				$subscriptionsModel = Mage::getModel('subscriptions/subscriptions');
				$subscriptionsModel->setCustomerId($customer_id);
				$subscriptionsModel->setLocation($location);
				$subscriptionsModel->setSubscriptionPeriod($subscription_period);
				$subscriptionsModel->setSubscriptionCategory($subscription_category);
				$subscriptionsModel->setStartDate($start_date);
				$subscriptionsModel->setEndDate($end_date);
				$subscriptionsModel->setOrderId($order_id);
				$subscriptionsModel->setCustomerEmail($customer_email);
				$subscriptionsModel->setItemOptions($item_options);		
				
				//$subscriptionsModel->setCreatedDate(date("Y-m-d G:i:s"));		
				
				try 
				{
					$subscriptionsModel->save(); 

				} catch (Exception $e){
					Mage::log($e->getMessage());
				}
				
			}
           
        }			  

    }

}
