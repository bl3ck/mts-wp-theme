<?php
/**
 * Winner post type — additional ACF field groups.
 *
 * Registers two field groups via PHP (so they're version-controlled and
 * don't collide with the existing ACF JSON-synced "Winner Meta" group):
 *
 *   1. Scholar Record (Admin Only) — private record-keeping. NEVER
 *      rendered in public templates.
 *   2. Additional Media — `after_headshot` image field. Safe to render
 *      publicly if the theme chooses to.
 *
 * @package Michael_Taiwo_Scholarship
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Canonical list of grad status choices. Shared by the ACF select field
 * and the admin list filter so the two can't drift out of sync.
 *
 * The empty-string key is excluded from the filter dropdown but used as
 * the ACF "Not set" placeholder.
 */
function mts_winner_grad_status_choices() {
    return [
        ''          => '— Not set —',
        'applied'   => 'Applied',
        'admitted'  => 'Admitted',
        'enrolled'  => 'Enrolled',
        'graduated' => 'Graduated',
        'deferred'  => 'Deferred',
        'withdrawn' => 'Withdrawn',
        'other'     => 'Other',
    ];
}

add_action( 'acf/init', 'mts_register_winner_field_groups' );
function mts_register_winner_field_groups() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    /* ---------------------------------------------------------------
     * 1. Scholar Record (Admin Only)
     *
     * These fields are intentionally NOT rendered by any public
     * template. They exist purely for internal record-keeping and
     * admin-side filtering.
     * ------------------------------------------------------------- */
    acf_add_local_field_group( [
        'key'                   => 'group_winner_private_record',
        'title'                 => 'Scholar Record (Admin Only)',
        'description'           => 'Private record-keeping fields. These are never displayed on the public site — they exist only for internal tracking and admin filtering.',
        'location'              => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'winner',
                ],
            ],
        ],
        'position'              => 'normal',
        'menu_order'            => 10,
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'show_in_rest'          => 0,
        'active'                => true,
        'fields'                => [
            [
                'key'   => 'field_winner_grad_school',
                'label' => 'Current Graduate School',
                'name'  => 'grad_school',
                'type'  => 'text',
                'wrapper' => [ 'width' => '50' ],
            ],
            [
                'key'          => 'field_winner_grad_program',
                'label'        => 'Graduate Program / Degree',
                'name'         => 'grad_program',
                'type'         => 'text',
                'instructions' => 'e.g. MSc Computer Science, PhD Public Health, MBA',
                'wrapper'      => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_winner_grad_status',
                'label'         => 'Status',
                'name'          => 'grad_status',
                'type'          => 'select',
                'choices'       => mts_winner_grad_status_choices(),
                'default_value' => '',
                'allow_null'    => 0,
                'multiple'      => 0,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '33' ],
            ],
            [
                'key'     => 'field_winner_grad_start_year',
                'label'   => 'Start Year',
                'name'    => 'grad_start_year',
                'type'    => 'number',
                'min'     => 1900,
                'max'     => 2100,
                'wrapper' => [ 'width' => '33' ],
            ],
            [
                'key'     => 'field_winner_grad_expected_end',
                'label'   => 'Expected Graduation Year',
                'name'    => 'grad_expected_end',
                'type'    => 'number',
                'min'     => 1900,
                'max'     => 2100,
                'wrapper' => [ 'width' => '34' ],
            ],
            [
                'key'     => 'field_winner_contact_email',
                'label'   => 'Contact Email',
                'name'    => 'contact_email',
                'type'    => 'email',
                'wrapper' => [ 'width' => '50' ],
            ],
            [
                'key'     => 'field_winner_linkedin',
                'label'   => 'LinkedIn URL',
                'name'    => 'linkedin_url',
                'type'    => 'url',
                'wrapper' => [ 'width' => '50' ],
            ],
            [
                'key'          => 'field_winner_private_notes',
                'label'        => 'Private Notes',
                'name'         => 'private_notes',
                'type'         => 'textarea',
                'rows'         => 6,
                'new_lines'    => 'wpautop',
                'instructions' => 'Any other internal notes about this scholar. Not shown publicly.',
            ],
        ],
    ] );

    /* ---------------------------------------------------------------
     * 2. Additional Media
     *
     * An "after" headshot shown as a sidebar metabox. Theme templates
     * can read this via get_field('after_headshot') if/when they want
     * to display it alongside the main featured image.
     * ------------------------------------------------------------- */
    acf_add_local_field_group( [
        'key'      => 'group_winner_media',
        'title'    => 'Additional Media',
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'winner',
                ],
            ],
        ],
        'position'     => 'side',
        'menu_order'   => 20,
        'show_in_rest' => 0,
        'active'       => true,
        'fields'       => [
            [
                'key'           => 'field_winner_after_headshot',
                'label'         => 'After Headshot',
                'name'          => 'after_headshot',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
                'instructions'  => 'A recent photo of the scholar. The main Featured Image serves as the "before" photo.',
            ],
        ],
    ] );
}
