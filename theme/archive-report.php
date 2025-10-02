<?php
/**
 * Template Name: Reports Archive
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header(); ?>

<div class="page-container">
    <!-- <h1 class="text-4xl font-bold mb-8">Reports</h1> -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="bg-white rounded-lg shadow">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php the_post_thumbnail_url('medium') ?>" alt="<?php the_title(); ?>" class="mb-4 w-full object-cover rounded">
                        </a>
                    <?php endif; ?>
                    <div class="p-3">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
    
                        <p class="text-gray-600 mb-4">
                            <?php the_field('report_desc'); ?>
                        </p>
    
                        <a href="<?php the_permalink(); ?>" class="text-green-700 hover:underline">View report</a>
                    </div>
                </div>
            <?php endwhile;
        else: ?>
            <p>No reports found.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
