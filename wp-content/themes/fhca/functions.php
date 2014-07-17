<?php
/**
 * FHCA functions and definitions
 *
 * @package FHCA
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'fhca_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function fhca_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on FHCA, use a find and replace
	 * to change 'fhca' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'fhca', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'fhca' ),
	) );
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link'
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'fhca_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
	// Enables post thumbnails for pages
	add_theme_support( 'post-thumbnails', array( 'page' ) );
}
endif; // fhca_setup
add_action( 'after_setup_theme', 'fhca_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function fhca_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'fhca' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'fhca_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function fhca_scripts() {
	wp_enqueue_style( 'fhca-style', get_stylesheet_uri() );

	/*wp_enqueue_script( 'fhca-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'fhca-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	*/

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fhca_scripts' );

/* Removes the top level pages from the search results, or any particular page ID that needs to removed from search results*/

function jp_search_filter( $query ) {
  if ( $query->is_search && $query->is_main_query() ) {
    $query->set( 'post__not_in', array( 6,10,12,14,16 ) ); 
  }
}
add_filter( 'pre_get_posts', 'jp_search_filter' );

/* Add excerpt option to pages for the home page summariess*/
add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

function the_post_excerpt_by_id($ID, $limit) {
	$page_data = get_page($ID);
    $excerpt = strip_tags($page_data->post_excerpt);
    $excerpt_length = $limit; //number of words to limit the execerpt to
    
    $words = explode(' ', $excerpt, $excerpt_length + 1);

    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '…');
        $excerpt = implode(' ', $words);
    endif;

    echo $excerpt;
}