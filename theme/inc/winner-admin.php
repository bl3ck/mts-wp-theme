<?php
/**
 * Winner post type — admin list screen customizations.
 *
 * Adds custom columns, sortable columns, and filter dropdowns to the
 * Winners edit screen so admins can scan and filter the growing
 * scholar database at a glance.
 *
 * @package Michael_Taiwo_Scholarship
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* -------------------------------------------------------------------------
 * 1. Custom columns
 * ---------------------------------------------------------------------- */

add_filter( 'manage_winner_posts_columns', 'mts_winner_admin_columns' );
function mts_winner_admin_columns( $columns ) {
    // Rebuild from scratch to control the order: checkbox, thumbnail,
    // title, graduate school, status, awarded year, date.
    $new = [];
    if ( isset( $columns['cb'] ) ) {
        $new['cb'] = $columns['cb'];
    }
    $new['mts_thumb']        = __( 'Photo', 'mts' );
    $new['title']            = $columns['title'] ?? __( 'Title' );
    $new['mts_grad_school']  = __( 'Graduate School', 'mts' );
    $new['mts_grad_status']  = __( 'Status', 'mts' );
    $new['mts_awarded_year'] = __( 'Awarded', 'mts' );
    $new['date']             = $columns['date'] ?? __( 'Date' );
    return $new;
}

add_action( 'manage_winner_posts_custom_column', 'mts_winner_admin_column_content', 10, 2 );
function mts_winner_admin_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'mts_thumb':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail(
                    $post_id,
                    [ 48, 48 ],
                    [
                        'style' => 'width:48px;height:48px;object-fit:cover;border-radius:50%;',
                        'alt'   => '',
                    ]
                );
            } else {
                echo '<div style="width:48px;height:48px;border-radius:50%;background:#f0f0f1;display:inline-block;"></div>';
            }
            break;

        case 'mts_grad_school':
            $school = get_post_meta( $post_id, 'grad_school', true );
            if ( $school ) {
                echo esc_html( $school );
                $program = get_post_meta( $post_id, 'grad_program', true );
                if ( $program ) {
                    echo '<br><span style="color:#646970;font-size:12px;">' . esc_html( $program ) . '</span>';
                }
            } else {
                echo '<span style="color:#999;">—</span>';
            }
            break;

        case 'mts_grad_status':
            $status = get_post_meta( $post_id, 'grad_status', true );
            if ( $status ) {
                $labels = function_exists( 'mts_winner_grad_status_choices' )
                    ? mts_winner_grad_status_choices()
                    : [];
                echo esc_html( $labels[ $status ] ?? ucfirst( $status ) );
            } else {
                echo '<span style="color:#999;">—</span>';
            }
            break;

        case 'mts_awarded_year':
            $terms = get_the_terms( $post_id, 'awarded_year' );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
            } else {
                echo '<span style="color:#999;">—</span>';
            }
            break;
    }
}

/* -------------------------------------------------------------------------
 * 2. Sortable columns
 * ---------------------------------------------------------------------- */

add_filter( 'manage_edit-winner_sortable_columns', 'mts_winner_sortable_columns' );
function mts_winner_sortable_columns( $columns ) {
    $columns['mts_grad_school'] = 'grad_school';
    $columns['mts_grad_status'] = 'grad_status';
    return $columns;
}

add_action( 'pre_get_posts', 'mts_winner_handle_orderby' );
function mts_winner_handle_orderby( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || 'edit-winner' !== $screen->id ) {
        return;
    }
    $orderby = $query->get( 'orderby' );
    if ( in_array( $orderby, [ 'grad_school', 'grad_status' ], true ) ) {
        $query->set( 'meta_key', $orderby );
        $query->set( 'orderby', 'meta_value' );
    }
}

/* -------------------------------------------------------------------------
 * 3. Filter dropdowns (restrict_manage_posts)
 * ---------------------------------------------------------------------- */

