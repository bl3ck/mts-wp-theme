<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'add_meta_boxes_mentor', 'mts_mentor_register_winners_box' );
function mts_mentor_register_winners_box() {
    add_meta_box( 'mts_mentor_winners', __( 'Assigned Winners', 'mts' ), 'mts_mentor_render_winners_box', 'mentor', 'normal', 'default' );
}

function mts_mentor_winner_query_args( $mentor_id ) {
    $args = mts_winner_filtered_query_args( [ 'filter_mentor_id' => (string) absint( $mentor_id ) ] );
    $args['posts_per_page'] = 20;
    $args['fields'] = 'ids';
    $args['orderby'] = [ 'title' => 'ASC', 'ID' => 'ASC' ];
    return $args;
}

function mts_mentor_render_winners_box( $mentor ) {
    $winner_type = get_post_type_object( 'winner' );
    if ( ! $winner_type || ! current_user_can( 'edit_post', $mentor->ID ) || ! current_user_can( $winner_type->cap->edit_posts ) ) {
        return;
    }

    $query = new WP_Query( mts_mentor_winner_query_args( $mentor->ID ) );
    _prime_post_caches( $query->posts, true, true );
    $winner_ids = array_filter( $query->posts, function ( $post_id ) {
        return current_user_can( 'edit_post', $post_id );
    } );

    if ( $winner_ids ) {
        echo '<div style="overflow-x:auto"><table class="widefat striped"><thead><tr>';
        foreach ( [ 'Winner', 'Graduate School', 'Enrollment Location', 'Status' ] as $label ) {
            echo '<th scope="col">' . esc_html( $label ) . '</th>';
        }
        echo '</tr></thead><tbody>';
        $statuses = mts_winner_grad_status_choices();
        foreach ( $winner_ids as $post_id ) {
            $location = implode( ', ', array_filter( [
                get_post_meta( $post_id, 'current_location', true ),
                get_post_meta( $post_id, 'current_country', true ),
            ] ) );
            $status = get_post_meta( $post_id, 'grad_status', true );
            printf(
                '<tr><td><a href="%s">%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>',
                esc_url( get_edit_post_link( $post_id, 'raw' ) ),
                esc_html( get_the_title( $post_id ) ),
                esc_html( get_post_meta( $post_id, 'grad_school', true ) ),
                esc_html( $location ),
                esc_html( $status ? ( $statuses[ $status ] ?? $status ) : '' )
            );
        }
        echo '</tbody></table></div>';
    } else {
        echo '<p>' . esc_html__( 'No accessible winners assigned.', 'mts' ) . '</p>';
    }

    $url = add_query_arg( [ 'post_type' => 'winner', 'filter_mentor_id' => $mentor->ID ], admin_url( 'edit.php' ) );
    echo '<p><a class="button" href="' . esc_url( $url ) . '">' . esc_html__( 'View all assigned winners', 'mts' ) . '</a></p>';
}