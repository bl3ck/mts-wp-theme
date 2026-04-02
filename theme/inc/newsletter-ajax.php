<?php
/**
 * Newsletter — AJAX handler, script enqueue, and single-post redirect.
 *
 * @package Michael_Taiwo_Scholarship
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Redirect single newsletter posts to their Brevo link.
 */
function mts_newsletter_redirect() {
    if ( is_singular( 'newsletter' ) ) {
        $link = get_field( 'link' );
        if ( $link ) {
            wp_redirect( esc_url( $link ), 301 );
            exit;
        }
    }
}
add_action( 'template_redirect', 'mts_newsletter_redirect' );

/**
 * AJAX handler for loading newsletter posts.
 */
function mts_load_newsletters_ajax() {
    check_ajax_referer( 'newsletter_archive_nonce', 'nonce' );

    $page     = isset( $_POST['page'] )     ? absint( $_POST['page'] )     : 1;
    $per_page = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 9;
    $offset   = ( $page - 1 ) * $per_page;

    $args = [
        'post_type'      => 'newsletter',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'offset'         => $offset,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    // Total count.
    $count_args = $args;
    unset( $count_args['posts_per_page'], $count_args['offset'] );
    $total_query = new WP_Query( $count_args );
    $total_posts = $total_query->found_posts;

    $query = new WP_Query( $args );
    $posts = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();

            $featured_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            if ( ! $featured_image ) {
                $featured_image = get_template_directory_uri() . '/assets/default-post.jpg';
            }

            $brevo_link = get_field( 'link' );

            $posts[] = [
                'id'             => get_the_ID(),
                'title'          => html_entity_decode( get_the_title(), ENT_QUOTES, 'UTF-8' ),
                'featured_image' => $featured_image,
                'date'           => get_the_date( 'M j, Y' ),
                'excerpt'        => wp_trim_words( get_the_excerpt() ?: get_the_content(), 20, '…' ),
                'link'           => $brevo_link ? esc_url( $brevo_link ) : get_permalink(),
            ];
        }
        wp_reset_postdata();
    }

    $has_more = ( $offset + $per_page ) < $total_posts;

    wp_send_json( [
        'success' => true,
        'data'    => [
            'posts'    => $posts,
            'total'    => $total_posts,
            'has_more' => $has_more,
        ],
    ] );
}

add_action( 'wp_ajax_load_newsletters',        'mts_load_newsletters_ajax' );
add_action( 'wp_ajax_nopriv_load_newsletters', 'mts_load_newsletters_ajax' );

/**
 * Enqueue newsletter archive scripts.
 */
function mts_newsletter_archive_scripts() {
    if ( is_post_type_archive( 'newsletter' ) ) {
        wp_enqueue_script(
            'newsletter-archive',
            get_template_directory_uri() . '/js/newsletter-archive.js',
            [],
            MT_VERSION,
            true
        );
        wp_localize_script( 'newsletter-archive', 'newsletterArchive', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'newsletter_archive_nonce' ),
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'mts_newsletter_archive_scripts' );
