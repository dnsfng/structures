<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package structures
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	if ( has_children() ) {
			echo '<aside class="entry-image">
							<a class="onboarding-navigation" href="#article-navigation">';
					the_post_thumbnail('single-post-thumbnail');
			echo '</a>
						</aside> <!-- .entry-image -->';
		};
	?>

	<header class="entry-header">

		<?php	if ( has_children() ) {
			the_title( '<h1 class="entry-title"> <a class="onboarding-navigation" href="#article-navigation">', '</a></h1>' );
		} else {
			the_title( '<h1 class="entry-title">', '</h1>' );
		};
		?>

		<?php

			$subtitle = get_post_meta(get_the_ID(), 'sous-titre', true);
			$subtitle_length  = strlen($subtitle);

			if ($subtitle != "" )  {
				if ($subtitle_length < 30) {
					echo '<h2 class="entry-subtitle">' . $subtitle . '</h2>';
				} else {
					echo '<h2 class="entry-subtitle entry-subtitle--long">' . $subtitle . '</h2>';
				}
			};

		?>

	</header><!-- .entry-header -->

	<?php if (get_post_meta(get_the_ID(), 'telechargement', true) != "" )  {?>

		<div class="entry-download">
			<a class='dl-article' href="<?php echo get_post_meta(get_the_ID(), 'telechargement', true) ?>" >
				<span class='link-border'>
					<?php esc_html_e( 'Télécharger ce texte', 'structures' ); ?>
				</span>
			</a>
		</div>

	<?php }; ?>

	<div class="entry-content">
		<?php
			if( has_excerpt() ){
				the_excerpt();
			}

			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'structures' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->

	<?php	if ( has_children() ) { ?>
		<section class="site-header-nav nav-about" role="navigation">
			<a href="#about" class='menu-toggle menu-toggle-about' aria-controls='about' aria-expanded='false'>
				<span class="link-border">
						<?php esc_html_e( 'À propos', 'structures' ); ?>
				</span>
			</a>
		</section>
	<?php }; ?>

	<?php if ( get_edit_post_link() ) : ?>
		<?php structures_entry_footer(); ?>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'structures' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
