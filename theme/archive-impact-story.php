<?php get_header(); ?>

<div class="page-container">
    <!-- <h1 class="text-4xl font-bold mb-8">Impact Stories</h1> -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
    <?
                $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $testimonial = get_field('short_quote'); //get_the_content();
                $name = get_the_title();
                $position = get_field('position');
                $short_quote = get_field('short_quote');
                $university = get_field('university');
                $country = get_field('country');
                $post_link = get_permalink();
            ?>
                <a href="<?php echo esc_url($post_link); ?>" class="relative flex aspect-9/16 w-72 shrink-0 snap-start scroll-ml-[var(--scroll-padding)] flex-col justify-end overflow-hidden rounded-xl sm:aspect-3/4 sm:w-86 group transition-transform duration-300 hover:z-10 hover:shadow-xl">
                    <?php if ($image) : ?>
                        <div class="absolute inset-x-0 top-0 aspect-square w-full overflow-hidden">
                            <img alt="<?php echo esc_attr($name); ?>"
                                src="<?php echo esc_url($image); ?>"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105">
                        </div>
                    <?php endif; ?>
                    <div aria-hidden="true" class="absolute inset-0 rounded-xl bg-linear-to-t from-black from-[calc(7/16*100%)] ring-1 ring-gray-950/10 ring-inset sm:from-25%"></div>
                    <figure class="relative p-6">
                        <blockquote>
                            <p class="relative text-lg/5 text-white line-clamp-3">
                                <span aria-hidden="true" class="absolute -translate-x-full">"</span>
                                <?php echo esc_html($testimonial); ?>
                                <span aria-hidden="true" class="absolute">"</span>
                            </p>
                        </blockquote>
                        <figcaption class="mt-6 border-t border-white/20 pt-6">
                            <p class="text-md/6 font-mt font-medium text-white line-clamp-1"><?php echo esc_html($name); ?></p>
                            <p class="text-sm/6 font-medium">
                                <span class="fancy-text">
                                    <?php if ($university) echo esc_html($university) . ','; ?>
                                </span>
                                <span class="text-mt-cream"><?php echo esc_html($country); ?></span>
                            </p>
                        </figcaption>
                    </figure>
                </a>
            <?php endwhile;
        else: ?>
            <p>No Impact Stories found.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
