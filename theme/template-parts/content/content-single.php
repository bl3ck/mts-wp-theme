<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Michael_Taiwo_Scholarship
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="max-w-4xl mx-auto px-4 sm:px-6 py-20 sm:py-32">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	
			<?php if ( ! is_page() ) : ?>
				<div class="entry-meta">
					<?php mt_entry_meta(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->
	
		<?php mt_post_thumbnail(); ?>
	
		<div <?php mt_content_class( 'entry-content' ); ?>>
			<?php
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers. */
						__( 'Continue reading<span class="sr-only"> "%s"</span>', 'michael-taiwo-scholarship' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
	
			wp_link_pages(
				array(
					'before' => '<div>' . __( 'Pages:', 'michael-taiwo-scholarship' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->
	
		<footer class="entry-footer">
			<?php mt_entry_footer(); ?>
		</footer><!-- .entry-footer -->

	</div>

</article><!-- #post-${ID} -->
