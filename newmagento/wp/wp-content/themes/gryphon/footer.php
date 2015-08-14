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
							<li><a href="https://instagram.com/gryphontea/" target="_blank" class="instagram"></a></li>
							<li><a href="https://www.pinterest.com/gryphontea/" target="_blank" class="pintrest"></a></li>
							<li><a href="https://www.facebook.com/gryphonteacompany" target="_blank" class="facebook"></a></li>
							<li><a href="https://www.linkedin.com/company/gryphon-tea-company" target="_blank" class="linkedin"><i class="fa fa-linkedin" style="
	"></i></a></li>
							<li><a href="https://www.youtube.com/channel/UCrKNQcT3PHO-o_vQBMUlH8Q" target="_blank" class="youtube"><i class="fa fa-youtube" style="
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
						<p>&copy; 2013 GRYPHON TEA COMPANY.</p>
						<p>All material on this site is copyrighted by Gryphon Tea Company. Gryphon Tea Company® is a registered trademark. No images or copy on this site may be used without written permission by Gryphon Tea Company.</p>	
					</div>
				</div>
			</div>
		</div>
	</div><!-- page wrapper end -->
	<?php wp_footer(); ?>
</body>
</html>