<?php

if (!defined('ABSPATH')) {
    // Exit if accessed directly.
	exit;
}


// MTS Winners
function create_mt_winner_type() {
    $labels = array(
        'name'               => 'Winners',
        'singular_name'      => 'Winner',
        'menu_name'          => 'Winners',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Winner',
        'edit'               => 'Edit',
        'edit_item'          => 'Edit Winner',
        'new_item'           => 'New Winner',
        'view'               => 'View',
        'view_item'          => 'View Winner',
        'search_items'       => 'Search Winners',
        'not_found'          => 'No Winners found',
        'not_found_in_trash' => 'No Winners found in trash',
        'parent'             => 'Parent Winner'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'winners' ),
        'menu_icon'          => 'dashicons-awards',
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( 'post_tag' )
    );
    register_post_type( 'winner', $args );

    // Register REST API endpoints
    add_action('rest_api_init', function() {
        register_rest_route('winners/v1', '/filter-options', [
            'methods' => 'GET',
            'callback' => '_tw_get_winner_filter_options',
            'permission_callback' => '__return_true'
        ]);
        
        register_rest_route('winners/v1', '/winners', [
            'methods' => 'GET',
            'callback' => '_tw_get_filtered_winners',
            'permission_callback' => '__return_true'
        ]);
    });

    // Enqueue scripts
    add_action('wp_enqueue_scripts', function() {
        if (is_page_template('winner-archive.php')) {
            wp_enqueue_script(
                '_tw-winners-archive',
                get_template_directory_uri() . '/js/winners-archive.js',
                ['alpinejs'],
                filemtime(get_template_directory() . '/js/winners-archive.js'),
                true
            );

            // Localize strings
            wp_localize_script('_tw-winners-archive', 'winnersArchiveI18n', [
                'showingText' => __('Showing', '_tw'),
                'ofText' => __('of', '_tw'),
                'winnersText' => __('winners', '_tw'),
                'loadingText' => __('Loading...', '_tw')
            ]);
        }
    });
}

add_action( 'init', 'create_mt_winner_type' );


// Stories of Impact
function create_mt_impact_stories_type() {
    $labels = array(
        'name'               => 'Impact Stories',
        'singular_name'      => 'Impact Story',
        'menu_name'          => 'Impact Stories',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Impact Story',
        'edit'               => 'Edit',
        'edit_item'          => 'Edit Impact Story',
        'new_item'           => 'New Impact Story',
        'view'               => 'View',
        'view_item'          => 'View Impact Story',
        'search_items'       => 'Search Impact Stories',
        'not_found'          => 'No Impact Stories found',
        'not_found_in_trash' => 'No Impact Stories found in trash',
        'parent'             => 'Parent Impact Story'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'impact-stories' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( 'post_tag' )
    );
    register_post_type( 'impact-story', $args );
}

add_action( 'init', 'create_mt_impact_stories_type' );



// Team - members
function create_mt_team_type() {
    $labels = array(
        'name'               => 'Team Members',
        'singular_name'      => 'Team Member',
        'menu_name'          => 'Team Members',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Team Member',
        'edit'               => 'Edit',
        'edit_item'          => 'Edit Team Member',
        'new_item'           => 'New Team Member',
        'view'               => 'View',
        'view_item'          => 'View Team Member',
        'search_items'       => 'Search Team Members',
        'not_found'          => 'No Team Members found',
        'not_found_in_trash' => 'No Team Members found in trash',
        'parent'             => 'Parent Team Member'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'team-members' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( 'post_tag' )
    );
    register_post_type( 'team-member', $args );
}

add_action( 'init', 'create_mt_team_type' );

// Team - board members
function create_mt_board_member_type() {
    $labels = array(
        'name'               => 'Board Members',
        'singular_name'      => 'Board Member',
        'menu_name'          => 'Board Members',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Board Member',
        'edit'               => 'Edit',
        'edit_item'          => 'Edit Board Member',
        'new_item'           => 'New Board Member',
        'view'               => 'View',
        'view_item'          => 'View Board Member',
        'search_items'       => 'Search Board Members',
        'not_found'          => 'No Board Members found',
        'not_found_in_trash' => 'No Board Members found in trash',
        'parent'             => 'Parent Board Member'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'board-members' ),
        'menu_icon'          => 'dashicons-profile',
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( 'post_tag' )
    );
    register_post_type( 'board-member', $args );
}

