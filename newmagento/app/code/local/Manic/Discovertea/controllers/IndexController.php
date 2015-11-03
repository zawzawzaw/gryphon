<?php
class Manic_Discovertea_IndexController extends Mage_Core_Controller_Front_Action
{
   	public function indexAction() {
   		header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
		header('Access-Control-Allow-Methods: GET');
		header('Access-Control-Max-Age: 1000');
		header('Access-Control-Allow-Headers: Content-Type');

   		$product_id = $this->getRequest()->getParams('id');
 		$product=Mage::getModel('catalog/product')->load($product_id);

 		$allProducts = $product->getData();

 		unset($allProducts['type_selections']);

 		echo json_encode($allProducts, JSON_UNESCAPED_SLASHES); 		    
	}

	public function connectAction() {		

		//Basic parameters that need to be provided for oAuth authentication
	    //on Magento
	    // $params = array(
	    //     'siteUrl' => 'http://test.gryphontea.com/magento/oauth',
	    //     'requestTokenUrl' => 'http://test.gryphontea.com/magento/oauth/initiate',
	    //     'accessTokenUrl' => 'http://test.gryphontea.com/magento/oauth/token',
	    //     'authorizeUrl' => 'http://test.gryphontea.com/magento/admin/index.php/oauth_authorize',//This URL is used only if we authenticate as Admin user type
	    //     'consumerKey' => '678c0d8c77bd8d473c92a85e27906d00',//Consumer key registered in server administration
	    //     'consumerSecret' => '753fedef49d9eeb9f271b19aff7f6d8b',//Consumer secret registered in server administration
	    //     'callbackUrl' => 'http://test.gryphontea.com/magento/discovertea/index/callback',//Url of callback action below
	    // );

	    // // Initiate oAuth consumer with above parameters
	    // $consumer = new Zend_Oauth_Consumer($params);
	    // // Get request token
	    // $requestToken = $consumer->getRequestToken();
	    // // Get session
	    // $session = Mage::getSingleton('core/session');
	    // // Save serialized request token object in session for later use
	    // $session->setRequestToken(serialize($requestToken));
	    // // Redirect to authorize URL
	    // $consumer->redirect();

	    $order = Mage::getSingleton('sales/order'); 
		$order->loadByIncrementId(145000288);
		$_items = $order->getAllItems();
		$all_order_items = [];
		$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();

		foreach($_items as $_item) {
			$all_order_items['id'] = $_item->getItemId();
			$product = Mage::getModel('catalog/product')->load($all_order_items['id']);
			$categoryIds = $product->getCategoryIds();
			$categoryName = '';
			if (isset($categoryIds[0])){
				$category = Mage::getModel('catalog/category')->setStoreId(Mage::app()->getStore()->getId())->load($categoryIds[0]);
				$categoryName = $category->getName();
			}
			$all_order_items['name'] = $_item->getName();
			$all_order_items['sku'] = $_item->getSku();
			$all_order_items['category'] = $categoryName;
			$all_order_items['price'] = number_format(abs($_item->getPrice()),2);			
			$all_order_items['currency'] = $currency_code;
			$all_order_items['quantity'] = number_format(abs($_item->getQtyOrdered()),2);
			$all_order_items['date'] = $_item->getCreatedAt();
		}

		print_r(json_encode($all_order_items));

		exit();

	    return;
	}

	public function callbackAction() {

	    //oAuth parameters
	    $params = array(
	        'siteUrl' => 'http://test.gryphontea.com/magento/oauth',
	        'requestTokenUrl' => 'http://test.gryphontea.com/magento/oauth/initiate',
	        'accessTokenUrl' => 'http://test.gryphontea.com/magento/oauth/token',
	        'consumerKey' => '678c0d8c77bd8d473c92a85e27906d00',
	        'consumerSecret' => '753fedef49d9eeb9f271b19aff7f6d8b'
	    );

	    // Get session
	    $session = Mage::getSingleton('core/session');
	    // Read and unserialize request token from session
	    $requestToken = unserialize($session->getRequestToken());
	    // Initiate oAuth consumer
	    $consumer = new Zend_Oauth_Consumer($params);
	    // Using oAuth parameters and request Token we got, get access token
	    $acessToken = $consumer->getAccessToken($_GET, $requestToken);
	    // Get HTTP client from access token object
	    $restClient = $acessToken->getHttpClient($params);
	    // Set REST resource URL
	    $restClient->setUri('http://test.gryphontea.com/magento/api/rest/products');
	    // In Magento it is neccesary to set json or xml headers in order to work
	    $restClient->setHeaders('Accept', 'application/json');
	    // Get method
	    $restClient->setMethod(Zend_Http_Client::GET);
	    //Make REST request
	    $response = $restClient->request();
	    // Here we can see that response body contains json list of products
	    Zend_Debug::dump($response);

	    return;
	}

