<?php

if (!defined('ABSPATH')) {
    // Exit if accessed directly.
	exit;
}

// add_action('acf/save_post', 'sync_awarded_year_to_meta', 20);
// function sync_awarded_year_to_meta($post_id) {
//     if (get_post_type($post_id) !== 'winner') {
//         return;
//     }

//     $awarded_year = get_field('awarded_year', $post_id);

//     if (is_array($awarded_year)) {
//         $awarded_year = reset($awarded_year);
//     }

//     if ($awarded_year && is_object($awarded_year)) {
//         $year_number = intval($awarded_year->slug); // safer than ->name
//         if ($year_number > 1900) {
//             update_post_meta($post_id, 'awarded_year_number', $year_number);
//         }
//     }
// }

