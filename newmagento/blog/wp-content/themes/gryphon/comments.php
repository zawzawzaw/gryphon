<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

	<h2 class="comments-title">
		<?php
			printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'gryphon' ),
				number_format_i18n( get_comments_number() ), get_the_title() );
		?>
	</h2>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'gryphon' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'gryphon' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'gryphon' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>

	<ol class="comment-list">
		<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 34,
			) );
		?>
	</ol><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'gryphon' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'gryphon' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'gryphon' ) ); ?></div>
	</nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'gryphon' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php 
		$fields =  array(
		  'author' =>
		    '<div class="comment-form-author each-input"><label for="author">' . __( 'Name', 'domainreference' ) . '<em>*</em></label></div> ' .
		    '<input id="author" name="author" type="text" required="true" value="' . esc_attr( $commenter['comment_author'] ) .
		    '" size="30"' . $aria_req . ' />',

		  'email' =>
		    '<div class="comment-form-email each-input"><label for="email">' . __( 'Email', 'domainreference' ) . '<em>*</em></label></div> ' .
		    '<input id="email" name="email" type="text" required="true" value="' . esc_attr(  $commenter['comment_author_email'] ) .
		    '" size="30"' . $aria_req . ' />',

		  'url' =>
		    '<div class="comment-form-url each-input"><label for="url">' . __( 'Website', 'domainreference' ) . '</label></div>' .
		    '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
		    '" size="30" />',
		);

		$args = array(
		  	'id_form'           => 'commentform',
		  	'id_submit'         => 'submit',
		  	'class_submit'      => 'submit',
		  	'name_submit'       => 'submit',
		  	'title_reply'       => __( 'Leave a Reply' ),
		  	'title_reply_to'    => __( 'Leave a Reply to %s' ),
		  	'cancel_reply_link' => __( 'Cancel Reply' ),
		  	'label_submit'      => __( 'Post Comment' ),
		  	'format'            => 'xhtml',

		  	'comment_field' =>  '<div class="comment-form-comment each-input"><label for="comment">' . _x( 'Comment', 'noun' ) .
		    '<em>*</em></label><textarea id="comment" name="comment" required="true" cols="45" rows="8" aria-required="true">' .
		    '</textarea></div>',

		  	'must_log_in' => '<p class="must-log-in">' .
		    sprintf(
		      __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
		      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
		    ) . '</p>',

		  	'logged_in_as' => '<p class="logged-in-as">' .
		    sprintf(
		    __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
		      admin_url( 'profile.php' ),
		      $user_identity,
		      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
		    ) . '</p>',

		  	'comment_notes_before' => '<p class="comment-notes">' .
		    __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) .
		    '</p>',

		  	'comment_notes_after' => '<p class="form-allowed-tags">' .
		    sprintf(
		      __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ),
		      ' <code>' . allowed_tags() . '</code>'
		    ) . '</p>',

		  	'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		);
	?>

	<?php comment_form($args); ?>

</div><!-- #comments -->