	public function cartpreviewAction() {
   		
		$cart = Mage::getModel('checkout/cart')->getQuote();
		$cartQty =  Mage::helper('checkout/cart')->getSummaryCount();
		$cartTotal = Mage::getSingleton('checkout/cart')->getQuote()->getSubtotal();

		$all_items = array();
		
		if ($cartQty>0):
			foreach ($cart->getAllItems() as $key => $item):
			    $productId = $item->getProduct()->getId();
			    $productName = $item->getProduct()->getName();
			    $productPrice = Mage::helper('core')->formatPrice($item->getProduct()->getPrice(), true);
			    $productQty = $item->getQty();
			    $price = $item->getRowTotal();
			    $productImage = $item->getProduct()->getData();	

			    $product_data = Mage::getModel('catalog/product')->load($productId);			    

			    $all_items[$productId]['id'] = $productId;
			    $all_items[$productId]['name'] = $productName;
			    $all_items[$productId]['price'] = $productPrice;
			    $all_items[$productId]['qty'] = $productQty;			    
			    $all_items[$productId]['row_price'] = Mage::helper('core')->formatPrice($price, true);
			    $all_items[$productId]['image'] = $product_data->getImageUrl();

			endforeach;  											
		endif;
		
		$all_cart_data['cart_qty'] = $cartQty;
		$all_cart_data['cart_total'] = Mage::helper('core')->formatPrice($cartTotal, true);
		$all_cart_data['cart_items'] = $all_items;

		echo json_encode($all_cart_data, JSON_UNESCAPED_SLASHES);

	}	

	public function subscribeAction() {
		header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
		header('Access-Control-Allow-Methods: GET');
		header('Access-Control-Max-Age: 1000');
		header('Access-Control-Allow-Headers: Content-Type');
		
		if ($this->getRequest()->isPost()) {
    
            $subscribe_email = $this->getRequest()->getPost('subscribe_email', array());
            $input_email = $this->getRequest()->getPost('subscribe_email', array());

            if(!filter_var($subscribe_email, FILTER_VALIDATE_EMAIL)) {
            	$result['success']  = false;
            	$result['error']    = true;
            	$result['error_messages'] = $this->__('Invalid email address.');
            }

            // if(!isset($subscribe_email) || empty($subscribe_email)) {
            // 	if(Mage::getSingleton('customer/session')->isLoggedIn()) {
	           //      $customerData = Mage::getSingleton('customer/session')->getCustomer();
	           //      $customerDataArr = $customerData->getData();
	           //      $subscribe_email = $customerDataArr['email'];
	           //  }
            // }

            // check whether customer subscribe already or not
            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($subscribe_email);

            $subscriberData = $subscriber->getData();

            // print_r($subscriberData);

			if($subscriberData['subscriber_status']!==1)
			{
			    Mage::getModel('newsletter/subscriber')->subscribe($subscribe_email);
			    $result['success']  = true;
            	$result['error']    = false;
			}else {
				$result['success']  = false;
            	$result['error']    = true;
            	$result['error_messages']    = $this->__('This email is already being subscribed.');
			}

			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    
        }

	}

	public function unsubscribeAction() {

		if ($this->getRequest()->isGet()) {
    
            $unsubscribe_email = $this->getRequest()->getParam('unsubscribe_email', array()); 

            // print_r($unsubscribe_email);
            $result = array();
            $result['error'] = false;

            if(!filter_var($unsubscribe_email, FILTER_VALIDATE_EMAIL)) {
            	$result['success']  = false;
            	$result['error']    = true;
            	$result['messages'] = $this->__('Invalid email address. You may close this window and try again.');
            }

            // if(!isset($subscribe_email) || empty($subscribe_email)) {
            // 	if(Mage::getSingleton('customer/session')->isLoggedIn()) {
	           //      $customerData = Mage::getSingleton('customer/session')->getCustomer();
	           //      $customerDataArr = $customerData->getData();
	           //      $subscribe_email = $customerDataArr['email'];
	           //  }
            // }

            // check whether customer subscribe already or not
            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($unsubscribe_email);

            $subscriberData = $subscriber->getData();

            // print_r($subscriberData);

			if($result['error']==false && !empty($subscriberData) && $subscriberData['subscriber_status']!==3)
			{
			    $subscriber->unsubscribe();
			    $result['success']  = true;
            	$result['error']    = false;
            	$result['messages']    = $this->__('Successfully unsubscribed to newsletter. You may close this window.');
			}else if($result['error']==false) {
				$result['success']  = false;
            	$result['error']    = true;
            	$result['messages']    = $this->__('This email is already unsubscribed. You may close this window. <script>setTimeout("window.close()", 5000);</script>');
			}

			$this->getResponse()->setBody($result['messages']);
    
        }

	}

	public function subscribeUnsubscribeAction() {
    	if(Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $customerDataArr = $customerData->getData();
            $subscribe_email = $customerDataArr['email'];

            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($subscribe_email);

            $subscriberData = $subscriber->getData();

			if($subscriber->getStatus() == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED)
			{
				Mage::getModel('newsletter/subscriber')->loadByEmail($subscribe_email)->unsubscribe();
			    $result['success']  = true;
            	$result['error']    = false;
            	$result['message'] = 'Successfully unsubscribed to newsletter.';
			    
			}else {				
				Mage::getModel('newsletter/subscriber')->subscribe($subscribe_email);
			    $result['success']  = true;
            	$result['error']    = false;            	
                $result['message'] = 'Successfully subscribed to newsletter.';				
			}

        }else {
        	$result['success']  = false;
        	$result['error']    = true;
        	$result['error_messages']    = $this->__('User is not logged in.');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
}