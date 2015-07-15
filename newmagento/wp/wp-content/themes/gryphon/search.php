<?php get_header(); ?>

<?php 
function new_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
?>

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
							<?php if ( have_posts() ) : ?>													
							<div class="post">
								<div class="post-content">
									<h1><?php printf( __( 'Search Results for: %s', 'gryphon' ), get_search_query() ); ?></h1>
									<hr class="small">
									<?php if ( is_single() ) : ?>
										<h1 class="entry-title"><?php the_title(); ?></h1>
									<?php else : ?>
										<?php while ( have_posts() ) : the_post(); ?>
											<h1 class="entry-title">
												<?php the_title(); ?><a href="<?php the_permalink(); ?>" rel="bookmark"></a>
											</h1>

											<div class="entry-content">
												<?php the_excerpt('More...'); ?>													
											</div>
										
										<?php endwhile; ?>
									<?php endif; // is_single() ?>
								</div>
							</div>												
						<?php else : ?>
							<header class="entry-header">
								<h1 class="entry-title"><?php _e( 'Nothing Found', 'gryphon' ); ?></h1>
							</header>
							
							<div class="entry-content">
								<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'gryphon' ); ?></p>
							</div><!-- .entry-content -->							

						<?php endif; ?>
						</div>						
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