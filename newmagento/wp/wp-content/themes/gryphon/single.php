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
		  		wp_list_categories( array(
		  			'title_li' => '',
		  		));
		  	?>
	  	</ul>
	  </div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#mobile-blog-sidebar').gryphon_mobile_wp_sidebar({});
		});
	</script>

	<div class="main-content single">
		<div class="image-text-content container">
			<div class="row">
				<div class="col-md-9">
					<div class="posts">
						<div id="all-posts">
							<?php														
							if ( have_posts() ) :

								while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'content', get_post_format() ); ?>
								<?php endwhile; // end of the loop. ?>																									

							<?php else : ?>
								<?php get_template_part( 'content', 'none' ); ?>
							<?php endif; ?>
						</div>						
					</div>					
				</div>
				<div class="visible-md-block visible-lg-block">
					<?php get_sidebar(); ?>					
				</div>
			</div>	
		</div>
	</div>

</div>

<?php get_footer(); ?>