add_action( 'init', 'create_mt_board_member_type' );

add_post_type_support( 'page', 'excerpt' );

// MTS Reports
function create_mt_reports_type() {
    $labels = array(
        'name'               => 'Reports',
        'singular_name'      => 'Report',
        'menu_name'          => 'Reports',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Report',
        'edit'               => 'Edit',
        'edit_item'          => 'Edit Report',
        'new_item'           => 'New Report',
        'view'               => 'View',
        'view_item'          => 'View Report',
        'search_items'       => 'Search Reports',
        'not_found'          => 'No Reports found',
        'not_found_in_trash' => 'No Reports found in trash',
        'parent'             => 'Parent Report'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'report' ),
        'menu_icon'          => 'dashicons-media-document',
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array( 'post_tag' )
    );
    register_post_type( 'report', $args );

    // Register REST API endpoints
    add_action('rest_api_init', function() {
        register_rest_route('reports/v1', '/filter-options', [
            'methods' => 'GET',
            'callback' => '_tw_get_winner_filter_options',
            'permission_callback' => '__return_true'
        ]);
        
        register_rest_route('reports/v1', '/reports', [
            'methods' => 'GET',
            'callback' => '_tw_get_filtered_reports',
            'permission_callback' => '__return_true'
        ]);
    });

    // Enqueue scripts
    add_action('wp_enqueue_scripts', function() {
        if (is_page_template('report-archive.php')) {
            wp_enqueue_script(
                '_tw-reports-archive',
                get_template_directory_uri() . '/js/reports-archive.js',
                ['alpinejs'],
                filemtime(get_template_directory() . '/js/reports-archive.js'),
                true
            );

            // Localize strings
            wp_localize_script('_tw-reports-archive', 'reportsArchiveI18n', [
                'showingText' => __('Showing', '_tw'),
                'ofText' => __('of', '_tw'),
                'reportsText' => __('reports', '_tw'),
                'loadingText' => __('Loading...', '_tw')
            ]);
        }
    });
}

add_action( 'init', 'create_mt_reports_type' );

// Register 'Course' taxonomy
function register_course_taxonomy() {
    register_taxonomy('course', 'winner', [
        'label' => 'Courses',
        'hierarchical' => false, // false = like tags, true = like categories
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true, // Needed for Gutenberg + ACF
        'rewrite' => ['slug' => 'course'],
    ]);
}
add_action('init', 'register_course_taxonomy');

// Register 'University' taxonomy
function register_university_taxonomy() {
    register_taxonomy('university', 'winner', [
        'label' => 'Universities',
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'university'],
    ]);
}
add_action('init', 'register_university_taxonomy');

// Register 'Country' taxonomy
function register_country_taxonomy() {
    register_taxonomy('country', 'winner', [
        'label' => 'Countries',
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'country'],
    ]);
}
add_action('init', 'register_country_taxonomy');

// Register 'Grad Year' taxonomy
function register_graduation_year_taxonomy() {
    register_taxonomy('graduation_year', 'winner', [
        'label' => 'Graduation Year',
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'graduation_year'],
    ]);
}
add_action('init', 'register_graduation_year_taxonomy');

// Register 'Award Year' taxonomy
function register_awarded_year_taxonomy() {
    register_taxonomy('awarded_year', 'winner', [
        'label' => 'Awarded Year',
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'awarded_year'],
    ]);
}
add_action('init', 'register_awarded_year_taxonomy');

// Partners
function create_mt_partners_type() {
    $labels = array(
        'name'               => 'Partners',
        'singular_name'      => 'Partner',
        'menu_name'          => 'Partners',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Partner',
        'edit'               => 'Edit',
        'edit_item'          => 'Edit Partner',
        'new_item'           => 'New Partner',
        'view'               => 'View',
        'view_item'          => 'View Partner',
        'search_items'       => 'Search Partners',
        'not_found'          => 'No Partners found',
        'not_found_in_trash' => 'No Partners found in trash',
        'parent'             => 'Parent Partner'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'partner' ),
        'menu_icon'          => 'dashicons-profile',
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array()
    );
    register_post_type( 'partner', $args );
}

add_action( 'init', 'create_mt_partners_type' );
