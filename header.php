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

	<?php if ( $post->post_parent ) { ?>

	<header id="masthead" class="site-header" role="banner">

		<?php // Display secondary-menu : Home ?>

		<section class="site-header-nav nav-left nav-home" role="navigation">
			<a href="/"><span class="link-border"><?php esc_html_e( 'Accueil', 'structures' ); ?></span></a>
		</section>

		<section class="site-header-nav nav-right nav-tome" role="navigation">

			<nav id='subpage-navigation' class='third-navigation'>

				<button class='menu-toggle menu-toggle-nav' aria-controls='third-menu' aria-expanded='false'>
					<span class='link-border'>
						<?php echo get_the_title($post->post_parent) ?>
					</span>
				</button>

				<div class='menu third-menu'>
					<ul class='nav-menu subpage-nav-menu'>
						<?php
						// Display secondary-menu : Siblings pages navigation

						wp_list_pages(array(
							'title_li'    => '',
							'child_of' => $post->post_parent,
							'sort_column' => 'menu_order',
							'post_status' => array('publish', 'future', 'pending'),
							'walker'			=> new Menu_with_pending_Pages()
						));
						?>
					</ul>
				</div>

				<footer class='menu-footer'>

					<a class='dl-article' href="<?php echo get_post_meta(get_the_ID(), 'telechargement', true) ?>" >
						<span class='link-border'>
							<?php esc_html_e( 'Télécharger ce texte', 'structures' ); ?>
						</span>
					</a>

					<a class='dl-tome' href="<?php echo get_post_meta($post->post_parent, 'telechargement', true) ?>" >
						<span class='link-border'>
							<?php esc_html_e( 'Télécharger la revue', 'structures' ); ?>
						</span>
					</a>

					<button class='menu-toggle menu-toggle-about' aria-controls='about' aria-expanded='false'>
						<span class='link-border'>
							<?php esc_html_e( 'À propos', 'structures' ); ?>
						</span>
					</button>

				</footer>

			</nav>

		</section>

	</header><!-- #masthead -->

	<?php }; ?>

	<div id="content" class="site-content">
