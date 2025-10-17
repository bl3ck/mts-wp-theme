<?php
/**
 * Template Name: MT-Meets Archive
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header(); ?>

<div class="page-container">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (have_posts()) : while (have_posts()) : the_post();
                $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $title = get_the_title();
                $post_link = get_permalink();
                $excerpt = get_the_excerpt();
                $date = get_the_date();
                
                // Get custom fields if using ACF
                $meeting_date = get_field('date');
                $meeting_location = get_field('location') ?: 'Online';
        ?>
                <article class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pb-8 pt-80 sm:pt-48 lg:pt-80">
                    <?php if ($image): ?>
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="absolute inset-0 -z-10 h-full w-full object-cover">
                    <?php endif; ?>
                    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
                    <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>

                    <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300">
                        <time class="mr-8"><?php echo esc_html($meeting_location); ?></time>
                        <div class="-ml-4 flex items-center gap-x-4">
                            <svg viewBox="0 0 2 2" class="-ml-0.5 h-0.5 w-0.5 flex-none fill-white/50">
                                <circle cx="1" cy="1" r="1"></circle>
                            </svg>
                            <div class="flex gap-x-2.5">
                                <?php echo $meeting_date ? esc_html($meeting_date) : esc_html($date); ?>
                            </div>
                        </div>
                    </div>
                    <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
                        <a href="<?php echo esc_url($post_link); ?>">
                            <span class="absolute inset-0"></span>
                            <?php echo esc_html($title); ?>
                        </a>
                    </h3>
                </article>
        <?php endwhile; ?>
        
        <?php else: ?>
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No MT-Meets found</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no mt-meets scheduled.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php
    // Pagination
    the_posts_pagination(array(
        'mid_size' => 2,
        'prev_text' => __('« Previous', 'textdomain'),
        'next_text' => __('Next »', 'textdomain'),
        'class' => 'mt-8'
    ));
    ?>
</div>

<?php get_footer(); ?>
