<?php

if (!defined('ABSPATH')) {
    // Exit if accessed directly.
    exit;
}

// AJAX handler for filtering winners - Final clean version
function filter_winners_ajax()
{
    check_ajax_referer('winners_filter_nonce', 'nonce');

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = 12;
    $offset = ($page - 1) * $per_page;

    $args = [
        'post_type'      => 'winner',
        'posts_per_page' => $per_page,
        'offset'         => $offset,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    // TAX QUERY
    $tax_query = [];

    if (!empty($_POST['country'])) {
        $tax_query[] = [
            'taxonomy' => 'country',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['country']),
        ];
    }

    if (!empty($_POST['university'])) {
        $tax_query[] = [
            'taxonomy' => 'university',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['university']),
        ];
    }

    if (!empty($_POST['course'])) {
        $tax_query[] = [
            'taxonomy' => 'course',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['course']),
        ];
    }

    // Awarded Year filter
    if (!empty($_POST['award_years']) && is_array($_POST['award_years'])) {
        $tax_query[] = [
            'taxonomy' => 'awarded_year',
            'field'    => 'slug',
            'terms'    => array_map('sanitize_text_field', $_POST['award_years']),
        ];
    }

    // Graduation Year filter
    if (!empty($_POST['graduation_years']) && is_array($_POST['graduation_years'])) {
        $tax_query[] = [
            'taxonomy' => 'graduation_year',
            'field'    => 'slug',
            'terms'    => array_map('sanitize_text_field', $_POST['graduation_years']),
        ];
    }

    // Apply taxonomy filters if any exist
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    // Handle search functionality
    if (!empty($_POST['search'])) {
        $search_term = strtolower(sanitize_text_field($_POST['search']));
        
        // Get all posts that match taxonomy filters (or all if no taxonomy filters)
        $search_args = $args;
        unset($search_args['posts_per_page'], $search_args['offset']);
        $search_args['posts_per_page'] = -1;
        
        $all_posts = get_posts($search_args);
        $matching_post_ids = [];
        
        foreach ($all_posts as $post) {
            $match_found = false;
            
            // Check post title
            if (stripos($post->post_title, $search_term) !== false) {
                $match_found = true;
            }
            
            // Check ACF fields if no title match found yet
            if (!$match_found) {
                // Check country field
                $country_field = get_field('country', $post->ID);
                $match_found = check_field_for_search_term($country_field, $search_term);
                
                // Check university field
                if (!$match_found) {
                    $university_field = get_field('university', $post->ID);
                    $match_found = check_field_for_search_term($university_field, $search_term);
                }
                
                // Check course field
                if (!$match_found) {
                    $course_field = get_field('course_of_study', $post->ID);
                    $match_found = check_field_for_search_term($course_field, $search_term);
                }
            }
            
            if ($match_found) {
                $matching_post_ids[] = $post->ID;
            }
        }
        
        // Update args to only include matching posts
        $args['post__in'] = !empty($matching_post_ids) ? $matching_post_ids : [0];
        unset($args['tax_query']); // Remove tax query since we already filtered
    }

    // Total count query
    $count_args = $args;
    unset($count_args['posts_per_page'], $count_args['offset']);
    $total_query = new WP_Query($count_args);
    $total_winners = $total_query->found_posts;

    // Paginated query
    $query = new WP_Query($args);
    $winners = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $awarded_term = get_field('awarded_year');
            $awarded_year = $awarded_term && is_object($awarded_term) ? $awarded_term->name : '';

            $graduation_terms = get_field('graduation_year');
            $graduation_year = '';
            if (is_array($graduation_terms)) {
                $graduation_year = implode(', ', array_map(function($term) {
                    return is_object($term) ? $term->name : '';
                }, $graduation_terms));
            }

            $cgpa = get_field('cgpa');

            // University
            $university = get_field('university');
            if (is_array($university)) {
                $university = implode(', ', array_map(function ($term_id) {
                    $term = get_term($term_id);
                    return !is_wp_error($term) ? $term->name : '';
                }, $university));
            } elseif (is_numeric($university)) {
                $term = get_term($university);
                $university = !is_wp_error($term) ? $term->name : '';
            }

            // Course
            $course = get_field('course_of_study');
            if (is_array($course)) {
                $course = implode(', ', array_map(function ($term_id) {
                    $term = get_term($term_id);
                    return !is_wp_error($term) ? $term->name : '';
                }, $course));
            } elseif (is_numeric($course)) {
                $term = get_term($course);
                $course = !is_wp_error($term) ? $term->name : '';
            }

            // Country
            $country = get_field('country');
            $country_label = '';
            $country_value = '';

            if (is_array($country)) {
                $country_ids = array_filter($country, 'is_numeric');
                if (!empty($country_ids)) {
                    $term = get_term($country_ids[0]);
                    if (!is_wp_error($term)) {
                        $country_label = $term->name;
                        $country_value = $term->slug;
                    }
                }
            } elseif (is_numeric($country)) {
                $term = get_term($country);
                if (!is_wp_error($term)) {
                    $country_label = $term->name;
                    $country_value = $term->slug;
                }
            }

            $university = is_array($university) ? implode(', ', array_map(function ($term) {
                return is_object($term) ? $term->name : $term;
            }, $university)) : $university;

            $course = is_array($course) ? implode(', ', array_map(function ($term) {
                return is_object($term) ? $term->name : $term;
            }, $course)) : $course;

            $winners[] = [
                'id'              => get_the_ID(),
                'title'           => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
                'link'            => get_permalink(),
                'image'           => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                'country'         => [
                    'value' => $country_value,
                    'label' => $country_label,
                ],
                'university'      => html_entity_decode($university, ENT_QUOTES, 'UTF-8'),
                'course'          => html_entity_decode($course, ENT_QUOTES, 'UTF-8'),
                'awarded_year'    => $awarded_year,
                'graduation_year' => $graduation_year,
                'cgpa'            => $cgpa,
                'nationality'     => get_field('nationality'),
            ];
        }
        wp_reset_postdata();
    }

    // Sort winners by awarded year (most recent first)
    if (!empty($winners)) {
        usort($winners, function($a, $b) {
            $year_a = intval($a['awarded_year']);
            $year_b = intval($b['awarded_year']);
            
            // If years are the same or invalid, maintain original order
            if ($year_a === $year_b) {
                return 0;
            }
            
            // Sort in descending order (newest first)
            return $year_b - $year_a;
        });
    }

    $has_more = ($offset + $per_page) < $total_winners;

    wp_send_json([
        'success' => true,
        'data'    => [
            'winners'   => $winners,
            'total'     => $total_winners,
            'has_more'  => $has_more,
        ]
    ]);
}

