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


		<section class="site-header-nav nav-left nav-home" role="navigation">
			<a href="/"><span class="link-border"><?php esc_html_e( 'Accueil', 'structures' ); ?></span></a>
		</section>

		<section class="site-header-nav nav-right nav-tome" role="navigation">

		<?php
		// Display secondary-menu : Siblings pages
		if ( $post->post_parent ) {

			$parent_title = get_the_title($post->post_parent);

			$output_before =  "
			<nav id='subpage-navigation' class='secondary-navigation'>
				<button class='menu-toggle menu-toggle-nav' aria-controls='secondary-menu' aria-expanded='false'><span class='link-border'>".$parent_title."</span></button>
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

		</section>

		<section class="site-header-nav nav-about" role="navigation">
			<button class='menu-toggle menu-toggle-about' aria-controls='about' aria-expanded='false'><span class="link-border"><?php esc_html_e( 'À propos', 'structures' ); ?></span></button>
		</section>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
