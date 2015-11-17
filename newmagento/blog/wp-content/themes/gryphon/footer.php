		
		<div id="mobile-footer-wrapper" class="visible-xs visible-sm">
		
			<div id="mobile-header-footer-copy" class="mobile-footer">
				<h2>newsletter sign up</h2>
				<hr class="small">
				<!-- <p>Join our Gryphon Tea Family and be the first to learn about our latest news, and enjoy exclusive deals and invites to events</p> -->
				<p>Join our Gryphon Tea Family and be the first to learn about our latest news, enjoy exclusive deals and invites to events!</p>

				<div class="search">
					<input type="text" name="subscribe_email" placeholder="Enter your email address" />
					<button class="signup subscribe_newsletter"><i class="fa fa-check"></i> <span>Sign up</span></button>
				</div>

				<hr class="dotted">

				<h2>FOLLOW US</h2>
				<hr class="small">
				<ul class="share-ctas">
					<li><a href="https://instagram.com/gryphontea/" target="_blank" class="instagram"><i class="fa fa-instagram"></i></a></li>
					<li><a href="https://www.pinterest.com/gryphontea/" target="_blank" class="pintrest"><i class="fa fa-pinterest"></i></a></li>
					<li><a href="https://www.facebook.com/gryphonteacompany" target="_blank" class="facebook"><i class="fa fa-facebook"></i></a></li>
					<li><a href="https://www.linkedin.com/company/gryphon-tea-company" target="_blank" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
					<li><a href="https://www.youtube.com/channel/UCrKNQcT3PHO-o_vQBMUlH8Q" target="_blank" class="youtube"><i class="fa fa-youtube"></i></a></li>
					<li><a href="https://plus.google.com/+Gryphonteacompany/posts" target="_blank" class="google-plus"><i class="fa fa-google-plus"></i></a></li>
				</ul>

				<div class="copywrite">
					<h6>&copy; <?php echo date("Y"); ?> GRYPHON TEA COMPANY.</h6>
					<p>All material on this site is copyrighted by Gryphon Tea Company. Gryphon Tea Company® is a registered trademark. No images or copy on this site may be used without written permission by Gryphon Tea Company.</p>	
				</div>

			</div>
					
		</div>

		<div id="footer-wrapper" class="visible-md visible-lg fixed-footer"><!-- container-fluid -->
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<h2>newsletter sign up</h2>
						<hr class="small">
						<p>Join our Gryphon Tea Family and be the first to learn about our latest news, and enjoy exclusive deals and invites to events</p>

						<div class="search">
							<input type="text" name="search" placeholder="Enter your email address" />
							<button class="signup"><i class="fa fa-check"></i> <span>Sign up</span></button>
						</div>
					</div>
					<div class="col-md-7">
						<?php
						    $args = array(
						    'order'                  => 'ASC',
						    'orderby'                => 'menu_order',
						    'post_type'              => 'nav_menu_item',
						    'post_status'            => 'publish',
						    'output'                 => ARRAY_A,
						    'output_key'             => 'menu_order',
						    'nopaging'               => true,
						    'update_post_term_cache' => false );
						    $menu_items = wp_get_nav_menu_items( 'Footer Menu', $args );
						?>
						<div class="links-wrapper">
							<div class="about-us-links links">
								<h2>about us</h2>
								<hr class="small">
								<ul>
									<?php 
						            foreach ( (array) $menu_items as $key => $menu_item ) {
						                if ( $menu_item->menu_item_parent == 37 ) :
						                    $title = $menu_item->title;
						                    $url = $menu_item->url; 
						            ?>
						                <li>                          
						                  <a href="<?php echo $url; ?>"><?php echo $title; ?></a>
						                </li>
						            <?php
						                endif;
						            }
						            ?>
								</ul>
							</div>
							<div class="store-links links">
								<h2>tea store</h2>
								<hr class="small">
								<ul>
									<?php 
						            foreach ( (array) $menu_items as $key => $menu_item ) {
						                if ( $menu_item->menu_item_parent == 30 ) :
						                    $title = $menu_item->title;
						                    $url = $menu_item->url; 
						            ?>
						                <li>                          
						                  <a href="<?php echo $url; ?>"><?php echo $title; ?></a>
						                </li>
						            <?php
						                endif;
						            }
						            ?>									
								</ul>
							</div>
							<div class="selections-links links">
								<h2>Collections</h2>
								<hr class="small">
								<ul>
									<?php 
						            foreach ( (array) $menu_items as $key => $menu_item ) {
						                if ( $menu_item->menu_item_parent == 52 ) :
						                    $title = $menu_item->title;
						                    $url = $menu_item->url; 
						            ?>
						                <li>                          
						                  <a href="<?php echo $url; ?>"><?php echo $title; ?></a>
						                </li>
						            <?php
						                endif;
						            }
						            ?>
								</ul>
							</div>
							<div class="customer-service-link links">
								<h2>Order Information</h2>
								<hr class="small">
								<ul>
									<?php 
						            foreach ( (array) $menu_items as $key => $menu_item ) {
						                if ( $menu_item->menu_item_parent == 79 ) :
						                    $title = $menu_item->title;
						                    $url = $menu_item->url; 
						            ?>
						                <li>                          
						                  <a href="<?php echo $url; ?>"><?php echo $title; ?></a>
						                </li>
						            <?php
						                endif;
						            }
						            ?>
								</ul>
							</div>							
						</div>
					</div>
					<div class="col-md-2">
						<h2>FOLLOW US</h2>
						<hr class="small">
						<ul class="share-ctas">
							<li><a href="https://instagram.com/gryphontea/" target="_blank" class="instagram"><i class="fa fa-instagram"></i></a></li>
							<li><a href="https://www.pinterest.com/gryphontea/" target="_blank" class="pintrest"><i class="fa fa-pinterest"></i></a></li>
							<li><a href="https://www.facebook.com/gryphonteacompany" target="_blank" class="facebook"><i class="fa fa-facebook"></i></a></li>
							<li><a href="https://www.linkedin.com/company/gryphon-tea-company" target="_blank" class="linkedin"><i class="fa fa-linkedin" style="
	"></i></a></li>
							<li><a href="https://www.youtube.com/channel/UCrKNQcT3PHO-o_vQBMUlH8Q/videos" target="_blank" class="youtube"><i class="fa fa-youtube" style="
	"></i></a></li>
							<li><a href="https://plus.google.com/+Gryphonteacompany/posts" target="_blank" class="google-plus"><i class="fa fa-google-plus" style="
	"></i></a></li>
						</ul>
					</div>
				</div>
			</div>

		</div>
		<div id="copyright-wrapper" class="visible-md visible-lg fixed-footer">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>&copy; <?php echo date("Y"); ?> GRYPHON TEA COMPANY.</p>
						<p>All material on this site is copyrighted by Gryphon Tea Company. Gryphon Tea Company® is a registered trademark. No images or copy on this site may be used without written permission by Gryphon Tea Company.</p>	
					</div>
				</div>
			</div>
		</div>
	</div><!-- page wrapper end -->
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '1633996753523303',
	      xfbml      : true,
	      version    : 'v2.4'
	    });

	  //   $('.facebook').on('click', function(e){
	  //   	e.preventDefault();
	  //   	var url = $(this).attr('href');
	  //   	FB.ui(
			// {
			// 	method: 'share',
			// 	href: url,
			// },function(response) {
			//     if (response && !response.error_code) {
			//       	alert('Posting completed.');
			//     }
			// });
	  //   });
	  };

	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));

	  	function openNewWindow(URLtoOpen, windowName, windowFeatures) { newWindow=window.open(URLtoOpen, windowName, windowFeatures); }

	  	$(document).on('click','.facebook', function(e){
	    	e.preventDefault();
	    	var url = $(this).attr('href');
	    	openNewWindow(url,'sharing','height=600,width=600,toolbar=no,scrollbars=no,resizable=yes');
    	});
    	$(document).on('click','.twitter', function(e){
	    	e.preventDefault();
	    	var url = $(this).attr('href');
	    	openNewWindow(url,'sharing','height=600,width=600,toolbar=no,scrollbars=no,resizable=yes');
    	});
    	$(document).on('click','.pintrest', function(e){
	    	e.preventDefault();
	    	var url = $(this).attr('href');
	    	openNewWindow(url,'sharing','height=600,width=600,toolbar=no,scrollbars=no,resizable=yes');
    	});    	
    	$(document).on('click','.google', function(e){
	    	e.preventDefault();
	    	var url = $(this).attr('href');
	    	openNewWindow(url,'sharing','height=600,width=600,toolbar=no,scrollbars=no,resizable=yes');
    	});
    	$(document).on('click','.linkedin', function(e){    	
	    	e.preventDefault();
	    	var url = $(this).attr('href');
	    	openNewWindow(url,'sharing','height=600,width=600,toolbar=no,scrollbars=no,resizable=yes');
    	});
	</script>
	<?php wp_footer(); ?>
</body>
</html>