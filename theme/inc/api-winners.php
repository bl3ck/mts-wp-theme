<?php
/**
 * Winners REST API Endpoint
 *
 * @package Michael_Taiwo_Scholarship
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the /winners/ endpoint.
 */
function mts_register_winners_endpoint()
{
    register_rest_route('mts/v1', '/winners', [
        'methods'             => 'GET',
        'callback'            => 'mts_get_winners_rest_handler',
        'permission_callback' => '__return_true', // Public endpoint
        'args'                => [
            'page' => [
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ],
            'per_page' => [
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ],
            'country' => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'university' => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'course' => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'search' => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'award_years' => [
                // Can be comma separated or array
                'sanitize_callback' => 'sanitize_text_field', 
            ],
            'graduation_years' => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ],
    ]);
}
add_action('rest_api_init', 'mts_register_winners_endpoint');

/**
 * Handler for the winners endpoint.
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response The response object.
 */
function mts_get_winners_rest_handler($request)
{
    $params = $request->get_params();
    $result = mts_get_winners($params);

    return rest_ensure_response($result);
}
