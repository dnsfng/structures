<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package structures
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'structures' ); ?></a>

	<header id="masthead" class="site-header" role="banner">

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Veloce', 'structures' ); ?></button>
			<a class="menu-switch menu-switch__about" href="/a-propos">À propos</a>
			<a class="menu-switch menu-switch__menu" href="/">Retour au menu</a>
			<?php // TODO: translate this ?>
			<?php
			// Display primary-menu : All pages
				if (has_nav_menu('primary-menu')){
					wp_nav_menu(
						array(
							'theme_location' => 'primary-menu',
							'menu_class' => 'menu primary-menu',
							'fallback_cb' => false,
							'walker' => new Menu_with_images_Walker()
						)
					);
				} else {
					wp_page_menu(
						array(
							'menu_class' => 'menu primary-menu',
							'walker' => new Menu_with_images_Walker()
						)
					);
				}
			?>
		</nav><!-- #site-navigation -->

		<?php
		// Display secondary-menu : Siblings pages

		if ( $post->post_parent ) {

			$parent_title = get_the_title($post->post_parent);

			$output_before =  "
			<nav id='subpage-navigation' class='secondary-navigation'>
				<button class='menu-toggle' aria-controls='secondary-menu' aria-expanded='false'>".$parent_title."</button>
				<div class='menu secondary-menu'>
					<ul class='nav-menu subpage-nav-menu'>";
			$output_after =  "</ul></div></nav>";

			echo $output_before;
			wp_list_pages(array(
				'title_li'    => '',
				'child_of' => $post->post_parent,
				'sort_column' => 'menu_order'
			));
			echo $output_after;
		};
		?>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