add_action( 'restrict_manage_posts', 'mts_winner_admin_filters' );
function mts_winner_admin_filters( $post_type ) {
    if ( 'winner' !== $post_type ) {
        return;
    }

    // --- Graduate status (post meta) ---
    $current_status = isset( $_GET['filter_grad_status'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_grad_status'] ) ) : '';
    echo '<select name="filter_grad_status">';
    echo '<option value="">' . esc_html__( 'All statuses', 'mts' ) . '</option>';
    if ( function_exists( 'mts_winner_grad_status_choices' ) ) {
        foreach ( mts_winner_grad_status_choices() as $slug => $label ) {
            if ( '' === $slug ) {
                continue;
            }
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $slug ),
                selected( $current_status, $slug, false ),
                esc_html( $label )
            );
        }
    }
    echo '</select>';

    // --- Taxonomy filters (awarded year, country, university) ---
    foreach ( [ 'awarded_year', 'country', 'university' ] as $tax ) {
        if ( ! taxonomy_exists( $tax ) ) {
            continue;
        }
        $taxonomy = get_taxonomy( $tax );
        $terms    = get_terms( [
            'taxonomy'   => $tax,
            'hide_empty' => false,
            'orderby'    => 'awarded_year' === $tax ? 'name' : 'name',
            'order'      => 'awarded_year' === $tax ? 'DESC' : 'ASC',
        ] );
        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            continue;
        }

        $current = isset( $_GET[ $tax ] ) ? sanitize_text_field( wp_unslash( $_GET[ $tax ] ) ) : '';
        printf( '<select name="%s">', esc_attr( $tax ) );
        printf(
            '<option value="">%s</option>',
            esc_html( sprintf( /* translators: %s: taxonomy plural label */ __( 'All %s', 'mts' ), strtolower( $taxonomy->label ) ) )
        );
        foreach ( $terms as $term ) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $term->slug ),
                selected( $current, $term->slug, false ),
                esc_html( $term->name )
            );
        }
        echo '</select>';
    }
}

add_action( 'pre_get_posts', 'mts_winner_apply_admin_filters' );
function mts_winner_apply_admin_filters( $query ) {
    global $pagenow;
    if ( ! is_admin() || ! $query->is_main_query() || 'edit.php' !== $pagenow ) {
        return;
    }
    if ( ( $_GET['post_type'] ?? '' ) !== 'winner' ) {
        return;
    }

    // Graduate status meta filter
    if ( ! empty( $_GET['filter_grad_status'] ) ) {
        $meta_query   = (array) $query->get( 'meta_query' );
        $meta_query[] = [
            'key'   => 'grad_status',
            'value' => sanitize_text_field( wp_unslash( $_GET['filter_grad_status'] ) ),
        ];
        $query->set( 'meta_query', $meta_query );
    }

    // Taxonomy filters
    $tax_query = (array) $query->get( 'tax_query' );
    foreach ( [ 'awarded_year', 'country', 'university' ] as $tax ) {
        if ( ! empty( $_GET[ $tax ] ) ) {
            $tax_query[] = [
                'taxonomy' => $tax,
                'field'    => 'slug',
                'terms'    => sanitize_text_field( wp_unslash( $_GET[ $tax ] ) ),
            ];
        }
    }
    if ( count( $tax_query ) > 0 ) {
        $query->set( 'tax_query', $tax_query );
    }
}

/* -------------------------------------------------------------------------
 * 4. Thumbnail column width — keep the list compact.
 * ---------------------------------------------------------------------- */

add_action( 'admin_head-edit.php', 'mts_winner_admin_list_styles' );
function mts_winner_admin_list_styles() {
    $screen = get_current_screen();
    if ( ! $screen || 'edit-winner' !== $screen->id ) {
        return;
    }
    ?>
    <style>
        .wp-list-table .column-mts_thumb { width: 64px; text-align: center; }
        .wp-list-table .column-mts_grad_status { width: 110px; }
        .wp-list-table .column-mts_awarded_year { width: 100px; }
    </style>
    <?php
}
