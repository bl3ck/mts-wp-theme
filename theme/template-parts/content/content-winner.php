<?php

/**
 * Template part for displaying single winners
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Michael_Taiwo_Scholarship
 */
?>

<?php
// University: array of term IDs
$university_ids = get_field('university');
$universities = is_array($university_ids)
	? array_map(fn($id) => get_term($id)->name, $university_ids)
	: [];

// Course: array of term IDs
$course_ids = get_field('course_of_study');
$courses = is_array($course_ids)
	? array_map(fn($id) => get_term($id)->name, $course_ids)
	: [];

// Country: single term ID
$country_id = get_field('country');
$country = $country_id ? get_term($country_id)->name : '';

// Graduation Year: single term ID
$graduation_year_id = get_field('graduation_year');
$graduation_year = is_array($graduation_year_id)
	? array_map(fn($id) => get_term($id)->name, $graduation_year_id)
	: [];

// Awarded Year: single term ID
$awarded_year_id = get_field('awarded_year');
$awarded_year = $awarded_year_id ? get_term($awarded_year_id)->name : '';

// CGPA (regular ACF field, not taxonomy)
$cgpa = get_field('cgpa');

$cgpa = get_field('cgpa');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="page-container">

		<div class="grid sm:grid-cols-2 align-start items-start justify-start gap-6 gap-6">
			<div class="">
				<?php
				echo get_the_post_thumbnail(get_the_ID(), 'large', [
					'class' => 'w-96 sm:w-[24rem] h-auto rounded shadow-md object-contain'
				]);
				?>
			</div>
			<div class="space-y-2 text-sm text-gray-600">
				<?php if (!empty($awarded_year)) : ?>
					<p class="flex items-start mb-6">
						<span class="bg-mt-blue text-white font-semibold px-6 py-1 rounded"><?php echo esc_html($awarded_year); ?> Winner</span>
					</p>
				<?php endif; ?>

				<?php if (!empty($universities)) : ?>
					<p class="flex items-start">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
						</svg>
						<span><?php echo esc_html(implode(', ', $universities)); ?></span>
					</p>
				<?php endif; ?>

				<?php if (!empty($courses)) : ?>
					<p class="flex items-start">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
						</svg>
						<span><?php echo esc_html(implode(', ', $courses)); ?></span>
					</p>
				<?php endif; ?>

				<?php if (!empty($country)) : ?>
					<p class="flex items-start">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
						<span><?php echo esc_html($country); ?></span>
					</p>
				<?php endif; ?>

				<?php if (!empty($graduation_year)) : ?>
					<p class="flex items-start">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
						</svg>
						<span><?php echo esc_html(implode(', ', $graduation_year)); ?></span>
					</p>
				<?php endif; ?>

				<?php if (!empty($cgpa)) : ?>
					<p class="flex items-start">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
						</svg>
						<span><span class="font-medium">CGPA:</span> <?php echo esc_html($cgpa); ?></span>
					</p>
				<?php endif; ?>

				<!-- content -->
				<div <?php mt_content_class('entry-content'); ?>>
					<?php
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers. */
								__('Continue reading<span class="sr-only"> "%s"</span>', 'michael-taiwo-scholarship'),
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
							'before' => '<div>' . __('Pages:', 'michael-taiwo-scholarship'),
							'after'  => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->
			</div>
		</div>

		<footer class="entry-footer">
			<?php mt_entry_footer(); ?>
		</footer><!-- .entry-footer -->

	</div>

</article><!-- #post-${ID} -->