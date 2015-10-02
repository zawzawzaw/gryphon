<?php get_header(); ?>

<div id="content-wrapper" class="blog">
	<div class="blog-banner">
		<div class="image-text-content container">
			<div class="row">
				<div class="col-md-12 caption-container">
					<div class="caption">
						<h1>The Gryphon Blog</h1>
						<a href="http://www.gryphontea.com/store.html" class="read-more cta">Visit Tea Store</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="visible-xs-block visible-sm-block" id="mobile-blog-sidebar">
		<div id="mobile-blog-button-container">
	      <div class="mobile-blog-button" id="gryphon-blog-tag-button">Tags</div>
	      <div class="mobile-blog-button" id="gryphon-blog-categories-button">Categories</div>
	  </div>

	  <div id="mobile-blog-tag-container">
	  	<?php 
	  		wp_tag_cloud( array(
	  			'format' => 'list',
	  		));
	  	?>
	  </div>
	  <div id="mobile-blog-category-container">
	  	<ul>
		  	<?php 
		  		/*
		  		wp_list_categories( array(
		  			'title_li' => '',
		  		));
		  		*/

		  		get_categories();

		  	?>
	  	</ul>
	  </div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#mobile-blog-sidebar').gryphon_mobile_wp_sidebar({});
		});
	</script>

	<div class="main-content">
		<div class="image-text-content container">
			<div class="row">
				<div class="col-md-9">
					<div class="posts">
						<div id="all-posts">
							<?php
							$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
							$temp = $wp_query;
							$wp_query = null;
							$wp_query = new WP_Query();
							$wp_query -> query('post_type=post&showposts=2'.'&paged='.$paged);
							
							if($wp_query->have_posts()):

								while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
									<?php get_template_part( 'content', get_post_format() ); ?>
								<?php endwhile; // end of the loop. ?>																									

							<?php else : ?>
								<?php get_template_part( 'content', 'none' ); ?>
							<?php endif; ?>
						</div>
						<div class="row load-more-wrapper">
							<div class="col-md-12">
								<div class="each-load-more">
									<hr class="load-more">
								</div>
								<div class="each-load-more">
									<a href="#" class="load-more-btn">load more</a>
								</div>
								<div class="each-load-more">
									<hr class="load-more">
								</div>
							</div>
						</div>
					</div>

					<div id="pagenav" style="display:none;">
						<div class="prev-page"><?php previous_posts_link('Previous') ?></div>
						<div class="next-page"><?php next_posts_link('Next') ?></div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="visible-md-block visible-lg-block">
						<?php get_sidebar(); ?>					
					</div>
				</div>		
			</div>	
		</div>
	</div>

</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		$('.mobile-readmore-button').click(function(event){
			event.preventDefault();

			var target = $(event.currentTarget);
			var parent = target.parent();
			parent.toggleClass('expanded-version');

		});

	});
</script>

<?php get_footer(); ?>