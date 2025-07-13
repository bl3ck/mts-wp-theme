<?php

if (!defined('ABSPATH')) {
    // Exit if accessed directly.
    exit;
}

/**
 * Theme Setup Functions
 *
 * This file contains functions for:
 * 1. Creating navigation menu and pages on theme activation
 * 2. Adding theme logo support to the customizer
 */
/**
 * Set up initial menus and pages when theme is activated
 * 
 * @return void
 */
// Declare menu location
function theme_register_menus()
{
    register_nav_menus(array(
        'primary' => __('Primary Menu'),
    ));
}
add_action('after_setup_theme', 'theme_register_menus');


// Setup pages and menu
function mytheme_create_pages_on_activation() {
    // Only run once
    if (get_option('mytheme_pages_created') === 'yes') {
        return;
    }

    $pages_to_create = [
        'Home',
        'About',
        'Blog',
        'Apply',
        '1% Challenge',
        'Eligibility',
        'Timeline',
        'How to apply',
        'Volunteer',
        'How we select',
        'The Dream Lounge',
        'Scholar Network',
        'Contact Us',
        'Support'
    ];

    foreach ($pages_to_create as $page_title) {
        $slug = sanitize_title($page_title);

        // Use get_page_by_path instead of WP_Query (faster & more direct)
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_title'   => $page_title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);
        }
    }

    update_option('mytheme_pages_created', 'yes');
}
add_action('after_switch_theme', 'mytheme_create_pages_on_activation');


// delete_option('theme_pages_setup_done');

/**
 * Add theme logo support to the customizer
 * 
 * @return void
 */
function theme_add_custom_logo_support()
{
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    ));
}
add_action('after_setup_theme', 'theme_add_custom_logo_support');

/**
 * Register footer menus
 * 
 * Note: Instead of using theme locations, we're setting up specific named menus
 * that will be fetched directly via wp_get_nav_menu_items()
 * 
 * @return void
 */
function theme_register_footer_menus()
{
    // Check if the menus already exist
    $footer_1 = wp_get_nav_menu_object('footer-1');
    $footer_2 = wp_get_nav_menu_object('footer-2');
    $footer_3 = wp_get_nav_menu_object('footer-3');
    $footer_privacy = wp_get_nav_menu_object('footer-privacy');

    // Create the menus if they don't exist
    if (!$footer_1) {
        wp_create_nav_menu('footer-1');
    }

    if (!$footer_2) {
        wp_create_nav_menu('footer-2');
    }

    if (!$footer_3) {
        wp_create_nav_menu('footer-3');
    }

    if (!$footer_privacy) {
        wp_create_nav_menu('footer-privacy');
    }
}
add_action('after_setup_theme', 'theme_register_footer_menus');

function get_page_id_by_title($title)
{
    $query = new WP_Query(array(
        'post_type'              => 'page',
        'title'                  => $title,
        'posts_per_page'         => 1,
        'post_status'            => 'any',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'orderby'                => 'post_date ID',
        'order'                  => 'ASC',
    ));

    if ($query->have_posts()) {
        $query->the_post();
        $page_id = get_the_ID();
        wp_reset_postdata();
        return $page_id;
    }

    return false;
}

function get_page_url_by_title($title)
{
    $page_id = get_page_id_by_title($title);
    return $page_id ? get_permalink($page_id) : false;
}
