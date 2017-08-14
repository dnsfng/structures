<?php
/**
 * structures functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package structures
 */


if ( ! function_exists( 'structures_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function structures_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on structures, use a find and replace
	 * to change 'structures' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'structures', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Image size for single posts
	// add_image_size( 'single-post-thumbnail', 1920,1920 );
	// TODO: revert thumbnail size
	add_image_size( 'single-post-thumbnail', 200,200 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary-menu' => esc_html__( 'Primary', 'structures' ),
	) );

	/**
	 * Main navigation custom walker
	 */
	 class Menu_with_images_Walker extends Walker_Page {

		 function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {

	 		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
	 			$t = "\t";
	 			$n = "\n";
	 		} else {
	 			$t = '';
	 			$n = '';
	 		}

	 		if ( $depth ) {
	 			$indent = str_repeat( $t, $depth );
	 		} else {
	 			$indent = '';
	 		}

	 		$css_class = array( 'page_item', 'page-item-' . $page->ID );

	 		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
	 			$css_class[] = 'page_item_has_children';
	 		}

	 		if ( ! empty( $current_page ) ) {
	 			$_current_page = get_post( $current_page );
	 			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
	 				$css_class[] = 'current_page_ancestor';
	 			}
	 			if ( $page->ID == $current_page ) {
	 				$css_class[] = 'current_page_item';
	 			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
	 				$css_class[] = 'current_page_parent';
	 			}
	 		} elseif ( $page->ID == get_option('page_for_posts') ) {
	 			$css_class[] = 'current_page_parent';
	 		}

	 		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

	 		if ( '' === $page->post_title ) {
	 			/* translators: %d: ID of a post */
	 			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
	 		}

	 		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
	 		$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

	 		$output .= $indent . sprintf(
	 			'<li class="%s"><a href="%s">%s%s%s%s</a>',
	 			$css_classes,
	 			get_permalink( $page->ID ),
				// get_the_post_thumbnail($page->ID, array(1920,1920)),
				// TODO: revert thumbnail size
				// TODO: display thumbnail on homepage only
				get_the_post_thumbnail($page->ID, array(200,200)),
	 			$args['link_before'],
	 			apply_filters( 'the_title', $page->post_title, $page->ID ),
	 			$args['link_after']
	 		);

	 	}
	 }

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'structures_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'structures_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function structures_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'structures_content_width', 640 );
}
add_action( 'after_setup_theme', 'structures_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

// TODO: remove sidebar support and widget area
// function structures_widgets_init() {
// 	register_sidebar( array(
// 		'name'          => esc_html__( 'Sidebar', 'structures' ),
// 		'id'            => 'sidebar-1',
// 		'description'   => esc_html__( 'Add widgets here.', 'structures' ),
// 		'before_widget' => '<section id="%1$s" class="widget %2$s">',
// 		'after_widget'  => '</section>',
// 		'before_title'  => '<h2 class="widget-title">',
// 		'after_title'   => '</h2>',
// 	) );
// }
// add_action( 'widgets_init', 'structures_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function structures_scripts() {

	// Add custom google fonts
	wp_register_style('google-fonts', "https://fonts.googleapis.com/css?family=Old+Standard+TT&#58;400&#44;400i&#44;700&amp;subset=latin",array(),null);
	wp_enqueue_style( 'google-fonts');

	wp_enqueue_style( 'structures-style', get_stylesheet_uri() );

	wp_enqueue_script( 'structures-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'structures-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'structures_switch-menu', get_template_directory_uri() . '/js/structures_switch-menu.js', array(), false, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'structures_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
