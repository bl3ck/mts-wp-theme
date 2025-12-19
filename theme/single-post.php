<?php
/**
 * Template Name: Single Blog Post
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header('single');

while (have_posts()) : the_post();
    // Get author info
    $author_id = get_the_author_meta('ID');
    $author_avatar = get_avatar_url($author_id, ['size' => 200]);
    $author_image = get_field('profile-image', 'user_' . $author_id);
    $author_name = get_the_author();

    $first_name = get_user_meta($author_id, 'first_name', true);
    $last_name = get_user_meta($author_id, 'last_name', true);

    $author_full_name = trim($first_name . ' ' . $last_name);

    // Get categories (excluding uncategorized)
    $post_categories = get_the_category();
    $filtered_categories = [];
    if ($post_categories) {
        foreach ($post_categories as $category) {
            if (strtolower($category->name) !== 'uncategorized' && $category->slug !== 'uncategorized') {
                $filtered_categories[] = $category;
            }
        }
    }

    // Get featured image
    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
?>

    <main class="bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-20 sm:py-32">

            <!-- Header Section -->
            <div class="max-w-2xl mx-auto text-center mb-12">
                <!-- Title -->
                <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-8">
                    <?php the_title(); ?>
                </h1>
            </div>

            <!-- Featured Image -->
            <?php if ($featured_image): ?>
                <div class="mb-12">
                    <img src="<?= $featured_image ?>" alt="<?php the_title_attribute(); ?>" class="w-full object-cover h-50 sm:h-[30rem] object-center rounded-lg shadow-lg">
                </div>
            <?php endif; ?>

            <div class="max-w-2xl mx-auto text-center mb-12">
                <!-- Subtitle/Excerpt -->
                <?php if (has_excerpt()): ?>
                    <p class="text-xl text-gray-600 leading-relaxed mb-8">
                        <?php the_excerpt(); ?>
                    </p>
                <?php endif; ?>

                <!-- Meta Info -->
                <div class="mt-12 flex items-center justify-center gap-4 text-sm text-gray-500">
                    <div class="flex items-center gap-3">
                        <img src="<?= $author_image ?>" alt="<?= esc_html($author_full_name); ?>" class="w-10 object-cover h-10 rounded-full">
                        <span class="font-medium text-gray-900"><?= esc_html($author_full_name) ?></span>
                    </div>
                    <span>•</span>
                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('M j, Y'); ?></time>
                </div>
            </div>


            <!-- Content -->
            <div class="max-w-2xl mx-auto">
                <div class="prose prose-lg prose-gray max-w-none">
                    <?php the_content(); ?>
                </div>

                
                <!-- Categories -->
                <?php if ($filtered_categories): ?>
                    <div class="mb-8">
                        <div class="flex flex-wrap gap-2 justify-center">
                            <?php foreach ($filtered_categories as $category): ?>
                                <a href="<?= get_category_link($category->term_id) ?>" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors duration-200">
                                    <?= esc_html($category->name) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php
            // Get co-authors from ACF field
            $co_authors = get_field('authors');
            
            // Get primary author bio
            $author_bio = get_user_meta($author_id, 'description', true);
            
            // Check if we should show contributors section (primary author has bio OR there are co-authors)
            $show_contributors = !empty($author_bio) || ($co_authors && is_array($co_authors) && count($co_authors) > 0);
            
            if ($show_contributors):
            ?>
            <!-- Contributors Section -->
            <div class="max-w-2xl mx-auto mt-16 pt-8 border-t border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">
                    <?php echo ($co_authors && count($co_authors) > 0) ? 'Contributors' : 'About the Author'; ?>
                </h3>
                <div class="grid grid-cols-1 gap-6">
                    <?php if (!empty($author_bio)): ?>
                        <!-- Primary Author -->
                        <div class="flex items-start gap-4">
                            <img src="<?= esc_url($author_image ?: get_avatar_url($author_id, ['size' => 800])) ?>" 
                                 alt="<?= esc_attr($author_full_name) ?>" 
                                 class="size-24 rounded-xl object-cover flex-shrink-0">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-0 text-xl"><?= esc_html($author_full_name) ?></h4>
                                <p class="text-lg text-gray-600"><?= esc_html($author_bio) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                    // Show co-authors
                    if ($co_authors && is_array($co_authors)):
                        foreach ($co_authors as $co_author):
                            $co_author_id = $co_author['ID'];
                            $co_author_image = get_field('profile-image', 'user_' . $co_author_id);
                            $co_first_name = get_user_meta($co_author_id, 'first_name', true);
                            $co_last_name = get_user_meta($co_author_id, 'last_name', true);
                            $co_author_full_name = trim($co_first_name . ' ' . $co_last_name);
                            $co_author_bio = get_user_meta($co_author_id, 'description', true);
                            
                            // Fallback to display name if first/last name not set
                            if (empty($co_author_full_name)) {
                                $co_author_full_name = $co_author['display_name'];
                            }
                            
                            // Fallback to avatar if profile image not set
                            if (empty($co_author_image)) {
                                $co_author_image = get_avatar_url($co_author_id, ['size' => 800]);
                            }
                    ?>
                        <div class="flex items-start gap-4">
                            <img src="<?= esc_url($co_author_image) ?>" 
                                 alt="<?= esc_attr($co_author_full_name) ?>" 
                                 class="size-24 rounded-xl object-cover flex-shrink-0">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-0 text-xl"><?= esc_html($co_author_full_name) ?></h4>
                                <?php if ($co_author_bio): ?>
                                    <p class="text-lg text-gray-600"><?= esc_html($co_author_bio) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- News Letter Signup -->
            <div class="max-w-2xl mx-auto mt-16 contact-form bg-mt-cream p-6 sm:p-12 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Be Part of a Global Dreaming Tribe</h2>
                <p class="mb-5">Connect. Grow. Inspire — Join Dream Lounge. Your monthly dose of stories, strategies, and scholarship support to help you rise.</p>
                <?php echo do_shortcode('[sibwp_form id=1]'); ?>
            </div>

            <!-- Author Bio -->
            <div class="max-w-2xl hidden mx-auto mt-16 pt-8 border-t border-gray-200">
                <div class="flex items-start gap-4">
                    <img src="<?= $author_image ?>" alt="<?= esc_attr($author_name) ?>" class="w-16 h-16 rounded-full flex-shrink-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2"><?= $author_name ?></h3>
                        <?php if (get_the_author_meta('description')): ?>
                            <p class="text-gray-600"><?php the_author_meta('description'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Related Posts or Navigation -->
            <div class="max-w-2xl mx-auto mt-16 pt-8 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>

                    <?php if ($prev_post): ?>
                        <a href="<?= get_permalink($prev_post) ?>" class="group flex items-center gap-2 text-blue-600 hover:text-blue-700 transition-colors">
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <div class="text-left">
                                <div class="text-sm text-gray-500">Previous</div>
                                <div class="font-medium"><?= wp_trim_words(get_the_title($prev_post), 6) ?></div>
                            </div>
                        </a>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>

                    <?php if ($next_post): ?>
                        <a href="<?= get_permalink($next_post) ?>" class="group flex items-center gap-2 text-blue-600 hover:text-blue-700 transition-colors text-right">
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Next</div>
                                <div class="font-medium"><?= wp_trim_words(get_the_title($next_post), 6) ?></div>
                            </div>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

<?php
endwhile;
get_footer();
?>