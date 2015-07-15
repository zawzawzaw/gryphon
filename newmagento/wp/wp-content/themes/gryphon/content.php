<div class="post">
	<div class="post-content">
		<h1><?php the_title(); ?></h1>
		<span><?php the_date(); ?></span>
		<hr class="small">

		<?php the_content(); ?>

		<!-- <img src="images/content/blog-1.jpg" class="img-responsive" alt="blog 1"> -->
		<?php the_post_thumbnail( 'full', array('class'=>'img-responsive') ); ?>
	</div>

	<div class="row post-extra-content">
		<div class="col-md-12">
			<div class="category post-extra">
				<h2>category</h2>
				<?php 
				$category = get_the_category(); 
				if($category[0]){
					echo '<p><a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a></p>';
				}
				?>
			</div>
		
			<div class="tags post-extra">
				<h2>tags</h2>
				<p>
				<?php
				$posttags = get_the_tags();
				if ($posttags) {
				  foreach($posttags as $tag) {
				    echo '#'.$tag->name . ' '; 
				  }
				}
				?>				
				</p>
			</div>
			
			<div class="sharing post-extra">
				<ul>
					<li><a href="#" class="instagram"></a></li>
					<li><a href="#" class="pintrest"></a></li>
					<li><a href="#" class="twitter"></a></li>
					<li><a href="#" class="facebook"></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- <hr class="big"> -->