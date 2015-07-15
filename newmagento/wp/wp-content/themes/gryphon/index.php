<?php get_header(); ?>

<div id="content-wrapper" class="blog">
	<div class="blog-banner">
		<div class="image-text-content container">
			<div class="row">
				<div class="col-md-12 caption-container">
					<div class="caption">
						<h1>How to start a day with <br> Gryphon tea</h1>
						<a href="store.html" class="read-more cta">Visit Tea Store</a>
					</div>
				</div>
			</div>
		</div>
	</div>

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
					<?php get_sidebar(); ?>					
				</div>		
			</div>	
		</div>
	</div>

</div>

<?php get_footer(); ?>