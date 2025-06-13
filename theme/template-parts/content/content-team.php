<?php

/**
 * Template part for displaying single team members
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
$graduation_year = $graduation_year_id ? get_term($graduation_year_id)->name : '';

// Awarded Year: single term ID
$awarded_year_id = get_field('awarded_year');
$awarded_year = $awarded_year_id ? get_term($awarded_year_id)->name : '';

// CGPA (regular ACF field, not taxonomy)
$cgpa = get_field('cgpa');
$cgpa = get_field('cgpa');


$image = get_the_post_thumbnail_url(get_the_ID(), 'large');
$name = get_the_title();
$country = get_field('country');
$post_link = get_permalink();
$role = get_field('role');
$linkedin_url = get_field('linkedin');
$featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');

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
                <div class="mt-1.5">
                    <?php if ($role) : ?>
                        <div class="max-w-[90%] opacity-80 md:max-w-[100%]"><?php echo esc_html($role); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex flex-col gap-3 space-y-2 text-sm text-gray-600">
                <?php if ($linkedin_url) : ?>
                    <a
                        href="<?php echo esc_url($linkedin_url); ?>"
                        target="_blank"
                        class="my-2.5"
                        rel="noreferrer"
                        tabindex="0">
                        <span class="h-[26px] w-[26px] text-black transition-colors duration-300 block">
                            <svg width="100%" height="100%" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1_6519)">
                                    <path d="M16.149 0.323242H1.07805C0.665146 0.323242 0.355469 0.63292 0.355469 1.04582V16.22C0.355469 16.5297 0.665146 16.8394 1.07805 16.8394H16.2522C16.6651 16.8394 16.9748 16.5297 16.9748 16.1168V1.04582C16.8716 0.63292 16.5619 0.323242 16.149 0.323242ZM5.20708 14.362H2.83289V6.51679H5.31031V14.362H5.20708ZM4.0716 5.48453C3.24579 5.48453 2.62644 4.76195 2.62644 4.03937C2.62644 3.21356 3.24579 2.59421 4.0716 2.59421C4.8974 2.59421 5.51676 3.21356 5.51676 4.03937C5.41353 4.76195 4.79418 5.48453 4.0716 5.48453ZM14.3942 14.362H11.9168V10.5426C11.9168 9.61356 11.9168 8.47808 10.6781 8.47808C9.43934 8.47808 9.23289 9.51034 9.23289 10.5426V14.4652H6.75547V6.51679H9.12966V7.54905C9.43934 6.92969 10.2651 6.31034 11.4006 6.31034C13.8781 6.31034 14.291 7.96195 14.291 10.0265V14.362H14.3942Z" fill="currentColor"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_1_6519">
                                        <rect width="16.5161" height="16.5161" fill="black" transform="translate(0.355469 0.322266)"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                        </span>
                    </a>
                <?php endif; ?>
                

                <!-- content -->
                <div <?php mt_content_class('entry-content !py-0'); ?>>
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