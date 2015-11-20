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
	  	<ul>
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
	  <div id="mobile-blog-category-container">
	  	<ul>
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

							<?php
								// If comments are open or we have at least one comment, load up the comment template.
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;
							?>
						</div>						
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

<?php get_footer(); ?>