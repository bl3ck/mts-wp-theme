<?php
/**
 * Template Name: Single Winner
 * 
 * The template for displaying single winner posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header();
?>

	<section id="primary">
		<main id="main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/content', 'winner' );

				if ( is_singular( 'post' ) ) {
					// Previous/next post navigation.
					the_post_navigation(
						array(
							'next_text' => '<span aria-hidden="true">' . __( 'Next Post', 'michael-taiwo-scholarship' ) . '</span> ' .
								'<span class="sr-only">' . __( 'Next post:', 'michael-taiwo-scholarship' ) . '</span> <br/>' .
								'<span>%title</span>',
							'prev_text' => '<span aria-hidden="true">' . __( 'Previous Post', 'michael-taiwo-scholarship' ) . '</span> ' .
								'<span class="sr-only">' . __( 'Previous post:', 'michael-taiwo-scholarship' ) . '</span> <br/>' .
								'<span>%title</span>',
						)
					);
				}

				// End the loop.
			endwhile;
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();