// Helper function to check ACF field for search term
function check_field_for_search_term($field, $search_term) {
    if (!$field) return false;
    
    if (is_array($field)) {
        foreach ($field as $item) {
            if (is_numeric($item)) {
                $term = get_term($item);
                if ($term && stripos($term->name, $search_term) !== false) {
                    return true;
                }
            } elseif (is_object($item) && isset($item->name)) {
                if (stripos($item->name, $search_term) !== false) {
                    return true;
                }
            }
        }
    } elseif (is_object($field) && isset($field->name)) {
        return stripos($field->name, $search_term) !== false;
    } elseif (is_numeric($field)) {
        $term = get_term($field);
        return $term && stripos($term->name, $search_term) !== false;
    } elseif (is_string($field)) {
        return stripos($field, $search_term) !== false;
    }
    
    return false;
}

add_action('wp_ajax_filter_winners', 'filter_winners_ajax');
add_action('wp_ajax_nopriv_filter_winners', 'filter_winners_ajax');

// Enqueue scripts
function winners_archive_scripts()
{
    if (is_post_type_archive('winner')) {

        // Your custom script with localized AJAX URL
        wp_enqueue_script('winners-archive', get_template_directory_uri() . '/js/winners-archive.js', array(), '1.0.0', true);
        wp_localize_script('winners-archive', 'winnersArchive', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('winners_filter_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'winners_archive_scripts');
