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
    $new['mts_location']     = __( 'Enrollment Location', 'mts' );
    $new['mts_mentor']       = __( 'Mentor', 'mts' );
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

        case 'mts_location':
            $location = array_filter( [
                get_post_meta( $post_id, 'current_location', true ),
                get_post_meta( $post_id, 'current_country', true ),
            ] );
            echo esc_html( implode( ', ', $location ) );
            break;

        case 'mts_mentor':
            $mentor_id = absint( get_post_meta( $post_id, 'mentor_id', true ) );
            if ( $mentor_id && 'mentor' === get_post_type( $mentor_id ) ) {
                echo esc_html( get_the_title( $mentor_id ) );
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

add_action( 'restrict_manage_posts', 'mts_winner_admin_filters', 10, 2 );
function mts_winner_admin_filters( $post_type, $which = 'top' ) {
    if ( 'winner' !== $post_type || 'top' !== $which ) {
        return;
    }

    // --- Graduate status (post meta) ---
    $request = mts_winner_filter_request( $_GET );
    $current_status = $request['filter_grad_status'] ?? '';
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

    foreach ( mts_winner_meta_filter_labels() as $meta_key => $label ) {
        $request_key = 'filter_' . $meta_key;
        printf( '<select name="%s" aria-label="%s">', esc_attr( $request_key ), esc_attr( $label ) );
        printf( '<option value="">%s</option>', esc_html( $label ) );
        foreach ( mts_winner_meta_filter_values( $meta_key ) as $value ) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $value ),
                selected( $request[ $request_key ] ?? '', $value, false ),
                esc_html( $value )
            );
        }
        echo '</select>';
    }

    echo '<select name="filter_mentor_id" aria-label="' . esc_attr__( 'Assigned Mentor', 'mts' ) . '">';
    echo '<option value="">' . esc_html__( 'All mentors', 'mts' ) . '</option>';
    foreach ( get_posts( [ 'post_type' => 'mentor', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ] ) as $mentor ) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr( $mentor->ID ),
            selected( $request['filter_mentor_id'] ?? '', $mentor->ID, false ),
            esc_html( $mentor->post_title )
        );
    }
    echo '</select>';

    // --- Taxonomy filters (awarded year, country, university) ---
    foreach ( [ 'awarded_year', 'country', 'university', 'winner_scholarship' ] as $tax ) {
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

        $current = $request[ $tax ] ?? '';
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

    if ( mts_winner_can_export() ) {
        wp_nonce_field( 'mts_export_winners', 'mts_export_nonce', false );
        echo '<button type="submit" name="mts_export_winners" value="1" class="button">' . esc_html__( 'Export filtered winners (CSV)', 'mts' ) . '</button>';
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

    $args = mts_winner_filtered_query_args( mts_winner_filter_request( $_GET ) );
    foreach ( [ 'meta_query', 'tax_query' ] as $query_key ) {
        if ( ! empty( $args[ $query_key ] ) ) {
            $existing = (array) $query->get( $query_key );
            $query->set( $query_key, $existing ? [ 'relation' => 'AND', $existing, $args[ $query_key ] ] : $args[ $query_key ] );
        }
    }
}

function mts_winner_meta_filter_labels() {
    return [
        'grad_school'      => __( 'All graduate schools', 'mts' ),
        'grad_program'     => __( 'All graduate programs / degrees', 'mts' ),
        'current_location' => __( 'All enrollment cities / regions', 'mts' ),
        'current_country'  => __( 'All enrollment countries', 'mts' ),
    ];
}

function mts_winner_meta_filter_values( $meta_key ) {
    global $wpdb;
    if ( ! array_key_exists( $meta_key, mts_winner_meta_filter_labels() ) ) {
        return [];
    }
    return $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE p.post_type = %s AND p.post_status NOT IN ('trash', 'auto-draft')
        AND pm.meta_key = %s AND pm.meta_value <> '' ORDER BY pm.meta_value ASC",
        'winner',
        $meta_key
    ) );
}

