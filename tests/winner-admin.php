<?php

define( 'ABSPATH', __DIR__ );

function add_action( ...$args ) {}
function add_filter( ...$args ) {}
function add_post_type_support( ...$args ) {}
function __( $text, ...$args ) { return $text; }
function esc_html__( $text, ...$args ) { return $text; }
function esc_html( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function admin_url( $path ) { return '/wp-admin/' . $path; }
function add_query_arg( $args, $url ) { return $url . '?' . http_build_query( $args ); }
function get_edit_post_link( $post_id, ...$args ) { return '/wp-admin/post.php?post=' . $post_id . '&action=edit'; }
function taxonomy_exists( $taxonomy ) { return true; }
function sanitize_text_field( $value ) { return trim( strip_tags( $value ) ); }
function wp_unslash( $value ) { return stripslashes( $value ); }
function absint( $value ) { return abs( (int) $value ); }
function is_admin() { return true; }
function get_post_type_object( $type ) { return (object) [ 'cap' => (object) [ 'edit_posts' => 'edit_posts' ] ]; }

$can_export = true;
$nonce_valid = false;
$field_groups = [];
$post_types = [];
$taxonomies = [];
$meta_boxes = [];
$primed_batches = [];

function _prime_post_caches( $post_ids, $update_term_cache, $update_meta_cache ) {
    global $primed_batches;
    $primed_batches[] = $post_ids;
}

function current_user_can( $capability, $post_id = null ) {
    global $can_export;
    return 'edit_post' === $capability ? 2 !== $post_id : $can_export;
}
function wp_die( $message, ...$args ) { throw new RuntimeException( $message ); }
function check_admin_referer( ...$args ) {
    global $nonce_valid;
    if ( ! $nonce_valid ) {
        throw new RuntimeException( 'Invalid nonce' );
    }
}
function acf_add_local_field_group( $group ) {
    global $field_groups;
    $field_groups[ $group['key'] ] = $group;
}
function register_post_type( $type, $args ) {
    global $post_types;
    $post_types[ $type ] = $args;
}
function register_taxonomy( $taxonomy, $types, $args ) {
    global $taxonomies;
    $taxonomies[ $taxonomy ] = [ 'types' => $types, 'args' => $args ];
}
function add_meta_box( $id, $title, $callback, $screen, ...$args ) {
    global $meta_boxes;
    $meta_boxes[ $id ] = [ 'callback' => $callback, 'screen' => $screen ];
}
function get_post_meta( $post_id, $key, $single ) {
    return [ 'grad_status' => 'enrolled', 'mentor_id' => '42', 'private_notes' => '=HYPERLINK("bad")' ][ $key ] ?? '';
}
function get_the_title( $post_id ) { return 42 === $post_id ? 'Mentor Example' : 'Winner ' . $post_id; }
function get_post_status( $post_id ) { return 'publish'; }
function get_post_type( $post_id ) { return 'mentor'; }
function get_the_terms( $post_id, $taxonomy ) {
    if ( 'winner_scholarship' === $taxonomy ) {
        return [ (object) [ 'name' => 'Mastercard Foundation' ], (object) [ 'name' => 'Erasmus Mundus' ] ];
    }
    return [ (object) [ 'name' => 'country' === $taxonomy ? 'Nigeria' : '2025' ] ];
}
function is_wp_error( $value ) { return false; }
function wp_list_pluck( $values, $field ) {
    return array_map( function ( $value ) use ( $field ) { return $value->$field; }, $values );
}

class WP_Query {
    public $posts;
    public $args;
    public $main = false;
    public static $queries = [];
    public static $fixture_posts = null;

    public function __construct( $args ) {
        $this->args = $args;
        self::$queries[] = $args;
        $this->posts = self::$fixture_posts ?? ( isset( $args['paged'] ) ? array_slice( range( 1, 205 ), ( $args['paged'] - 1 ) * $args['posts_per_page'], $args['posts_per_page'] ) : [] );
    }
    public function is_main_query() { return $this->main; }
    public function get( $key ) { return $this->args[ $key ] ?? null; }
    public function set( $key, $value ) { $this->args[ $key ] = $value; }
}

require __DIR__ . '/../theme/inc/post-types.php';
require __DIR__ . '/../theme/inc/winner-fields.php';
require __DIR__ . '/../theme/inc/winner-admin.php';
require __DIR__ . '/../theme/inc/mentor-admin.php';

function mts_test_expect( $condition, $label ) {
    if ( ! $condition ) {
        throw new RuntimeException( $label );
    }
    echo 'PASS: ' . $label . PHP_EOL;
}

mts_register_mentor_post_type();
mts_test_expect( ! $post_types['mentor']['public'] && ! $post_types['mentor']['show_in_rest'] && $post_types['mentor']['show_ui'], 'Mentors are admin-only' );
mts_register_winner_scholarship_taxonomy();
mts_test_expect( [ 'winner' ] === $taxonomies['winner_scholarship']['types'] && ! $taxonomies['winner_scholarship']['args']['public'], 'Scholarships registered privately for winners' );
mts_register_winner_field_groups();
$fields = array_column( $field_groups['group_winner_private_record']['fields'], null, 'name' );
mts_test_expect( isset( $fields['current_location'], $fields['current_country'], $fields['mentor_id'] ), 'Winner location and mentor fields registered' );
mts_test_expect( 'id' === $fields['mentor_id']['return_format'] && 0 === $fields['mentor_id']['multiple'], 'Mentor assignment stores one ID' );
mts_test_expect( 0 === $field_groups['group_winner_private_record']['show_in_rest'], 'Private fields not exposed to REST' );
mts_test_expect( 'Enrollment Country' === $fields['current_country']['label'] && 'Enrollment City / Region' === $fields['current_location']['label'], 'Location fields explicitly describe enrollment' );
mts_test_expect( 'multi_select' === $fields['scholarships_awarded']['field_type'] && 1 === $fields['scholarships_awarded']['save_terms'], 'Multiple scholarship awards save as filterable terms' );

$_GET = [
    'post_type' => 'winner', 'filter_grad_status' => 'enrolled', 'filter_grad_school' => 'MIT',
    'filter_grad_program' => 'PhD', 'filter_current_location' => 'Boston',
    'filter_current_country' => 'United States', 'filter_mentor_id' => '42',
    'awarded_year' => '2025', 'country' => 'nigeria', 'university' => 'lagos',
    'winner_scholarship' => 'mastercard-foundation',
    's' => 'Scholar', 'post_status' => 'draft', 'm' => '202610', 'orderby' => 'grad_school', 'order' => 'ASC',
    'unknown' => 'ignored',
];
$request = mts_winner_filter_request( $_GET );
$args = mts_winner_filtered_query_args( $request );
mts_test_expect( 7 === count( $args['meta_query'] ) && 'AND' === $args['meta_query']['relation'], 'All six metadata filters combine with AND' );
mts_test_expect( 5 === count( $args['tax_query'] ) && 'AND' === $args['tax_query']['relation'], 'Scholarship and existing taxonomy filters combine' );
mts_test_expect( 'Scholar' === $args['s'] && 'draft' === $args['post_status'] && '202610' === $args['m'], 'Search, post status and month retained' );
mts_test_expect( 'grad_school' === $args['meta_key'] && 'meta_value' === $args['orderby'], 'School sorting retained for export' );
mts_test_expect( ! isset( $request['unknown'] ) && [] === mts_winner_filter_request( [ 'filter_grad_school' => [ 'bad' ] ] ), 'Unsupported and array-shaped request values ignored' );
mts_test_expect( ! isset( mts_winner_filtered_query_args( [] )['meta_query'] ), 'Unfiltered list includes winners with no new fields' );

$pagenow = 'edit.php';
$existing = [ 'relation' => 'OR', [ 'key' => 'existing', 'value' => 'yes' ] ];
$list_query = new WP_Query( [ 'meta_query' => $existing ] );
$list_query->main = true;
mts_winner_apply_admin_filters( $list_query );
mts_test_expect( [ 'relation' => 'AND', $existing, $args['meta_query'] ] === $list_query->get( 'meta_query' ), 'Admin list shares export filters and preserves existing constraints' );

mts_test_expect( mts_winner_can_export(), 'Administrator can export' );
$can_export = false;
mts_test_expect( ! mts_winner_can_export(), 'Non-admin cannot export' );
$_GET['mts_export_winners'] = '1';
try {
    mts_winner_export_filtered_csv();
    throw new LogicException( 'Export permission guard did not stop the request' );
} catch ( RuntimeException $exception ) {
    mts_test_expect( false !== strpos( $exception->getMessage(), 'permission' ), 'Export endpoint checks permissions before streaming' );
}
$can_export = true;
try {
    mts_winner_export_filtered_csv();
    throw new LogicException( 'Export nonce guard did not stop the request' );
} catch ( RuntimeException $exception ) {
    mts_test_expect( 'Invalid nonce' === $exception->getMessage(), 'Export endpoint rejects invalid nonce' );
}

foreach ( [ '=1+1', '+cmd', '-cmd', '@SUM(A1)', '  =1', "\t=1", "\rtext", "\xEF\xBB\xBF=1" ] as $value ) {
    mts_test_expect( "'" === mts_winner_csv_cell( $value )[0], 'Spreadsheet formula prefix neutralized: ' . bin2hex( $value ) );
}
mts_test_expect( 'Normal text' === mts_winner_csv_cell( 'Normal text' ), 'Ordinary text preserved' );
WP_Query::$queries = [];
$rows = iterator_to_array( mts_winner_export_rows( $args ) );
mts_test_expect( 204 === count( $rows ), 'All matching records exported across batches, inaccessible winner excluded' );
mts_test_expect( 2 === count( WP_Query::$queries ) && 2 === WP_Query::$queries[1]['paged'], 'Export requests second page' );
mts_test_expect( [ 'meta_value' => 'ASC', 'ID' => 'ASC' ] === WP_Query::$queries[1]['orderby'], 'Export ordering has a stable ID tie-breaker' );
mts_test_expect( [ 200, 5 ] === array_map( 'count', $primed_batches ), 'Post, metadata and term caches primed per batch' );
mts_test_expect( $args['meta_query'] === WP_Query::$queries[1]['meta_query'], 'Filters retained across pages' );
mts_test_expect( count( $rows[0] ) === count( mts_winner_export_columns() ), 'CSV row and header widths match' );
mts_test_expect( 'Enrolled' === $rows[0][5] && 'Mentor Example' === $rows[0][10], 'Status labels and mentor names exported' );
mts_test_expect( "'" === $rows[0][16][0], 'Private notes formula escaped' );
mts_test_expect( 'Mastercard Foundation, Erasmus Mundus' === $rows[0][17], 'Multiple scholarship awards included in CSV' );

$stream = fopen( 'php://temp', 'w+' );
$sample = [ 'Name, with comma', 'Quoted "value"', "Multiline\nnotes", 'Non-ASCII: ' . "\xC3\xA9" ];
fputcsv( $stream, $sample, ',', '"', '' );
rewind( $stream );
mts_test_expect( $sample === fgetcsv( $stream, 0, ',', '"', '' ), 'CSV preserves commas, quotes, newlines and UTF-8' );
fclose( $stream );

mts_mentor_register_winners_box();
mts_test_expect( 'mentor' === $meta_boxes['mts_mentor_winners']['screen'], 'Assigned winners panel registered for mentors' );
$mentor_args = mts_mentor_winner_query_args( 42 );
mts_test_expect( '42' === $mentor_args['meta_query'][0]['value'] && 'mentor_id' === $mentor_args['meta_query'][0]['key'], 'Mentor roster uses the winner assignment relationship' );
mts_test_expect( 20 === $mentor_args['posts_per_page'] && ! in_array( 'trash', $mentor_args['post_status'], true ), 'Mentor roster bounded and excludes trashed winners' );
WP_Query::$fixture_posts = [ 1, 2 ];
ob_start();
mts_mentor_render_winners_box( (object) [ 'ID' => 42 ] );
$html = ob_get_clean();
mts_test_expect( false !== strpos( $html, 'Winner 1' ) && false === strpos( $html, 'Winner 2' ), 'Mentor roster hides inaccessible winners' );
mts_test_expect( false !== strpos( $html, 'filter_mentor_id=42' ) && false !== strpos( $html, 'post=1' ), 'Mentor roster links to winner editor and full filtered list' );
$can_export = false;
ob_start();
mts_mentor_render_winners_box( (object) [ 'ID' => 42 ] );
mts_test_expect( '' === ob_get_clean(), 'Mentor roster requires winner-editing permission' );