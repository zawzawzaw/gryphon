<?php
$current_tag  = get_query_var('tag');
$args=array(
  	'tag' => $current_tag,
   	'showposts'=> -1,
  	'caller_get_posts'=> 1
);
$my_query = new WP_Query($args);
?>
<?php get_header(); ?>

<div id="content-wrapper" class="blog">
	<div class="blog-banner">
		<div class="image-text-content container">
			<div class="row">
				<div class="col-md-12 caption-container">
					<div class="caption">
						<h1>The Gryphon Blog</h1>
						<a href="store.html" class="read-more cta">Visit Tea Store</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="main-content single">
		<div class="image-text-content container">
			<div class="row">
				<div class="col-md-9">
					<div class="posts">
						<div id="all-posts">
							<?php														
							if ( $my_query->have_posts() ) :

								while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
									<?php get_template_part( 'content', get_post_format() ); ?>
								<?php endwhile; // end of the loop. ?>																									

							<?php else : ?>
								<?php get_template_part( 'content', 'none' ); ?>
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