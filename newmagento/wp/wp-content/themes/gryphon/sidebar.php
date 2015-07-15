<?php
if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div class="sidebar">
		<div class="each-sidebar-content">
			<h2>follow us</h2>
			<ul>
				<li><a href="#" class="instagram"></a></li>
				<li><a href="#" class="pintrest"></a></li>
				<li><a href="#" class="twitter"></a></li>
				<li><a href="#" class="facebook"></a></li>
			</ul>
		</div>

		<?php //dynamic_sidebar( 'sidebar-1' ); ?>			

		<?php get_search_form(); ?>

		<div class="each-sidebar-content">
			<h2>Tags</h2>
			<p class="tags">
				<?php
				$posttags = get_the_tags();
				if ($posttags) {
				  foreach($posttags as $tag) {
				?>
				    <a href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo '#'.$tag->name . ' ';  ?></a>
				<?php
				  }
				}
				?>					
			</p>
		</div>

		<div class="each-sidebar-content">
			<h2>subscribe news letter</h2>
			<input type="text" name="email" placeholder="Type your email address">
			<a href="#" class="signup"><i class="fa fa-check"></i> Sign up</a>
		</div>

		<hr>

		<div class="each-sidebar-content">
			<h2>POPULAR POST</h2>

			<?php
				$recent_posts = wp_get_recent_posts();
				foreach( $recent_posts as $recent ){
					// print_r($recent);
			?>
					<div class="each-popular-post">
						<?php echo get_the_post_thumbnail( $recent["ID"], 'thumbnail', array( 'class' => 'img-responsive' ) ); ?>
						<span class="date"><?php echo date("F j", strtotime($recent["post_date"])); ?></span>
						<h2><?php echo $recent["post_title"]; ?></h2>
						<p><?php echo $recent["post_excerpt"]; ?></p>

						<a href="<?php echo get_permalink($recent["ID"]); ?>" class="cta read-more"><i class="fa fa-arrow-right"></i> Read more</a>
					</div>
			<?
				}
			?>			
		</div>
	</div>
<?php endif; ?>