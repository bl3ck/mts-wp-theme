<?php
/**
 * Winners Query Logic
 *
 * @package Michael_Taiwo_Scholarship
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get filtered winners based on parameters.
 *
 * @param array $params Filter parameters.
 * @return array Winners data and pagination info.
 */
function mts_get_winners($params = [])
{
    $page = isset($params['page']) ? intval($params['page']) : 1;
    $per_page = isset($params['per_page']) ? intval($params['per_page']) : 12;
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

    if (!empty($params['country'])) {
        $tax_query[] = [
            'taxonomy' => 'country',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($params['country']),
        ];
    }

    if (!empty($params['university'])) {
        $tax_query[] = [
            'taxonomy' => 'university',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($params['university']),
        ];
    }

    if (!empty($params['course'])) {
        $tax_query[] = [
            'taxonomy' => 'course',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($params['course']),
        ];
    }

    // Awarded Year filter
    if (!empty($params['award_years'])) {
        $award_years = is_array($params['award_years']) ? $params['award_years'] : explode(',', $params['award_years']);
        // Remove empty values
        $award_years = array_filter($award_years);
        if (!empty($award_years)) {
            $tax_query[] = [
                'taxonomy' => 'awarded_year',
                'field'    => 'slug',
                'terms'    => array_map('sanitize_text_field', $award_years),
            ];
        }
    }

    // Graduation Year filter
    if (!empty($params['graduation_years'])) {
        $grad_years = is_array($params['graduation_years']) ? $params['graduation_years'] : explode(',', $params['graduation_years']);
        // Remove empty values
        $grad_years = array_filter($grad_years);
        if (!empty($grad_years)) {
            $tax_query[] = [
                'taxonomy' => 'graduation_year',
                'field'    => 'slug',
                'terms'    => array_map('sanitize_text_field', $grad_years),
            ];
        }
    }

    // Apply taxonomy filters if any exist
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    // Handle search functionality
    if (!empty($params['search'])) {
        $search_term = strtolower(sanitize_text_field($params['search']));
        
        // Get all posts that match taxonomy filters (or all if no taxonomy filters)
        $search_args = $args;
        unset($search_args['posts_per_page'], $search_args['offset']);
        $search_args['posts_per_page'] = -1;
        $search_args['fields'] = 'ids'; // Just get IDs first for performance
        
        // Build initial query to get potential candidates based on tax query
        $potential_posts = get_posts($search_args);
        $matching_post_ids = [];
        
        foreach ($potential_posts as $post_id) {
            $match_found = false;
            $post_title = get_the_title($post_id);
            
            // Check post title
            if (stripos($post_title, $search_term) !== false) {
                $match_found = true;
            }
            
            // Check ACF fields if no title match found yet
            if (!$match_found) {
                // Check country field
                $country_field = get_field('country', $post_id);
                $match_found = check_field_for_search_term($country_field, $search_term);
                
                // Check university field
                if (!$match_found) {
                    $university_field = get_field('university', $post_id);
                    $match_found = check_field_for_search_term($university_field, $search_term);
                }
                
                // Check course field
                if (!$match_found) {
                    $course_field = get_field('course_of_study', $post_id);
                    $match_found = check_field_for_search_term($course_field, $search_term);
                }
            }
            
            if ($match_found) {
                $matching_post_ids[] = $post_id;
            }
        }
        
        // Update args to only include matching posts
        if (!empty($matching_post_ids)) {
            $args['post__in'] = $matching_post_ids;
        } else {
            $args['post__in'] = [0]; // No matches
        }
        unset($args['tax_query']); // Remove tax query since we already filtered candidate posts
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
            $post_id = get_the_ID();

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
            $university_field = get_field('university');
            $university_name = '';
             if (is_array($university_field)) {
                $names = [];
                foreach($university_field as $term) {
                    if (is_numeric($term)) {
                        $t = get_term($term);
                        if (!is_wp_error($t)) $names[] = $t->name;
                    } elseif (is_object($term)) {
                         $names[] = $term->name;
                    }
                }
                $university_name = implode(', ', $names);
            } elseif (is_numeric($university_field)) {
                $term = get_term($university_field);
                $university_name = !is_wp_error($term) ? $term->name : '';
            } elseif (is_object($university_field)) {
                $university_name = $university_field->name;
            }

            // Course
            $course_field = get_field('course_of_study');
            $course_name = '';
            if (is_array($course_field)) {
                $names = [];
               foreach($course_field as $term) {
                    if (is_numeric($term)) {
                        $t = get_term($term);
                        if (!is_wp_error($t)) $names[] = $t->name;
                    } elseif (is_object($term)) {
                         $names[] = $term->name;
                    }
                }
                $course_name = implode(', ', $names);
            } elseif (is_numeric($course_field)) {
                $term = get_term($course_field);
                $course_name = !is_wp_error($term) ? $term->name : '';
            } elseif (is_object($course_field)) {
                $course_name = $course_field->name;
            }

            // Country
            $country_field = get_field('country');
            $country_label = '';
            $country_value = '';

            if (is_array($country_field)) {
                // If ACF returns array of objects or IDs
                foreach($country_field as $c_item) {
                     if (is_numeric($c_item)) {
                        $term = get_term($c_item);
                         if (!is_wp_error($term)) {
                            $country_label = $term->name;
                            $country_value = $term->slug;
                            break; // Just take the first one
                        }
                     } elseif (is_object($c_item)) {
                         $country_label = $c_item->name;
                         $country_value = $c_item->slug;
                         break;
                     }
                }
            } elseif (is_numeric($country_field)) {
                $term = get_term($country_field);
                if (!is_wp_error($term)) {
                    $country_label = $term->name;
                    $country_value = $term->slug;
                }
            } elseif (is_object($country_field)) {
                 $country_label = $country_field->name;
                 $country_value = $country_field->slug;
            }
            
            $winners[] = [
                'id'              => $post_id,
                'title'           => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
                'link'            => get_permalink(),
                'image'           => get_the_post_thumbnail_url($post_id, 'full'),
                'country'         => [
                    'value' => $country_value,
                    'label' => $country_label,
                ],
                'university'      => html_entity_decode($university_name, ENT_QUOTES, 'UTF-8'),
                'course'          => html_entity_decode($course_name, ENT_QUOTES, 'UTF-8'),
                'awarded_year'    => $awarded_year,
                'graduation_year' => $graduation_year,
                'cgpa'            => $cgpa,
                'nationality'     => get_field('nationality'),
            ];
        }
        wp_reset_postdata();
    }

    // Sort winners by awarded year (most recent first) - preserving original logic
    if (!empty($winners)) {
        usort($winners, function($a, $b) {
            $year_a = isset($a['awarded_year']) ? intval($a['awarded_year']) : 0;
            $year_b = isset($b['awarded_year']) ? intval($b['awarded_year']) : 0;
            
            if ($year_a === $year_b) {
                return 0;
            }
            return $year_b - $year_a;
        });
    }

    $has_more = ($offset + $per_page) < $total_winners;

    return [
        'winners'   => $winners,
        'total'     => $total_winners,
        'has_more'  => $has_more,
    ];
}

if (!function_exists('check_field_for_search_term')) {
    /**
     * Helper function to check ACF field for search term
     */
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
}
