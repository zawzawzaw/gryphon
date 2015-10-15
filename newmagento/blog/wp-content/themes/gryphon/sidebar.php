<?php
if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div class="sidebar">
		<?php get_search_form(); ?>

		<div class="each-sidebar-content">
			<h2>follow us</h2>
			<ul>
				<li><a href="https://instagram.com/gryphontea/" target="_blank" class="instagram"></a></li>
				<li><a href="https://www.pinterest.com/gryphontea/" target="_blank" class="pintrest"></a></li>
				<li><a href="https://www.facebook.com/gryphonteacompany" target="_blank" class="facebook"></a></li>
				<li><a href="https://www.linkedin.com/company/gryphon-tea-company" target="_blank" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
				<li><a href="https://www.youtube.com/channel/UCrKNQcT3PHO-o_vQBMUlH8Q" target="_blank" class="youtube"><i class="fa fa-youtube"></i></a></li>
				<li><a href="https://plus.google.com/+Gryphonteacompany/posts" target="_blank" class="google-plus"><i class="fa fa-google-plus"></i></a></li>
			</ul>
		</div>

		<?php //dynamic_sidebar( 'sidebar-1' ); ?>		

		<div class="each-sidebar-content">
			<h2>Categories</h2>
			<ul class="custom-categories">
				<?php
				$categories = get_categories();

				// print_r($posttags);
				// $posttags = get_the_tags();
				if ($categories) {
				  foreach($categories as $category) {
				?>
				    <li><a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->name;  ?></a></li>
				<?php
				  }
				}
				?>					
			</ul>
		</div>		

		<div class="each-sidebar-content">
			<h2>Tags</h2>
			<ul class="tags">
				<?php
				$posttags = get_tags();

				// print_r($posttags);
				// $posttags = get_the_tags();
				if ($posttags) {
					$no_of_tags = count($posttags);
					$i = 1;
				  foreach($posttags as $tag) {
				  	//if($i==$no_of_tags):
				?>
				    <li><a href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo $tag->name;  ?></a></li>
				<?php
					$i++;
				  }
				}
				?>					
			</ul>
		</div>
		
		<!-- <div class="each-sidebar-content">
			<h2>subscribe news letter</h2>
			<input type="text" name="email" placeholder="Type your email address">
			<a href="#" class="signup"><i class="fa fa-check"></i> Sign up</a>
		</div> -->

		<hr>

		<div class="each-sidebar-content">
			<h2>POPULAR POST</h2>
			<?php
				$args = array(
    				'numberposts' => 3,
    				'post_type' => 'post',
    				'post_status' => 'publish'
    			);
				$recent_posts = wp_get_recent_posts($args);
				foreach( $recent_posts as $recent ){
			?>
					<div class="each-popular-post">
						<?php echo get_the_post_thumbnail( $recent["ID"], array(253,253), array( 'class' => 'img-responsive' ) ); ?>
						<span class="date"><?php echo date("F j, Y", strtotime($recent["post_date"])); ?></span>
						<h2><?php echo $recent["post_title"]; ?></h2>
						<!-- <p><?php echo $recent["post_excerpt"]; ?></p> -->

						<a href="<?php echo get_permalink($recent["ID"]); ?>" class="cta read-more"><i class="my-arrow-right"></i> Read more</a>
					</div>
			<?
				}
			?>			
		</div>
	</div>
<?php endif; ?>