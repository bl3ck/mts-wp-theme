<?php
/**
 * Winners — AJAX handler, script enqueue, and dependency loading.
 *
 * Query logic lives in winners-logic.php.
 * REST API endpoint lives in api-winners.php.
 *
 * @package Michael_Taiwo_Scholarship
 */

if (!defined('ABSPATH')) {
    exit;
}

// Shared query logic (mts_get_winners + check_field_for_search_term)
require_once get_template_directory() . '/inc/winners-logic.php';

// REST API endpoint (/wp-json/mts/v1/winners)
require_once get_template_directory() . '/inc/api-winners.php';

/**
 * AJAX handler for filtering winners (used by the archive page Alpine component).
 */
function filter_winners_ajax()
{
    check_ajax_referer('winners_filter_nonce', 'nonce');

    $params = [
        'page'             => isset($_POST['page']) ? intval($_POST['page']) : 1,
        'per_page'         => isset($_POST['per_page']) ? intval($_POST['per_page']) : 12,
        'country'          => isset($_POST['country']) ? $_POST['country'] : '',
        'university'       => isset($_POST['university']) ? $_POST['university'] : '',
        'course'           => isset($_POST['course']) ? $_POST['course'] : '',
        'search'           => isset($_POST['search']) ? $_POST['search'] : '',
        'award_years'      => isset($_POST['award_years']) ? $_POST['award_years'] : [],
        'graduation_years' => isset($_POST['graduation_years']) ? $_POST['graduation_years'] : [],
    ];

    $result = mts_get_winners($params);

    wp_send_json([
        'success' => true,
        'data'    => $result,
    ]);
}

add_action('wp_ajax_filter_winners', 'filter_winners_ajax');
add_action('wp_ajax_nopriv_filter_winners', 'filter_winners_ajax');

/**
 * Enqueue winners-archive scripts when viewing the winner archive.
 */
function winners_archive_scripts()
{
    if (is_post_type_archive('winner')) {
        wp_enqueue_script('winners-archive', get_template_directory_uri() . '/js/winners-archive.js', [], '1.0.0', true);
        wp_localize_script('winners-archive', 'winnersArchive', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('winners_filter_nonce'),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'winners_archive_scripts');
