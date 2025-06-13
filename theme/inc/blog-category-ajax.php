<?php
// AJAX handler for loading category posts
function load_category_posts_ajax()
{
    check_ajax_referer('category_posts_nonce', 'nonce');

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 9;
    $offset = ($page - 1) * $per_page;

    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'offset'         => $offset,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    // Category filter (required for category archive)
    if (!empty($_POST['category'])) {
        $args['category_name'] = sanitize_text_field($_POST['category']);
    }

    // Total count query
    $count_args = $args;
    unset($count_args['posts_per_page'], $count_args['offset']);
    $total_query = new WP_Query($count_args);
    $total_posts = $total_query->found_posts;

    // Paginated query
    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get author info
            $author_id = get_the_author_meta('ID');
            $author_avatar = get_avatar_url($author_id, ['size' => 96]);
            $author_name = get_the_author();

            $author_image = get_field('profile-image', 'user_' . $author_id);
            $first_name = get_user_meta($author_id, 'first_name', true);
            $last_name = get_user_meta($author_id, 'last_name', true);

            $author_full_name = trim($first_name . ' ' . $last_name);

            // Get categories (excluding uncategorized)
            $post_categories = get_the_category();
            $categories = [];
            if ($post_categories) {
                foreach ($post_categories as $category) {
                    // Skip "Uncategorized" category
                    if (strtolower($category->name) !== 'uncategorized' && $category->slug !== 'uncategorized') {
                        $categories[] = [
                            'id' => $category->term_id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                            'link' => get_category_link($category->term_id)
                        ];
                    }
                }
            }

            // Get excerpt
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(get_the_content(), 20, '...');
            }

            // Get featured image
            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            if (!$featured_image) {
                $featured_image = get_template_directory_uri() . '/assets/default-post.jpg';
            }

            $posts[] = [
                'id'             => get_the_ID(),
                'title'          => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
                'link'           => get_permalink(),
                'excerpt'        => $excerpt,
                'featured_image' => $featured_image,
                'date'           => get_the_date('M j, Y'),
                'author'         => [
                    'id'     => $author_id,
                    'name'   => $author_full_name,
                    'avatar' => $author_image,
                    'link'   => get_author_posts_url($author_id)
                ],
                'categories'     => $categories,
                'comment_count'  => get_comments_number(),
            ];
        }
        wp_reset_postdata();
    }

    $has_more = ($offset + $per_page) < $total_posts;

    wp_send_json([
        'success' => true,
        'data'    => [
            'posts'    => $posts,
            'total'    => $total_posts,
            'has_more' => $has_more,
        ]
    ]);
}

add_action('wp_ajax_load_category_posts', 'load_category_posts_ajax');
add_action('wp_ajax_nopriv_load_category_posts', 'load_category_posts_ajax');

// Enqueue scripts for category archive
function category_archive_scripts()
{
    if (is_category()) {
        // Enqueue the category archive JavaScript file
        wp_enqueue_script(
            'category-archive', 
            get_template_directory_uri() . '/js/category-archive.js', 
            array(), 
            '1.0.0', 
            true
        );
        
        // Localize script with AJAX data
        wp_localize_script('category-archive', 'categoryArchive', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('category_posts_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'category_archive_scripts');
