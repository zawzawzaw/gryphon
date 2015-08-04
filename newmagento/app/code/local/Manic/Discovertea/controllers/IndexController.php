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
	    $params = array(
	        'siteUrl' => 'http://test.gryphontea.com/magento/oauth',
	        'requestTokenUrl' => 'http://test.gryphontea.com/magento/oauth/initiate',
	        'accessTokenUrl' => 'http://test.gryphontea.com/magento/oauth/token',
	        'authorizeUrl' => 'http://test.gryphontea.com/magento/admin/index.php/oauth_authorize',//This URL is used only if we authenticate as Admin user type
	        'consumerKey' => '678c0d8c77bd8d473c92a85e27906d00',//Consumer key registered in server administration
	        'consumerSecret' => '753fedef49d9eeb9f271b19aff7f6d8b',//Consumer secret registered in server administration
	        'callbackUrl' => 'http://test.gryphontea.com/magento/discovertea/index/callback',//Url of callback action below
	    );

	    // Initiate oAuth consumer with above parameters
	    $consumer = new Zend_Oauth_Consumer($params);
	    // Get request token
	    $requestToken = $consumer->getRequestToken();
	    // Get session
	    $session = Mage::getSingleton('core/session');
	    // Save serialized request token object in session for later use
	    $session->setRequestToken(serialize($requestToken));
	    // Redirect to authorize URL
	    $consumer->redirect();

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
}