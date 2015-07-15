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
	<div id="header-wrapper" class=""><!-- container-fluid -->
		<header id="main-header" class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="logo-wrapper hidden-xs hidden-sm visible-md visible-lg">
						<a href="index.html" class="site-logo"><img src="<?php echo IMG; ?>/logo/main-logo.png" alt="logo"></a>
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
