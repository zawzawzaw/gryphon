		<div id="footer-wrapper" class="fixed-footer"><!-- container-fluid -->
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<h2>newsletter sign up</h2>
						<hr class="small">
						<p>By signing up the Gryphon newsletter, be the first one to hear about our special offers, new arrivals, latest news and trends and other events.</p>

						<div class="search">
							<input type="text" name="search" placeholder="Enter your email address" />
							<button class="signup"><i class="fa fa-check"></i> <span>Sign up</span></button>
						</div>
					</div>
					<div class="col-md-6 col-md-offset-1">
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
							<div class="store-links links">
								<h2>gryphon store</h2>
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
							<div class="customer-service-link links">
								<h2>customer service</h2>
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
						</div>
					</div>
					<div class="col-md-2">
						<h2>FOLLOW US</h2>
						<hr class="small">
						<ul class="share-ctas">
							<li><a href="#" class="instagram"></a></li>
							<li><a href="#" class="pintrest"></a></li>
							<li><a href="#" class="twitter"></a></li>
							<li><a href="#" class="facebook"></a></li>
						</ul>
					</div>
				</div>
			</div>

		</div>
		<div id="copyright-wrapper" class="fixed-footer">
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