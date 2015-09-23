<!doctype html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]>
<!--> 
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<meta name="keywords" content="Strand">	

	<!-- HTML5 SHIV -->
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Mobile Viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- Force IE to render best possible view -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<!-- FONT -->	
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet' type='text/css'>
	<script src="//use.typekit.net/fwh2axv.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>

	
		
	<!-- ICONS -->
	<!-- <link rel="shortcut icon" href="img/icons/favicon.ico">
	<link rel="apple-touch-icon" href="img/icons/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="img/icons/apple-touch-icon-72x72-precomposed.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="img/icons/apple-touch-icon-114x114-precomposed.png" /> -->	

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

<div id="page-wrapper">
	<div id="header-wrapper" class="visible-md visible-lg"><!-- container-fluid -->
		<header id="main-header" class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="logo-wrapper hidden-xs hidden-sm visible-md visible-lg">
						<a href="/magento" class="site-logo"><img src="<?php echo IMG; ?>/logo/main-logo.png" alt="logo"></a>
					</div>
					<div id="menu-wrapper">
						<nav id="nav-main">
							<?php

								$defaults = array(
									'echo' => true,
									'container' => false,
									'theme_location'  => 'main-menu',
									'menu_class'      => 'main-menu hidden-xs hidden-sm'
								);


								wp_nav_menu($defaults);
							?>
						
							<i class="fa fa-bars icon-font mobile-menu-btn hidden-md hidden-lg"></i>
							<ul class="mobile-menu hidden-md hidden-lg">
								<li>
									<a href="#">TEA</a>
									<ul class="sub-menu">
										<li><a href="store.html">Black</a></li>
										<li><a href="store.html">White</a></li>
										<li><a href="store.html">Green</a></li>
										<li><a href="store.html">Yellow</a></li>
										<li><a href="store.html">Oolong</a></li>
										<li><a href="store.html">Pu-erh</a></li>
										<li><a href="store.html">Rooibos</a></li>
										<li><a href="store.html">Tisane</a></li>
										<li><a href="store.html">Herbs</a></li>
									</ul>
								</li>
								<li>
									<a href="teawear.html">TEAWARE</a>
								</li>
								<li>
									<a href="gifts.html">GIFTS <!-- <i class="fa fa-chevron-down"></i> --></a>
									<!-- <ul class="sub-menu">
										<li><a href=""></a></li>
									</ul> -->
								</li>
								<li>
									<a href="subscription.html">SUBSCRIPTION</a>
								</li>
								<li>
									<a href="collections.html">COLLECTIONS <!-- <i class="fa fa-chevron-down"></i> --></a>
									<!-- <ul class="sub-menu">
										<li><a href=""></a></li>
									</ul> -->
								</li>
								<li>
									<a href="blog.html">BLOG</a>
								</li>
								<li>
									<a href="trade.html">TRADE</a>
								</li>
							</ul>
						</nav>
					</div>	
					<div id="header-cta">
						<!-- <div class="promo-image-text">
							<div class="promo hidden-xs hidden-sm">
								<img src="<?php echo IMG; ?>/icons/header-img-1.png" class="promo-img" alt="header img">
								<p>Get <span class="red">10%</span> off discount from your first purchase <br>until 17th of Jan with Promo Code: <span class="red">SHOPNOW</span></p>
							</div>

							<div class="cta">
								<ul class="cta-list">
									<li><a href="#" class="search"><span class="hidden-xs hidden-sm">Search</span></a></li>									
									<li><a href="account.html" class="account"><span class="hidden-xs hidden-sm">Richard</span></a></li>
									<li class="hidden-md hidden-lg"><hr class="divider"></li>
									<li><a href="cart.html" class="cart"><span>$S 36.50 (2)</span></a></li>
								</ul>
							 </div>
						</div> -->
					</div>
				</div>
			</div>
		</header>
	</div>


	<div id="mobile-header-wrapper" class="visible-xs visible-sm">
		<header id="main-mobile-header">
			<div class="marquee">
	    	<p class="rotate"><span class="rotating flip up" style="display: block; transform: rotateX(-180deg);"><span class="front">Use code "shopnow" to enjoy 10% off first purchase</span><span class="back"> Free local shipping when you spend over $60</span></span></p>
	    </div>

	    <div id="mobile-fixed-container">
	      <div class="mobile-menu-button"></div>
	      <a href="http://gryphontea.com/magento/" class="mobile-menu-logo"></a>
	    </div>

	    <div id="mobile-expand-container">
	    	







	    	<div class="mobile-submenu">
	        <div class="mobile-submenu-header">Gryphon Store</div>
	        <ul>
	          <li class="has-sub-menu">
	              <a href="http://gryphontea.com/magento/store.html">TEA</a>
	              <ul class="sub-menu" style=""><li><a href="http://gryphontea.com/magento/store.html">All</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/black-tea.html">Black</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/fruit-tisane.html">Fruit Tisane</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/green-tea.html">Green</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/herbs.html">Herbs</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/herbal-tisane.html">Herbal Tisane</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/multi.html">Multi</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/oolong-tea.html">Oolong</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/pu-erh-tea.html">Pu-Erh</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/roobios.html">Rooibos</a></li><li><a href="http://gryphontea.com/magento/store/tea-type/white-tea.html">White</a></li></ul>
	          </li>                   <li>
	            <a href="http://gryphontea.com/magento/store/pantry.html">PANTRY</a>
	          </li>
	                    
	          <li>
	                                    <a href="http://gryphontea.com/magento/store/gifts.html/">GIFTS</a>
	          </li>
	          <li>
	            <a href="http://gryphontea.com/magento/subscription/">SUBSCRIPTION</a>
	          </li>                 
	          <li>
	            <a href="http://gryphontea.com/magento/trade/">TRADE</a>
	          </li>         
	        </ul>
	      </div>

	      <div class="mobile-submenu">
	        <div class="mobile-submenu-header">About Gryphon</div>
	        <ul>
	          <li>
	            <a href="http://gryphontea.com/magento/collections/">COLLECTIONS</a>                
	          </li>
	          <li>
	            <a href="http://gryphontea.com/magento/wp">BLOG</a>
	          </li>
	          <li><a href="http://gryphontea.com/magento/discover-tea/">Discover Tea</a></li>
	          <li><a href="http://gryphontea.com/magento/our-story/">Our Story</a></li>
	          <li><a href="http://gryphontea.com/magento/careers/">Careers</a></li>
	        </ul>
	      </div>

	      <div class="mobile-submenu">
	        <div class="mobile-submenu-header">Customer Service</div>
	        <ul>
	                              

	          <li><a href="http://gryphontea.com/magento/contact-us/">Contact Us</a></li>                             
	          <li><a href="http://gryphontea.com/magento/faq/">FAQ</a></li>
	          <li><a href="http://gryphontea.com/magento/shipping-info/">Shipping Information</a></li>
	          <li><a href="http://www.trackntrace.com.sg/">Track Order</a></li>
	          <li><a href="http://gryphontea.com/magento/terms-and-conditions/">Terms &amp; Conditions</a></li>
	          <li><a href="http://gryphontea.com/magento/privacy-policy/">Privacy Policy</a></li>
	        </ul>
	      </div>

      




		    <div class="extra-space"></div>
		    <div id="mobile-header-footer-copy" class="mobile-footer">
		      <h2>newsletter sign up</h2>
		      <hr class="small">
		      <p>Join our Gryphon Tea Family and be the first to learn about our latest news, enjoy exclusive deals and invites to events!</p>

		      <div class="search">
		        <input type="text" name="subscribe_email" placeholder="Enter your email address">
		        <button class="signup subscribe_newsletter"><i class="fa fa-check"></i> <span>Sign up</span></button>
		      </div>

		      <hr class="dotted">

		      <h2>FOLLOW US</h2>
		      <hr class="small">
		      <ul class="share-ctas">
		        <li><a href="https://instagram.com/gryphontea/" target="_blank" class="instagram"></a></li>
		        <li><a href="https://www.pinterest.com/gryphontea/" target="_blank" class="pintrest"></a></li>
		        <li><a href="https://www.facebook.com/gryphonteacompany" target="_blank" class="facebook"></a></li>
		        <li><a href="https://www.linkedin.com/company/gryphon-tea-company" target="_blank" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
		        <li><a href="https://www.youtube.com/channel/UCrKNQcT3PHO-o_vQBMUlH8Q" target="_blank" class="youtube"><i class="fa fa-youtube"></i></a></li>
		        <li><a href="https://plus.google.com/+Gryphonteacompany/posts" target="_blank" class="google-plus"><i class="fa fa-google-plus"></i></a></li>
		      </ul>

		      <div class="copywrite">
		        <h6>© 2013 GRYPHON TEA COMPANY.</h6>
		        <p>All material on this site is copyrighted by Gryphon Tea Company. Gryphon Tea Company® is a registered trademark. No images or copy on this site may be used without written permission by Gryphon Tea Company.</p> 
		      </div>

		    </div>
	    	
	    </div> <!-- mobile-expand-container -->
		</header>

	</div>
		

	<script type="text/javascript">

		jQuery(document).ready(function($) {
			$('#main-mobile-header').gryphon_mobile_wp_header({});
		});
	</script>



	

