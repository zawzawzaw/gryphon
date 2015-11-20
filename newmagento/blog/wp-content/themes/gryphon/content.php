<div class="post">
	<div class="post-content">
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<h6><?php the_date(); ?></h6>
		<hr class="small">

		<?php the_content(); ?>		

		<!-- <img src="images/content/blog-1.jpg" class="img-responsive" alt="blog 1"> -->
		<?php //the_post_thumbnail( 'full', array('class'=>'img-responsive') ); ?>
	</div>

	<div class="row post-extra-content">
		<div class="col-md-12">
			<div class="post-extra-container">
				<div class="category post-extra">
					<h2>category</h2>
					<p>
					<?php 
					$categories = get_the_category();
					$i = 1;
					foreach ($categories as $key => $category) {
						if($i==count($categories))
							echo '<a style="display: inline-block;" href="'.get_category_link($category->term_id ).'">'.$category->cat_name.'</a>';
						else
							echo '<a style="display: inline-block;" href="'.get_category_link($category->term_id ).'">'.$category->cat_name.'</a> | ';
						$i++;
					}
					?>
					</p>
				</div>
			
				<div class="tags post-extra">
					<h2>tags</h2>
					<p>
					<?php
					$posttags = get_the_tags();
					if ($posttags) {
					  foreach($posttags as $tag) {
					  	echo '<a href="'.get_tag_link($tag->term_id).'">#'.$tag->name.' </a>';
					    // echo '#'.$tag->name . ' '; 
					  }
					}
					?>				
					</p>
				</div>
				
				<div class="sharing post-extra">
					<h2>Share</h2>
					<ul>
						<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink(get_the_ID()) ); ?>&media=<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>" class="pintrest-ico"><i class="fa fa-pinterest"></i></a></li>
						<li><a href="https://twitter.com/share?url=<?php echo urlencode( get_permalink(get_the_ID()) ); ?>&via=gryphontea&text=gryphon%20tea%20company" class="twitter-ico"><i class="fa fa-twitter"></i></a></li>
						<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(get_the_ID()); ?>" class="facebook-ico"><i class="fa fa-facebook"></i></a></li>						
						<li><a href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode( get_permalink(get_the_ID()) ); ?>&summary=gryphon%20tea%20company" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
						<li><a href="https://plus.google.com/share?url=<?php echo urlencode( get_permalink(get_the_ID()) ); ?>&hl=en-US" class="google"><i class="fa fa-google-plus"></i></a></li>						
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- <hr class="big"> -->