function mts_winner_filter_request( $source ) {
    $keys = [ 'filter_grad_status', 'filter_mentor_id', 'awarded_year', 'country', 'university', 'winner_scholarship', 's', 'post_status', 'm', 'author', 'orderby', 'order' ];
    foreach ( array_keys( mts_winner_meta_filter_labels() ) as $meta_key ) {
        $keys[] = 'filter_' . $meta_key;
    }
    $request = [];
    foreach ( $keys as $key ) {
        if ( isset( $source[ $key ] ) && is_string( $source[ $key ] ) ) {
            $request[ $key ] = sanitize_text_field( wp_unslash( $source[ $key ] ) );
        }
    }
    return $request;
}

function mts_winner_filtered_query_args( $request ) {
    $args = [ 'post_type' => 'winner' ];
    $meta_query = [ 'relation' => 'AND' ];
    foreach ( array_merge( [ 'grad_status', 'mentor_id' ], array_keys( mts_winner_meta_filter_labels() ) ) as $meta_key ) {
        $value = $request[ 'filter_' . $meta_key ] ?? '';
        if ( '' !== $value ) {
            $meta_query[] = [ 'key' => $meta_key, 'value' => $value, 'compare' => '=' ];
        }
    }
    if ( count( $meta_query ) > 1 ) {
        $args['meta_query'] = $meta_query;
    }
    $tax_query = [ 'relation' => 'AND' ];
    foreach ( [ 'awarded_year', 'country', 'university', 'winner_scholarship' ] as $taxonomy ) {
        if ( ! empty( $request[ $taxonomy ] ) && taxonomy_exists( $taxonomy ) ) {
            $tax_query[] = [ 'taxonomy' => $taxonomy, 'field' => 'slug', 'terms' => $request[ $taxonomy ] ];
        }
    }
    if ( count( $tax_query ) > 1 ) {
        $args['tax_query'] = $tax_query;
    }
    foreach ( [ 's', 'author' ] as $key ) {
        if ( ! empty( $request[ $key ] ) ) {
            $args[ $key ] = 'author' === $key ? absint( $request[ $key ] ) : $request[ $key ];
        }
    }
    $args['post_status'] = [ 'publish', 'future', 'draft', 'pending', 'private' ];
    if ( in_array( $request['post_status'] ?? '', [ 'publish', 'future', 'draft', 'pending', 'private', 'trash' ], true ) ) {
        $args['post_status'] = $request['post_status'];
    }
    if ( preg_match( '/^\d{6}$/', $request['m'] ?? '' ) ) {
        $args['m'] = $request['m'];
    }
    $orderby = $request['orderby'] ?? 'date';
    $args['orderby'] = in_array( $orderby, [ 'date', 'title', 'ID', 'modified' ], true ) ? $orderby : 'date';
    if ( in_array( $orderby, [ 'grad_school', 'grad_status' ], true ) ) {
        $args['meta_key'] = $orderby;
        $args['orderby'] = 'meta_value';
    }
    $args['order'] = 'ASC' === strtoupper( $request['order'] ?? 'DESC' ) ? 'ASC' : 'DESC';
    return $args;
}

function mts_winner_can_export() {
    $post_type = get_post_type_object( 'winner' );
    return $post_type && current_user_can( 'manage_options' ) && current_user_can( $post_type->cap->edit_posts );
}

function mts_winner_csv_cell( $value ) {
    $value = is_scalar( $value ) ? (string) $value : '';
    if ( preg_match( '/^[\s\x{FEFF}]*[=+@-]/u', $value ) || preg_match( '/^[\t\r\n]/', $value ) ) {
        return "'" . $value;
    }
    return $value;
}

function mts_winner_export_columns() {
    return [
        'id'               => 'Winner ID',
        'name'             => 'Name',
        'post_status'      => 'WordPress Post Status',
        'grad_school'      => 'Current Graduate School',
        'grad_program'     => 'Graduate Program / Degree',
        'grad_status'      => 'Graduate Status',
        'grad_start_year'  => 'Start Year',
        'grad_expected_end' => 'Expected Graduation Year',
        'current_location' => 'Enrollment City / Region',
        'current_country'  => 'Enrollment Country',
        'mentor'           => 'Assigned Mentor',
        'country'          => 'Country of Origin',
        'university'       => 'University',
        'awarded_year'     => 'Awarded Year',
        'contact_email'    => 'Contact Email',
        'linkedin_url'     => 'LinkedIn URL',
        'private_notes'    => 'Private Notes',
        'winner_scholarship' => 'Scholarships Awarded',
    ];
}

