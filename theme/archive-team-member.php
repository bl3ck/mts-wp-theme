<?php
/**
 * Template Name: Team Members Archive
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header(); ?>

<?php
$args = array(
    'post_type' => 'team-member',
    'orderby' => 'date',
    'order' => 'DESC'
);

$team_query = new WP_Query($args);
?>

<div class="page-container">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php if ($team_query->have_posts()) : while ($team_query->have_posts()) : $team_query->the_post(); ?>
                <div class="bg-white rounded-lg shadow hidden">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php the_post_thumbnail_url('medium') ?>" alt="<?php the_title(); ?>" class="mb-4 h-72 w-full object-top object-cover rounded">
                        </a>
                    <?php endif; ?>
                    <div class="p-3">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>

                        <p class="text-gray-600 mb-4">
                            <?php the_field('report_desc'); ?>
                        </p>

                        <a href="<?php the_permalink(); ?>" class="text-blue-500 hover:underline">View report</a>
                    </div>
                </div>
                <?php
                    $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    $name = get_the_title();
                    $country = get_field('country');
                    $post_link = get_permalink();
                    $role = get_field('role');
                    $linkedin_url = get_field('linkedin');
                    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                ?>
                <div class="col-span-1 flex flex-col">
                    <div class="relative h-[300px] w-full rounded-lg bg-white">
                        <?php if ($featured_image) : ?>
                            <img
                                alt="<?php the_title(); ?>"
                                draggable="false"
                                class="h-[300px] object-top w-full aspect-video object-cover rounded-md"
                                loading="lazy"
                                class="rounded-lg opacity-100 transition-opacity duration-200"
                                src="<?php echo esc_url($featured_image); ?>"
                                srcset="<?php echo esc_url($featured_image); ?> 1x, <?php echo esc_url($featured_image); ?> 2x">
                        <?php endif; ?>

                        <?php if ($linkedin_url) : ?>
                            <a
                                href="<?php echo esc_url($linkedin_url); ?>"
                                target="_blank"
                                class="absolute bottom-[5%] right-[5%] z-10"
                                rel="noreferrer"
                                tabindex="0">
                                <span class="h-[26px] w-[26px] text-white transition-colors duration-300 block">
                                    <svg width="100%" height="100%" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_1_6519)">
                                            <path d="M16.149 0.323242H1.07805C0.665146 0.323242 0.355469 0.63292 0.355469 1.04582V16.22C0.355469 16.5297 0.665146 16.8394 1.07805 16.8394H16.2522C16.6651 16.8394 16.9748 16.5297 16.9748 16.1168V1.04582C16.8716 0.63292 16.5619 0.323242 16.149 0.323242ZM5.20708 14.362H2.83289V6.51679H5.31031V14.362H5.20708ZM4.0716 5.48453C3.24579 5.48453 2.62644 4.76195 2.62644 4.03937C2.62644 3.21356 3.24579 2.59421 4.0716 2.59421C4.8974 2.59421 5.51676 3.21356 5.51676 4.03937C5.41353 4.76195 4.79418 5.48453 4.0716 5.48453ZM14.3942 14.362H11.9168V10.5426C11.9168 9.61356 11.9168 8.47808 10.6781 8.47808C9.43934 8.47808 9.23289 9.51034 9.23289 10.5426V14.4652H6.75547V6.51679H9.12966V7.54905C9.43934 6.92969 10.2651 6.31034 11.4006 6.31034C13.8781 6.31034 14.291 7.96195 14.291 10.0265V14.362H14.3942Z" fill="currentColor"></path>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1_6519">
                                                <rect width="16.5161" height="16.5161" fill="white" transform="translate(0.355469 0.322266)"></rect>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="mt-1.5">
                        <div class="">
                            <a class="text-xl font-mt" href="<?php echo esc_url(get_the_permalink()) ?>">
                                <?php esc_html_e(get_the_title()); ?>
                            </a>
                        </div>
                        <?php if ($role) : ?>
                            <div class="max-w-[90%] opacity-80 md:max-w-[100%]"><?php echo esc_html($role); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile;
        else: ?>
            <p>No team Members found.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>