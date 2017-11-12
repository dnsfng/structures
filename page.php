<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package structures
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

		<?php	if ( has_children() ) {?>
			<aside class="site-secondary" >

				<h1 class="section-title">Autres publications</h1>

				<nav id="volume-navigation" class="secondary-navigation">
					<?php // Display primary-menu : All pages
					wp_page_menu(
						array(
							'depth' => 1,
							'exclude' => get_the_ID(),
							'menu_class' => 'menu secondary-menu',
							'walker' => new Menu_with_images_and_extra_label_Walker()
						)
					);
					?>
				</nav><!-- #site-navigation -->
			</aside>
		<?php	}; ?>

	</div><!-- #primary -->

<?php
get_footer();