function mts_winner_export_row( $post_id ) {
    $row = [];
    foreach ( array_keys( mts_winner_export_columns() ) as $key ) {
        $row[ $key ] = get_post_meta( $post_id, $key, true );
    }
    $row['id'] = $post_id;
    $row['name'] = get_the_title( $post_id );
    $row['post_status'] = get_post_status( $post_id );
    $statuses = mts_winner_grad_status_choices();
    $row['grad_status'] = $row['grad_status'] ? ( $statuses[ $row['grad_status'] ] ?? $row['grad_status'] ) : '';
    $mentor_id = absint( get_post_meta( $post_id, 'mentor_id', true ) );
    $row['mentor'] = $mentor_id && 'mentor' === get_post_type( $mentor_id ) ? get_the_title( $mentor_id ) : '';
    foreach ( [ 'country', 'university', 'awarded_year', 'winner_scholarship' ] as $taxonomy ) {
        $terms = get_the_terms( $post_id, $taxonomy );
        $row[ $taxonomy ] = ! is_wp_error( $terms ) && $terms ? implode( ', ', wp_list_pluck( $terms, 'name' ) ) : '';
    }
    return array_map( 'mts_winner_csv_cell', array_values( $row ) );
}

function mts_winner_export_rows( $args ) {
    $args['posts_per_page'] = 200;
    $args['paged'] = 1;
    $args['fields'] = 'ids';
    $args['no_found_rows'] = true;
    $args['ignore_sticky_posts'] = true;
    $order = $args['order'] ?? 'DESC';
    $args['orderby'] = [ ( $args['orderby'] ?? 'date' ) => $order, 'ID' => $order ];
    do {
        $query = new WP_Query( $args );
        _prime_post_caches( $query->posts, true, true );
        foreach ( $query->posts as $post_id ) {
            if ( current_user_can( 'edit_post', $post_id ) ) {
                yield mts_winner_export_row( $post_id );
            }
        }
        ++$args['paged'];
    } while ( count( $query->posts ) === $args['posts_per_page'] );
}

add_action( 'load-edit.php', 'mts_winner_export_filtered_csv' );
function mts_winner_export_filtered_csv() {
    if ( ( $_GET['post_type'] ?? '' ) !== 'winner' || ! isset( $_GET['mts_export_winners'] ) ) {
        return;
    }
    if ( ! mts_winner_can_export() ) {
        wp_die( esc_html__( 'You do not have permission to export winner records.', 'mts' ), '', [ 'response' => 403 ] );
    }
    check_admin_referer( 'mts_export_winners', 'mts_export_nonce' );
    $args = mts_winner_filtered_query_args( mts_winner_filter_request( $_GET ) );
    $stream = fopen( 'php://output', 'w' );
    if ( false === $stream ) {
        wp_die( esc_html__( 'Unable to create the CSV export.', 'mts' ) );
    }
    nocache_headers();
    header( 'Content-Type: text/csv; charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename="winners-' . gmdate( 'Y-m-d-His' ) . '.csv"' );
    header( 'X-Content-Type-Options: nosniff' );
    fwrite( $stream, "\xEF\xBB\xBF" );
    fputcsv( $stream, array_values( mts_winner_export_columns() ), ',', '"', '' );
    foreach ( mts_winner_export_rows( $args ) as $row ) {
        fputcsv( $stream, $row, ',', '"', '' );
    }
    fclose( $stream );
    exit;
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
        .tablenav.top { height: auto; min-height: 30px; }
        .tablenav.top .actions { display: flex; flex-wrap: wrap; gap: 6px; max-width: 100%; margin-bottom: 6px; }
        .tablenav.top .actions select { float: none; margin: 0; max-width: 230px; }
        .wp-list-table .column-mts_thumb { width: 64px; text-align: center; }
        .wp-list-table .column-mts_grad_status { width: 110px; }
        .wp-list-table .column-mts_awarded_year { width: 100px; }
    </style>
    <?php
}
