<div class="each-sidebar-content">	
	<form role="search" method="get" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="search" placeholder="<?php echo esc_attr( 'Search…', 'presentation' ); ?>" name="s" id="search-input" value="<?php echo esc_attr( get_search_query() ); ?>" />
        <input class="screen-reader-text" type="submit" id="search-submit" value="Search" placeholder="Type the keywords" style="display:none;" />
	</form>
</div>