<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package structures
 */

?>

	<?php if (function_exists('dynamic_sidebar') && is_active_sidebar( 'about-image' ) && is_active_sidebar( 'about-description' )) { ?>
		<section id="about" class="about site-about">
			<div class="wrapper">
				<div class="about-image">
					<?php dynamic_sidebar('about-image'); ?>
				</div>
				<div class="about-description">
					<header class="entry-header">
						<h1 class="entry-title"><?php esc_html_e( 'Véloce', 'structures' ); ?></h1>
					</header>
					<?php dynamic_sidebar('about-description'); ?>
				</div>
				<a href="#hide-about" class='panel-close panel-close-about' aria-controls='about' aria-expanded='false'>
					<span class='link-border'>
						<?php esc_html_e( 'Masquer', 'structures' ); ?>
					</span>
				</a>
			</div>
		</section>
	<?php } ?>

	</div><!-- #content -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
