<?php 
/********************************************************************************************************/
/* CONSTANTS */
/********************************************************************************************************/

define("THEMEROOT", get_stylesheet_directory_uri());
define("IMG", THEMEROOT."/images");
define("JS", THEMEROOT."/js");
define("CSS", THEMEROOT."/css");


/********************************************************************************************************/
/* MENUS */
/********************************************************************************************************/

function register_my_menus(){
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'strand'),
        'footer-menu' => __('Footer Menu', 'strand')
    ));
}

add_action('init', 'register_my_menus');

/********************************************************************************************************/
/* LOAD ASSETS */
/********************************************************************************************************/

add_action('wp_enqueue_scripts', 'load_scripts');

function load_scripts(){
    wp_enqueue_style( 'bootstrap', THEMEROOT.'/css/vendors/bootstrap/bootstrap.min.css' );
    wp_enqueue_style( 'bootstrap-theme', THEMEROOT.'/css/vendors/bootstrap/bootstrap-theme.min.css' );    
    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'raty', THEMEROOT.'/js/plugins/raty/jquery.raty.css' );
    wp_enqueue_style( 'slick', THEMEROOT.'/js/plugins/slick/slick.css' );
    wp_enqueue_style( 'slick-theme', THEMEROOT.'/js/plugins/slick/slick-theme.css' );
    wp_enqueue_style( 'stylecss', get_stylesheet_uri() );

    wp_enqueue_script('$', THEMEROOT.'/js/vendors/jquery/jquery-1.11.1.min.js', array(), '', false);    
    wp_enqueue_script('carousel', THEMEROOT.'/js/vendors/bootstrap/bootstrap.min.js', array('$'), '', true);
    wp_enqueue_script('raty', THEMEROOT.'/js/plugins/raty/jquery.raty.js', array('$'), '', true);
    wp_enqueue_script('slick', THEMEROOT.'/js/plugins/slick/slick.min.js', array('$'), '', true);    
    wp_enqueue_script('inside-pages', THEMEROOT.'/js/inside-pages.js', array('$'), '', true);    
    wp_enqueue_script('mainjs', THEMEROOT.'/js/main.js', array('$'), '', true);    
}

/********************************************************************************************************/
/* ADD POST THUMBNAIL SUPPORT */
/********************************************************************************************************/

if(function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(940, 346);
}

// hide admin bar from front end
function hide_admin_bar_from_front_end(){
  if (is_blog_admin()) {
    return true;
  }
  return false;
}
add_filter( 'show_admin_bar', 'hide_admin_bar_from_front_end' );

add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Main Sidebar', 'theme-slug' ),
        'id' => 'sidebar-1',
        'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget'  => '</li>',
    'before_title'  => '<h2 class="widgettitle">',
    'after_title'   => '</h2>',
    ) );
}

function MyAjaxFunction(){
    //get the data from ajax() call
    $GreetingAll = $_POST['GreetingAll'];
    $results = "<h2>".$GreetingAll."</h2>";
    // Return the String
    die($results);
}
// creating Ajax call for WordPress
add_action( 'wp_ajax_nopriv_MyAjaxFunction', 'MyAjaxFunction' );
add_action( 'wp_ajax_MyAjaxFunction', 'MyAjaxFunction' );