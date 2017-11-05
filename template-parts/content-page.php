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

		<?php the_title( '<h1 class="entry-title"> <a class="onboarding-navigation" href="#article-navigation">', '</a></h1>' ); ?>

		<?php
			$custom_values = get_post_custom_values( 'sous-titre' );
			foreach ( $custom_values as $key => $value ) {
				$title_length = strlen($value);
				if ($title_length < 30) {
					echo '<h2 class="entry-subtitle">' . $value . '</h2>';
				} else {
					echo '<h2 class="entry-subtitle entry-subtitle--long">' . $value . '</h2>';
				}
			}
		?>
	</header><!-- .entry-header -->

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


	<?php if ( get_edit_post_link() ) : ?>
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
