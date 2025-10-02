<?php

/**
 * Template Name: Single E-Meet
 * 
 * The template for displaying single e-meet posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header();
?>

<?php while (have_posts()) : the_post();
    $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if (!$image) {
        $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    $title = get_the_title();
    $content = get_the_content();
    $date = get_the_date();

    // Get custom fields if using ACF
    $meeting_date = get_field('date');
    $meeting_time = get_field('time');
    $meeting_link = get_field('link');
    $meeting_description = get_field('description');
    $meeting_agenda = get_field('agenda');
    $meeting_location = get_field('location');
    $subtitle = get_field('subtitle');
    $gallery = get_field('gallery');
?>

 <!-- Event Details Bar -->
    <section class="border-b border-gray-100">
        <div class="page-container py-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
                <?php if ($meeting_date): ?>
                    <div class="flex align-middle gap-2 items-center">
                        <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="text-lg font-semibold text-gray-900"><?php echo esc_html($meeting_date); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($meeting_time): ?>
                    <div class="flex align-middle gap-2 items-center">
                        <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-lg font-semibold text-gray-900"><?php echo esc_html($meeting_time); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($meeting_location): ?>
                    <div class="flex align-middle gap-2 items-center">
                        <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="text-lg font-semibold text-gray-900"><?php echo esc_html($meeting_location); ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hidden">
        <?php if ($image): ?>
            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-[20rem] object-cover">
        <?php endif; ?>
    </section>


    <!-- Main Content -->
    <div class="page-container pt-16 pb-8">
        <section class="">
            <div class="grid sm:grid-cols-2 gap-8">
                <div class="">
                    <div class="prose prose-lg max-w-none text-gray-700">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                </div>
                <div>
                    <img src="<?php echo $meeting_agenda; ?>" alt="Meeting Agenda" class="w-full h-auto rounded-lg shadow-lg">
                </div>
            </div>
        </section>
    </div>

    <!-- Gallery -->
    <div class="pb-16 gallery-section">
        <div class="mx-4">
            <?php echo wp_kses_post($gallery); ?>
        </div>
    </div>

    <!-- Tags and Navigation -->
    <div class="page-container py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Tags -->
            <?php
            $tags = get_the_tags();
            if ($tags): ?>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Topics</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($tags as $tag): ?>
                            <a href="<?php echo get_tag_link($tag->term_id); ?>" class="inline-block px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full hover:bg-blue-200 transition-colors duration-200">
                                <?php echo esc_html($tag->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Navigation -->
            <nav class="border-t border-gray-200 pt-8">
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <?php
                        $prev_post = get_previous_post();
                        if ($prev_post): ?>
                            <a href="<?php echo get_permalink($prev_post); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200 group">
                                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <div>
                                    <div class="text-sm text-gray-500">Previous</div>
                                    <div class="font-medium"><?php echo wp_trim_words(get_the_title($prev_post), 6); ?></div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1 text-center">
                        <a href="<?php echo get_post_type_archive_link('e-meet'); ?>" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            All E-Meets
                        </a>
                    </div>

                    <div class="flex-1 text-right">
                        <?php
                        $next_post = get_next_post();
                        if ($next_post): ?>
                            <a href="<?php echo get_permalink($next_post); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200 group">
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Next</div>
                                    <div class="font-medium"><?php echo wp_trim_words(get_the_title($next_post), 6); ?></div>
                                </div>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>