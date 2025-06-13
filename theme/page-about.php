<?php

/**
 * Template Name: MT About Page
 *
 * @package Michael_Taiwo_Scholarship
 * 
 **/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

$colors = array('black', 'gray-200', 'gray-300', 'gray-400');

// Query team members
$team_args = array(
    'post_type' => 'team-member',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);

$team_query = new WP_Query($team_args);

// Board Members
$board_args = array(
    'post_type' => 'board-member',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
);

$board_members_query = new WP_Query($board_args);
?>

<section id="">
    <div class="page-container space-y-28">
        <div class="grid sm:grid-cols-2 gap-8 sm:gap-16">
            <div class="flex flex-col">
                <h2 class="w-full font-heading will-change-transform ">MT Scholarships </h2>
                <div class="mt-5 flex flex-col gap-4 w-full will-change-transform md:mt-[25px]" data-projection-id="244" style="opacity: 1;">
                    <p>Founded by Dr. Michael Taiwo, we remove financial barriers preventing talented students in developing countries from accessing global education.</p>
                    <p>We provide approximately $1,000 per scholar to cover standardized tests and application fees, plus comprehensive mentorship throughout their journey.</p>
                </div>
                <div class="mt-6 md:mt-[60px] " data-projection-id="245">
                    <div class="w-full md:w-auto ">
                        <div class=" flex space-x-4 pb-[5px] md:space-x-5 md:pb-[10px]">
                            <div class="flex flex-col md:flex-row gap-6">
                                <a class="button main-btn" href="<?php echo get_page_url_by_title('1% challenge') ?>">
                                    <p class=" text-nowrap leading-none">Join the 1% Challenge</p>
                                </a>
                                <a class="button secondary-btn" href="<?php echo get_page_url_by_title('Volunteer') ?>">
                                    <p class="text-nowrap leading-none">Become a Volunteer</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden sm:block">
                <img class="w-full h-full object-top object-cover rounded-2xl" src="<?php echo esc_url(get_template_directory_uri() . '/imgs/Dr-Michael Taiwo.jpg'); ?>" alt="Dr Michael Taiwo_Annual Scholarship">
            </div>
        </div>
    </div>
</section>

<section id="our-board" class="bg-mt-cream">
    <div class="page-container">
        <div>
            <h2 class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase data-dark:text-gray-400">Meet the board</h2>
            <h3 class="mt-2">Our board</h3>
        </div>
        <div class="">
            <?php if ($board_members_query->have_posts()) : ?>
                <div class="grid sm:grid-cols-4 gap-6 justify-center">
                    <?php while ($board_members_query->have_posts()) : $board_members_query->the_post();
                        $role = get_field('role');
                        $linkedin_url = get_field('linkedin');
                        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    ?>
                        <div class="col-span-1 flex flex-col">
                            <div class="relative h-[270px] w-full rounded-lg bg-white">
                                <?php if ($featured_image) : ?>
                                    <img
                                        alt="<?php the_title(); ?>"
                                        draggable="false"
                                        class="h-[270px] object-top w-full aspect-video object-cover rounded-md"
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
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p>No team members found.</p>
            <?php endif; ?>
        </div>

    </div>
</section>

<section id="team-values">
    <img class="w-full h-[16rem] sm:h-[30rem] object-top overflow-y-scroll object-cover rounded-t-2xl" src="<?php echo esc_url(get_template_directory_uri() . '/imgs/team.jpg'); ?>" alt="">
    <div class="page-container -mt-24 mb-5 pt-0 rounded-t-lg bg-white relative space-y-28">
        <div class="pt-6 sm:p-16">
            <h2 class="">Join the movement</h2>
            <div class="items-center align-middle gap-4">
                <div class="grid sm:grid-cols-2 gap-10">
                    <div class="flex justify-between flex-col gap-10">
                        <div class="flex flex-col gap-6">
                            <p class="max-w-lg">Help exceptional students from developing countries pursue graduate studies abroad by removing barriers of cost and access. Be part of the team making it possible.</p>
                        </div>
                        <div class="flex flex-col md:flex-row gap-6">
                            <a class="button main-btn" href="<?php echo get_page_url_by_title('Volunteer') ?>">
                                <p class=" text-nowrap leading-none">Join our team</p>
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <ul class="space-y-10">
                            <li><span class="font-sans block mb-2 font-medium">Mission</span>To support high-potential students from developing countries in accessing global graduate education through funding and mentorship.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-12 sm:mt-26">
                <div>
                    <h2 class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase data-dark:text-gray-400">Meet the team</h2>
                    <h3 class="mt-2">Our team</h3>
                </div>
                <?php if ($team_query->have_posts()) : ?>
                    <div class="grid sm:grid-cols-4 gap-6 justify-center">
                        <?php while ($team_query->have_posts()) : $team_query->the_post();
                            $role = get_field('role');
                            $linkedin_url = get_field('linkedin');
                            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        ?>
                            <div class="col-span-1 flex flex-col">
                                <div class="relative h-[250px] w-full rounded-lg bg-white">
                                    <?php if ($featured_image) : ?>
                                        <img
                                            alt="<?php the_title(); ?>"
                                            draggable="false"
                                            class="h-[250px] w-full object-top aspect-video object-cover rounded-md"
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
                                    <a class="text-xl font-mt" href="<?php echo esc_url(get_the_permalink()) ?>">
                                        <?php esc_html_e(get_the_title()); ?>
                                    </a>
                                    <?php if ($role) : ?>
                                        <div class="max-w-[90%] opacity-80 md:max-w-[100%]"><?php echo esc_html($role); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p>No team members found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
