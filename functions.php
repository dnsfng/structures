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
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * Enable support for Excerpt on pages.
	 * Add class to excerpt.
	 * Remove excerpt length limit.
	 */
	add_post_type_support( 'page', 'excerpt' );
	add_filter( "the_excerpt", "structures_add_class_to_excerpt" );
	function structures_add_class_to_excerpt( $excerpt ) {
	    return str_replace('<p', '<p class="entry-excerpt"', $excerpt);
	}

	/*
	 * Remove post edit and comments edit
	 */
	add_action('admin_menu', 'structures_remove_menu');
	function structures_remove_menu () {
   remove_menu_page('edit.php');
	 remove_menu_page('edit-comments.php');
	}

	/*
	 * Check if current page is a parent-page
	 */
	function has_children() {
    global $post;

    $children = get_pages( array( 'child_of' => $post->ID ) );
    if( count( $children ) == 0 ) {
        return false;
    } else {
        return true;
    }
}

	// Image size for single posts
	// add_image_size( 'single-post-thumbnail', 1920,1920 );
	// TODO: revert thumbnail size
	add_image_size( 'single-post-thumbnail', 800,800 );

	// Automatic non breakable space in content
	add_filter( 'the_content', 'structures_automatic_nbsp' );
	if( !function_exists( 'strucutures_automatic_nbsp' ) ) {
	    function structures_automatic_nbsp($content) {
	        $chars_after = '?!:;”»';
	        $content = preg_replace('/ (['.$chars_after.'])/', '&nbsp;${1}', $content);
	        return $content;
	    }
	}

	// Add childpage display shortcode and add list structure (ul)
	add_shortcode( 'list_children', 'structures_list_child_posts' );
	function structures_list_child_posts( $atts ) {
    global $id;
		add_filter( 'wp_list_pages', 'structures_list_child_posts_output' );
    wp_list_pages( array(
        'title_li'    => '',
        'child_of'    => $id,
        'sort_column' => 'menu_order',
				'post_status' => array('publish', 'future', 'pending'),
				'walker'			=> new Menu_with_images_Walker()
    ) );
		remove_filter( 'wp_list_pages', 'structures_list_child_posts_output' );
		// NOTE: Need to remove filter so other menus don't herit this specific output
	}

	function structures_list_child_posts_output($output) {
		$output_before =  "<nav id='article-navigation' class='main-navigation'> <div class='menu primary-menu'><ul class='nav-menu'>";
		$output_after =  "</ul></div></nav>";

		$output = $output_before.$output.$output_after ;

		return $output;
	}

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

			// Add rules for pending and future pages
			if ( $page->post_status != 'publish' ) {
				$css_class[] = 'page-status-' . $page->post_status;
				$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
				$link = "";
				$soon = "data-notice='".__( 'Bientôt disponible', 'structures' )."'";
			} else {
				$link = "href='".get_permalink( $page->ID )."'";
				$soon = "";
			}

	 		$output .= $indent . sprintf(
	 			'<li class="%s"><a %s %s>%s%s%s%s</a>',
	 			$css_classes,
	 			$link,
				$soon,
				// get_the_post_thumbnail($page->ID, array(1920,1920)),
				// TODO: revert thumbnail size
				// TODO: display thumbnail on homepage only
				get_the_post_thumbnail($page->ID, array(800,800)),
	 			$args['link_before'],
	 			apply_filters( 'the_title', $page->post_title, $page->ID ),
	 			$args['link_after']
	 		);

	 	}
	 }

	 /**
 	 * Secondary navigation custom walker
 	 */
	 class Menu_with_images_and_extra_label_Walker extends Walker_Page {

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

			$post_meta = get_post_meta($page->ID, 'periode', true);
			if ( ! empty ( $post_meta ) ) {
				$post_meta = '<time datetime="'.get_the_date("c").'">'.$post_meta.'</time>';
			} else {
				$post_meta = '<time datetime="'.get_the_date("c").'">'.get_the_date('d M. Y').'</time>';
			};

			$post_title = '<strong>'.apply_filters( 'the_title', $page->post_title, $page->ID ).'</strong>';

	 		$output .= $indent . sprintf(
	 			'<li class="%s"><a href="%s">%s%s%s%s%s</a>',
	 			$css_classes,
	 			get_permalink( $page->ID ),
				// get_the_post_thumbnail($page->ID, array(1920,1920)),
				// TODO: revert thumbnail size
				// TODO: display thumbnail on homepage only
				get_the_post_thumbnail($page->ID, array(360,360)),
	 			$args['link_before'],
	 			$post_title,
				$post_meta,
	 			$args['link_after']
	 		);

	 	}
	 }

	 /**
 	 * Main navigation custom walker
 	 */
 	 class Menu_with_pending_Pages extends Walker_Page {

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

 			// Add rules for pending and future pages
 			if ( $page->post_status != 'publish' ) {
 				$css_class[] = 'page-status-' . $page->post_status;
 				$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
 				$link = "";
 				$soon = "data-notice='".__( 'Bientôt disponible', 'structures' )."'";
 			} else {
 				$link = "href='".get_permalink( $page->ID )."'";
 				$soon = "";
 			}

 	 		$output .= $indent . sprintf(
 	 			'<li class="%s"><a %s %s>%s%s%s</a>',
 	 			$css_classes,
 	 			$link,
 				$soon,
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

function structures_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'À propos - Image', 'structures' ),
		'id'            => 'about-image',
		'description'   => esc_html__( 'Add widgets here.', 'structures' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
		// 'before_title'  => '<h2 class="widget-title">',
		// 'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'À propos — Texte', 'structures' ),
		'id'            => 'about-description',
		'description'   => esc_html__( 'Add widgets here.', 'structures' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
		// 'before_title'  => '<h2 class="widget-title">',
		// 'after_title'   => '</h2>',
	) );
}

function structures_widget_title($t) {
	return null;
}

add_action( 'widgets_init', 'structures_widgets_init' );
add_filter( 'widget_title', 'structures_widget_title' );

/**
 * Enqueue scripts and styles.
 */
function structures_scripts() {

	//header

	// Add custom google fonts
	wp_register_style('google-fonts', "https://fonts.googleapis.com/css?family=Old+Standard+TT&#58;400&#44;400i&#44;700&amp;subset=latin",array(),null);
	wp_enqueue_style( 'google-fonts');


	$located = locate_template( 'style.min.css' );
	 if ($located != '' ) {
	      wp_enqueue_style( 'structures-style', get_template_directory_uri() . '/style.min.css' );
	 } else {
	      wp_enqueue_style( 'structures-style', get_stylesheet_uri() );
	 }

	//footer

	// NOTE: removable;
	// wp_enqueue_script( 'structures_switch-menu', get_template_directory_uri() . '/js/structures_switch-menu.js', array(), '20171111', true );
	// wp_enqueue_script( 'structures_sub-menu', get_template_directory_uri() . '/js/structures_sub-menu.js', array(), '20171111', true );
	// wp_enqueue_script( 'structures-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20171111', true );

	wp_enqueue_script( 'structures-object-fit', get_template_directory_uri() . '/js/vendor/ofi.min.js', array(), '20171111', false );
	wp_enqueue_script( 'structures-skip-link-focus-fix', get_template_directory_uri() . '/js/_/skip-link-focus-fix.js', array(), '20171111', true );

	wp_enqueue_script( 'structures_sub-nav', get_template_directory_uri() . '/js/custom/sub-navigation.js', array(), '20171111', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'structures_scripts' );


/**
 * Remove useless support
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

function my_deregister_scripts(){
 wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

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
