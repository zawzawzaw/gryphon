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

		
		<div id="desktop-back-to-top-button">
			<a href="#"><i class="my-arrow-left"></i>Back to Top</a>
		</div>
		<script type="text/javascript" src=""></script>
		<script type="text/javascript">
			jQuery(document).ready(function($) {

				var body_element = $('body');
				var window_element = $(window);
				var main_container =  $('#content-wrapper .main-content > .image-text-content');

				$(window).scroll(function(){

					
					// 522 = 120 + 340 + 62

					window_element.scrollTop()
					(main_container.height() - 522) < 

					if(window_element.scrollTop() > 1160){
						if(body_element.hasClass('has-blog-back-to-top') == false){
							body_element.addClass('has-blog-back-to-top');
						}
					} else {
						if(body_element.hasClass('has-blog-back-to-top') == true){
							body_element.removeClass('has-blog-back-to-top');
						}
					}
				});

				$('#desktop-back-to-top-button a').click(function(event){
					event.preventDefault();
					//$(window).scrollTop(0);
					TweenMax.to(window, 1, {scrollTo:{y:0}, ease:Sine.easeInOut });
				});

			});
		</script>

	</div>
<?php endif; ?>