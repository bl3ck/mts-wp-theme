<?php
/**
 * Donation Form — shortcode, settings page, PayPal REST endpoints.
 *
 * Usage: [donation_form]
 *
 * Supports one-time donations (PayPal Orders API) and recurring monthly /
 * yearly donations (PayPal Subscriptions API). Plans for recurring donations
 * are created on demand and cached per amount/currency/interval combo.
 *
 * @package Michael_Taiwo_Scholarship
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* -------------------------------------------------------------------------
 * 1. Admin Settings Page (PayPal keys)
 * ---------------------------------------------------------------------- */

function mts_donation_settings_menu() {
    add_options_page(
        'Donation Settings',
        'Donation Settings',
        'manage_options',
        'mts-donation-settings',
        'mts_donation_settings_page'
    );
}
add_action( 'admin_menu', 'mts_donation_settings_menu' );

function mts_donation_settings_init() {
    register_setting( 'mts_donation', 'mts_donation_options', [
        'sanitize_callback' => 'mts_donation_sanitize_options',
    ] );

    add_settings_section(
        'mts_paypal_section',
        'PayPal Settings',
        '__return_null',
        'mts-donation-settings'
    );

    add_settings_field( 'paypal_client_id', 'Client ID', 'mts_donation_text_field', 'mts-donation-settings', 'mts_paypal_section', [
        'id'   => 'paypal_client_id',
        'type' => 'text',
    ] );

    add_settings_field( 'paypal_client_secret', 'Client Secret', 'mts_donation_text_field', 'mts-donation-settings', 'mts_paypal_section', [
        'id'   => 'paypal_client_secret',
        'type' => 'password',
    ] );

    add_settings_field( 'paypal_mode', 'Mode', 'mts_donation_select_field', 'mts-donation-settings', 'mts_paypal_section', [
        'id'      => 'paypal_mode',
        'options' => [ 'sandbox' => 'Sandbox', 'live' => 'Live' ],
    ] );

    add_settings_field( 'donation_currency', 'Currency', 'mts_donation_text_field', 'mts-donation-settings', 'mts_paypal_section', [
        'id'          => 'donation_currency',
        'type'        => 'text',
        'description' => 'Three-letter ISO code, e.g. USD',
    ] );

    add_settings_section(
        'mts_thankyou_section',
        'Thank You Message',
        function () {
            echo '<p>Shown in place of the form after a successful donation. Basic HTML tags (strong, em, a, br) are allowed in the message.</p>';
            echo '<p>Use the token <code>{name}</code> in the heading or message to insert the donor\'s first name (from their PayPal account). If no name is available, it falls back to <em>friend</em>.</p>';
        },
        'mts-donation-settings'
    );

    add_settings_field( 'donation_thankyou_heading', 'Heading', 'mts_donation_text_field', 'mts-donation-settings', 'mts_thankyou_section', [
        'id'   => 'donation_thankyou_heading',
        'type' => 'text',
    ] );

    add_settings_field( 'donation_thankyou_message', 'Message', 'mts_donation_textarea_field', 'mts-donation-settings', 'mts_thankyou_section', [
        'id'   => 'donation_thankyou_message',
        'rows' => 4,
    ] );

    add_settings_section(
        'mts_email_section',
        'Email Receipt',
        function () {
            echo '<p>Sent to the donor\'s PayPal email address after a successful donation. Leave fields blank to use the defaults shown as placeholders.</p>';
            echo '<p>Available tokens: <code>{name}</code>, <code>{amount}</code>, <code>{currency}</code>, <code>{frequency}</code>, <code>{comment}</code>. Basic HTML is allowed in the body.</p>';
        },
        'mts-donation-settings'
    );

    $defaults = mts_donation_email_defaults();

    add_settings_field( 'donation_email_from_name', 'From Name', 'mts_donation_text_field', 'mts-donation-settings', 'mts_email_section', [
        'id'          => 'donation_email_from_name',
        'type'        => 'text',
        'placeholder' => $defaults['donation_email_from_name'],
    ] );

    add_settings_field( 'donation_email_from_address', 'From Email', 'mts_donation_text_field', 'mts-donation-settings', 'mts_email_section', [
        'id'          => 'donation_email_from_address',
        'type'        => 'email',
        'placeholder' => $defaults['donation_email_from_address'],
    ] );

    add_settings_field( 'donation_email_subject', 'Subject', 'mts_donation_text_field', 'mts-donation-settings', 'mts_email_section', [
        'id'          => 'donation_email_subject',
        'type'        => 'text',
        'placeholder' => $defaults['donation_email_subject'],
    ] );

    add_settings_field( 'donation_email_body', 'Body', 'mts_donation_textarea_field', 'mts-donation-settings', 'mts_email_section', [
        'id'          => 'donation_email_body',
        'rows'        => 10,
        'placeholder' => $defaults['donation_email_body'],
    ] );
}
add_action( 'admin_init', 'mts_donation_settings_init' );

function mts_donation_email_defaults() {
    $site_name = get_bloginfo( 'name' );
    return [
        'donation_email_from_name'    => $site_name,
        'donation_email_from_address' => get_option( 'admin_email' ),
        'donation_email_subject'      => 'Thank you for your donation to ' . $site_name,
        'donation_email_body'         => "Dear {name},\n\nThank you so much for your generous {frequency} donation of {currency} {amount}. Your support means more than words can say.\n\nEvery contribution helps us carry out our mission, and yours is a meaningful part of that.\n\nWith gratitude,\nThe " . $site_name . ' team',
    ];
}

function mts_donation_sanitize_options( $input ) {
    $clean = [];
    $text_fields = [
        'paypal_client_id',
        'paypal_client_secret',
        'donation_currency',
        'donation_thankyou_heading',
        'donation_email_from_name',
        'donation_email_subject',
    ];
    foreach ( $text_fields as $key ) {
        $clean[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
    }
    $clean['paypal_mode'] = isset( $input['paypal_mode'] ) && $input['paypal_mode'] === 'live' ? 'live' : 'sandbox';

    $clean['donation_email_from_address'] = isset( $input['donation_email_from_address'] )
        ? sanitize_email( $input['donation_email_from_address'] )
        : '';

    $clean['donation_email_body'] = isset( $input['donation_email_body'] )
        ? wp_kses_post( $input['donation_email_body'] )
        : '';

    $clean['donation_thankyou_message'] = isset( $input['donation_thankyou_message'] )
        ? wp_kses( $input['donation_thankyou_message'], [
            'strong' => [],
            'em'     => [],
            'br'     => [],
            'a'      => [ 'href' => [], 'title' => [], 'target' => [], 'rel' => [] ],
        ] )
        : '';

    // Invalidate cached access token + plans when credentials or mode change.
    $previous = get_option( 'mts_donation_options', [] );
    $credential_keys = [ 'paypal_client_id', 'paypal_client_secret', 'paypal_mode' ];
    foreach ( $credential_keys as $k ) {
        if ( ( $previous[ $k ] ?? '' ) !== ( $clean[ $k ] ?? '' ) ) {
            delete_transient( 'mts_paypal_access_token' );
            delete_option( 'mts_paypal_product_id' );
            delete_option( 'mts_paypal_plans' );
            break;
        }
    }

    return $clean;
}

function mts_donation_text_field( $args ) {
    $opts        = get_option( 'mts_donation_options', [] );
    $value       = isset( $opts[ $args['id'] ] ) ? $opts[ $args['id'] ] : '';
    $type        = isset( $args['type'] ) ? $args['type'] : 'text';
    $placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
    printf(
        '<input type="%s" name="mts_donation_options[%s]" value="%s" placeholder="%s" class="regular-text" autocomplete="off" />',
        esc_attr( $type ),
        esc_attr( $args['id'] ),
        esc_attr( $value ),
        esc_attr( $placeholder )
    );
    if ( ! empty( $args['description'] ) ) {
        printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
    }
}

function mts_donation_textarea_field( $args ) {
    $opts        = get_option( 'mts_donation_options', [] );
    $value       = isset( $opts[ $args['id'] ] ) ? $opts[ $args['id'] ] : '';
    $rows        = isset( $args['rows'] ) ? (int) $args['rows'] : 4;
    $placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
    printf(
        '<textarea name="mts_donation_options[%s]" rows="%d" placeholder="%s" class="large-text">%s</textarea>',
        esc_attr( $args['id'] ),
        $rows,
        esc_attr( $placeholder ),
        esc_textarea( $value )
    );
    if ( ! empty( $args['description'] ) ) {
        printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
    }
}

function mts_donation_select_field( $args ) {
    $opts  = get_option( 'mts_donation_options', [] );
    $value = isset( $opts[ $args['id'] ] ) ? $opts[ $args['id'] ] : '';
    echo '<select name="mts_donation_options[' . esc_attr( $args['id'] ) . ']" class="regular-text">';
    foreach ( $args['options'] as $k => $label ) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr( $k ),
            selected( $value, $k, false ),
            esc_html( $label )
        );
    }
    echo '</select>';
}

function mts_donation_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Donation Settings</h1>
        <p>
            Create a REST API app at
            <a href="https://developer.paypal.com/dashboard/applications" target="_blank" rel="noopener">developer.paypal.com</a>
            to obtain your Client ID and Secret. Use the Sandbox credentials for testing and switch to Live when ready.
        </p>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'mts_donation' );
            do_settings_sections( 'mts-donation-settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

function mts_donation_option( $key, $default = '' ) {
    $opts = get_option( 'mts_donation_options', [] );
    return isset( $opts[ $key ] ) ? $opts[ $key ] : $default;
}

function mts_donation_currency() {
    return strtoupper( mts_donation_option( 'donation_currency', 'USD' ) );
}

function mts_paypal_api_base() {
    $mode = mts_donation_option( 'paypal_mode', 'sandbox' );
    return 'live' === $mode
        ? 'https://api-m.paypal.com'
        : 'https://api-m.sandbox.paypal.com';
}

/* -------------------------------------------------------------------------
 * 2. PayPal OAuth2 — fetch access token with caching.
 * ---------------------------------------------------------------------- */

function mts_paypal_get_access_token() {
    $client_id = mts_donation_option( 'paypal_client_id' );
    $secret    = mts_donation_option( 'paypal_client_secret' );

    if ( empty( $client_id ) || empty( $secret ) ) {
        return new WP_Error( 'missing_credentials', 'PayPal credentials not configured.', [ 'status' => 500 ] );
    }

    $cached = get_transient( 'mts_paypal_access_token' );
    if ( $cached ) {
        return $cached;
    }

    $response = wp_remote_post( mts_paypal_api_base() . '/v1/oauth2/token', [
        'headers' => [
            'Accept'        => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $secret ),
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ],
        'body'    => 'grant_type=client_credentials',
        'timeout' => 30,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'paypal_network_error', $response->get_error_message(), [ 'status' => 500 ] );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $data['access_token'] ) ) {
        $msg = $data['error_description'] ?? ( $data['error'] ?? 'PayPal authentication failed.' );
        return new WP_Error( 'paypal_auth_failed', $msg, [ 'status' => 500 ] );
    }

    // PayPal tokens typically last ~9 hours; cache a little shy of expires_in.
    $expires = isset( $data['expires_in'] ) ? max( 60, (int) $data['expires_in'] - 120 ) : 3000;
    set_transient( 'mts_paypal_access_token', $data['access_token'], $expires );

    return $data['access_token'];
}

/* -------------------------------------------------------------------------
 * 3. REST API Endpoints
 * ---------------------------------------------------------------------- */

function mts_donation_register_routes() {
    $namespace = 'mts/v1';

    register_rest_route( $namespace, '/donate/paypal/create-order', [
        'methods'             => 'POST',
        'callback'            => 'mts_donation_paypal_create_order',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $namespace, '/donate/paypal/capture-order', [
        'methods'             => 'POST',
        'callback'            => 'mts_donation_paypal_capture_order',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $namespace, '/donate/paypal/create-subscription', [
        'methods'             => 'POST',
        'callback'            => 'mts_donation_paypal_create_subscription',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $namespace, '/donate/paypal/subscription-activated', [
        'methods'             => 'POST',
        'callback'            => 'mts_donation_paypal_subscription_activated',
        'permission_callback' => '__return_true',
    ] );
}
add_action( 'rest_api_init', 'mts_donation_register_routes' );

/* ---- One-time orders ------------------------------------------------- */

function mts_donation_paypal_create_order( WP_REST_Request $request ) {
    $amount  = floatval( $request->get_param( 'amount' ) );
    $comment = sanitize_textarea_field( (string) $request->get_param( 'comment' ) );

    if ( $amount < 1 ) {
        return new WP_Error( 'invalid_amount', 'Minimum donation is 1.', [ 'status' => 400 ] );
    }

    $token = mts_paypal_get_access_token();
    if ( is_wp_error( $token ) ) {
        return $token;
    }

    $currency = mts_donation_currency();
    $body     = [
        'intent'         => 'CAPTURE',
        'purchase_units' => [
            [
                'amount'      => [
                    'currency_code' => $currency,
                    'value'         => number_format( $amount, 2, '.', '' ),
                ],
                'description' => $comment ? 'Donation — ' . mb_substr( $comment, 0, 80 ) : 'Donation',
                'custom_id'   => $comment ? mb_substr( $comment, 0, 127 ) : '',
            ],
        ],
    ];

    $response = wp_remote_post( mts_paypal_api_base() . '/v2/checkout/orders', [
        'headers' => [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ],
        'body'    => wp_json_encode( $body ),
        'timeout' => 30,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'paypal_network_error', $response->get_error_message(), [ 'status' => 500 ] );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $data['id'] ) ) {
        $msg = $data['message'] ?? 'Could not create PayPal order.';
        return new WP_Error( 'paypal_create_failed', $msg, [ 'status' => 500 ] );
    }

    return rest_ensure_response( [ 'id' => $data['id'] ] );
}

function mts_donation_paypal_capture_order( WP_REST_Request $request ) {
    $order_id = sanitize_text_field( (string) $request->get_param( 'order_id' ) );
    $comment  = sanitize_textarea_field( (string) $request->get_param( 'comment' ) );

    if ( empty( $order_id ) ) {
        return new WP_Error( 'missing_order', 'Order ID required.', [ 'status' => 400 ] );
    }

    $token = mts_paypal_get_access_token();
    if ( is_wp_error( $token ) ) {
        return $token;
    }

    $response = wp_remote_post( mts_paypal_api_base() . '/v2/checkout/orders/' . rawurlencode( $order_id ) . '/capture', [
        'headers' => [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ],
        'body'    => '{}',
        'timeout' => 30,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'paypal_network_error', $response->get_error_message(), [ 'status' => 500 ] );
    }

    $data   = json_decode( wp_remote_retrieve_body( $response ), true );
    $status = $data['status'] ?? '';

    if ( 'COMPLETED' !== $status ) {
        $msg = $data['message'] ?? 'Could not capture PayPal order.';
        return new WP_Error( 'paypal_capture_failed', $msg, [ 'status' => 500 ] );
    }

    $donor_name = isset( $data['payer']['name']['given_name'] )
        ? sanitize_text_field( (string) $data['payer']['name']['given_name'] )
        : '';

    do_action( 'mts_donation_completed', [
        'type'    => 'paypal_order',
        'comment' => mb_substr( $comment, 0, 100 ),
        'name'    => $donor_name,
        'data'    => $data,
    ] );

    return rest_ensure_response( [
        'success' => true,
        'name'    => $donor_name,
    ] );
}

/* ---- Recurring: product + plan catalog ------------------------------ */

function mts_paypal_get_or_create_product( $token ) {
    $product_id = get_option( 'mts_paypal_product_id' );
    if ( $product_id ) {
        return $product_id;
    }

    $body = [
        'name'        => get_bloginfo( 'name' ) . ' Recurring Donation',
        'description' => 'Recurring donation to support the scholarship programme.',
        'type'        => 'SERVICE',
        'category'    => 'NONPROFIT',
    ];

    $response = wp_remote_post( mts_paypal_api_base() . '/v1/catalogs/products', [
        'headers' => [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ],
        'body'    => wp_json_encode( $body ),
        'timeout' => 30,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'paypal_network_error', $response->get_error_message() );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $data['id'] ) ) {
        return new WP_Error( 'paypal_product_failed', $data['message'] ?? 'Could not create PayPal product.' );
    }

    update_option( 'mts_paypal_product_id', $data['id'] );
    return $data['id'];
}

function mts_paypal_get_or_create_plan( $token, $amount, $currency, $interval ) {
    $formatted = number_format( $amount, 2, '.', '' );
    $plans     = get_option( 'mts_paypal_plans', [] );
    $key       = strtolower( $currency . '_' . $formatted . '_' . $interval );

    if ( ! empty( $plans[ $key ] ) ) {
        return $plans[ $key ];
    }

    $product_id = mts_paypal_get_or_create_product( $token );
    if ( is_wp_error( $product_id ) ) {
        return $product_id;
    }

    $interval_unit = 'monthly' === $interval ? 'MONTH' : 'YEAR';

    $body = [
        'product_id'          => $product_id,
        'name'                => ucfirst( $interval ) . ' donation ' . $currency . ' ' . $formatted,
        'description'         => ucfirst( $interval ) . ' recurring donation of ' . $currency . ' ' . $formatted,
        'status'              => 'ACTIVE',
        'billing_cycles'      => [
            [
                'frequency'      => [
                    'interval_unit'  => $interval_unit,
                    'interval_count' => 1,
                ],
                'tenure_type'    => 'REGULAR',
                'sequence'       => 1,
                'total_cycles'   => 0, // infinite
                'pricing_scheme' => [
                    'fixed_price' => [
                        'value'         => $formatted,
                        'currency_code' => $currency,
                    ],
                ],
            ],
        ],
        'payment_preferences' => [
            'auto_bill_outstanding'     => true,
            'setup_fee_failure_action'  => 'CONTINUE',
            'payment_failure_threshold' => 3,
        ],
    ];

    $response = wp_remote_post( mts_paypal_api_base() . '/v1/billing/plans', [
        'headers' => [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ],
        'body'    => wp_json_encode( $body ),
        'timeout' => 30,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'paypal_network_error', $response->get_error_message() );
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $data['id'] ) ) {
        return new WP_Error( 'paypal_plan_failed', $data['message'] ?? 'Could not create PayPal plan.' );
    }

    $plans[ $key ] = $data['id'];
    update_option( 'mts_paypal_plans', $plans );

    return $data['id'];
}

function mts_donation_paypal_create_subscription( WP_REST_Request $request ) {
    $amount    = floatval( $request->get_param( 'amount' ) );
    $frequency = sanitize_text_field( (string) $request->get_param( 'frequency' ) );

    if ( $amount < 1 ) {
        return new WP_Error( 'invalid_amount', 'Minimum donation is 1.', [ 'status' => 400 ] );
    }
    if ( ! in_array( $frequency, [ 'monthly', 'yearly' ], true ) ) {
        return new WP_Error( 'invalid_frequency', 'Invalid frequency.', [ 'status' => 400 ] );
    }

    $token = mts_paypal_get_access_token();
    if ( is_wp_error( $token ) ) {
        return $token;
    }

    $plan_id = mts_paypal_get_or_create_plan( $token, $amount, mts_donation_currency(), $frequency );
    if ( is_wp_error( $plan_id ) ) {
        return new WP_Error( $plan_id->get_error_code(), $plan_id->get_error_message(), [ 'status' => 500 ] );
    }

    return rest_ensure_response( [ 'plan_id' => $plan_id ] );
}

function mts_donation_paypal_subscription_activated( WP_REST_Request $request ) {
    $subscription_id = sanitize_text_field( (string) $request->get_param( 'subscription_id' ) );

    if ( empty( $subscription_id ) ) {
        return new WP_Error( 'missing_subscription', 'Subscription ID required.', [ 'status' => 400 ] );
    }

    // Verify with PayPal that the subscription is actually active.
    $token = mts_paypal_get_access_token();
    if ( is_wp_error( $token ) ) {
        return $token;
    }

    $response = wp_remote_get( mts_paypal_api_base() . '/v1/billing/subscriptions/' . rawurlencode( $subscription_id ), [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
        ],
        'timeout' => 30,
    ] );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'paypal_network_error', $response->get_error_message(), [ 'status' => 500 ] );
    }

    $data   = json_decode( wp_remote_retrieve_body( $response ), true );
    $status = $data['status'] ?? '';

    if ( ! in_array( $status, [ 'ACTIVE', 'APPROVAL_PENDING', 'APPROVED' ], true ) ) {
        return new WP_Error( 'paypal_subscription_inactive', 'Subscription is not active (status: ' . $status . ').', [ 'status' => 500 ] );
    }

    // Pull the comment from PayPal's verified response rather than trusting
    // whatever the client passed in this request. The donor's comment was
    // written to custom_id when the subscription was created client-side.
    $comment = isset( $data['custom_id'] ) ? sanitize_textarea_field( (string) $data['custom_id'] ) : '';

    $donor_name = isset( $data['subscriber']['name']['given_name'] )
        ? sanitize_text_field( (string) $data['subscriber']['name']['given_name'] )
        : '';

    do_action( 'mts_donation_completed', [
        'type'    => 'paypal_subscription',
        'comment' => mb_substr( $comment, 0, 100 ),
        'name'    => $donor_name,
        'data'    => $data,
    ] );

    return rest_ensure_response( [
        'success' => true,
        'name'    => $donor_name,
    ] );
}

/* -------------------------------------------------------------------------
 * 4. Shortcode
 * ---------------------------------------------------------------------- */

function mts_donation_form_shortcode( $atts ) {
    // Normalize shortcode attributes (none currently supported, but keeps
    // the signature WP-compliant and ready for future options).
    shortcode_atts( [], $atts, 'donation_form' );

    // Flag so we can conditionally enqueue scripts in the footer.
    $GLOBALS['mts_donation_form_rendered'] = true;

    $currency   = mts_donation_currency();
    $nonce      = wp_create_nonce( 'wp_rest' );
    $rest_url   = esc_url_raw( rest_url( 'mts/v1/donate' ) );
    $paypal_id  = mts_donation_option( 'paypal_client_id' );
    $ty_heading = mts_donation_option( 'donation_thankyou_heading', 'Thank you!' );
    $ty_message = mts_donation_option( 'donation_thankyou_message', 'Your generous donation means the world to us. Together, we are making a real difference.' );
    if ( '' === trim( $ty_heading ) ) {
        $ty_heading = 'Thank you!';
    }
    if ( '' === trim( $ty_message ) ) {
        $ty_message = 'Your generous donation means the world to us. Together, we are making a real difference.';
    }

    // Pre-render the message body HTML server-side (wpautop + wp_kses) so the
    // JS component only has to handle {name} token substitution. The heading
    // stays as plain text since it's rendered via x-text, not x-html.
    $ty_message_html = wp_kses_post( wpautop( $ty_message ) );

    // Reusable class strings — keeps selected/unselected button states in
    // sync and keeps the input styling in one place.
    $btn_base     = 'border rounded-md py-2.5 text-sm font-medium transition-colors duration-150 cursor-pointer font-spartan';
    $btn_selected = 'bg-[#b8c4b8] border-[#b8c4b8] text-gray-900';
    $btn_idle     = 'bg-white border-gray-300 text-gray-700 hover:border-gray-400';
    $input_base   = 'w-full bg-white border border-gray-300 rounded-md font-spartan text-gray-900 focus:outline-none focus:ring-2 focus:ring-mt-blue/40 focus:border-mt-blue';

    ob_start();
    ?>
    <div class="mts-donation-wrapper not-prose rounded-2xl bg-mt-sand p-6 sm:p-10 max-w-2xl mx-auto"
         x-data="donationForm()"
         x-cloak>

        <!-- Thank-you message (shown after successful donation) -->
        <div x-show="success" x-transition x-cloak class="text-center py-6">
            <h2 class="!text-3xl sm:!text-4xl !mb-4 font-canela text-mt-blue"
                x-text="thankYouHeading"></h2>
            <div class="text-base text-gray-700 font-spartan leading-relaxed"
                 x-html="thankYouMessage"></div>
        </div>

        <!-- Donation form (hidden after successful donation) -->
        <div x-show="!success">

            <!-- Heading -->
            <h2 class="!text-3xl sm:!text-4xl !mb-2 font-canela text-mt-blue">Make a difference</h2>
            <p class="text-base text-gray-700 mb-8 font-spartan">
                Change starts with people like you. Your donation helps make a real impact, one action at a time. Together, we can do more.
            </p>

            <!-- Frequency -->
            <fieldset class="mb-6">
                <legend class="text-sm font-semibold text-gray-800 mb-2 font-spartan">Frequency</legend>
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="f in frequencies" :key="f.value">
                        <button type="button"
                                @click="frequency = f.value"
                                :class="frequency === f.value ? '<?php echo esc_attr( $btn_selected ); ?>' : '<?php echo esc_attr( $btn_idle ); ?>'"
                                class="<?php echo esc_attr( $btn_base ); ?>"
                                x-text="f.label">
                        </button>
                    </template>
                </div>
            </fieldset>

            <!-- Amount -->
            <fieldset class="mb-6">
                <legend class="text-sm font-semibold text-gray-800 mb-2 font-spartan">Amount</legend>
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <template x-for="a in amounts" :key="a">
                        <button type="button"
                                @click="selectAmount(a)"
                                :class="selectedPreset === a && !customActive ? '<?php echo esc_attr( $btn_selected ); ?>' : '<?php echo esc_attr( $btn_idle ); ?>'"
                                class="<?php echo esc_attr( $btn_base ); ?>"
                                x-text="'US$' + a.toLocaleString()">
                        </button>
                    </template>
                    <button type="button"
                            @click="activateCustom()"
                            :class="customActive ? '<?php echo esc_attr( $btn_selected ); ?>' : '<?php echo esc_attr( $btn_idle ); ?>'"
                            class="<?php echo esc_attr( $btn_base ); ?>">
                        Other
                    </button>
                </div>
                <!-- Custom amount input (only when "Other" is selected) -->
                <div x-show="customActive" x-transition class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-700 text-base font-spartan font-medium">$</span>
                    <input type="number" min="1" step="1"
                           x-model.number="customAmount"
                           @input="amount = customAmount"
                           placeholder="Enter amount"
                           class="<?php echo esc_attr( $input_base ); ?> py-3.5 pl-9 pr-4 text-base font-medium placeholder:text-gray-400" />
                </div>
            </fieldset>

            <!-- Comment -->
            <fieldset class="mb-6">
                <legend class="text-sm font-semibold text-gray-800 mb-2 font-spartan">Comment (optional)</legend>
                <textarea x-model="comment" maxlength="100" rows="3"
                          class="<?php echo esc_attr( $input_base ); ?> py-2.5 px-3 text-sm bg-white resize-none"></textarea>
                <p class="text-right text-xs text-gray-500 mt-1 font-spartan"
                   x-text="comment.length + '/100'"></p>
            </fieldset>

            <!-- Error message -->
            <div x-show="error" x-transition class="mb-4 rounded-md bg-red-50 border border-red-200 p-3">
                <p class="text-sm text-red-700 font-spartan" x-text="error"></p>
            </div>

            <!-- Prompt when no amount selected -->
            <div x-show="!amount || amount < 1"
                 class="w-full text-center py-4 rounded-md bg-white/60 border border-dashed border-gray-400 mb-2">
                <p class="text-sm text-gray-600 font-spartan">Select an amount to continue</p>
            </div>

            <!-- Label above PayPal button -->
            <p x-show="amount >= 1" class="text-sm text-gray-700 font-spartan mb-2"
               x-text="buttonLabel"></p>

            <!-- PayPal Button Container -->
            <div x-show="amount >= 1" id="mts-paypal-button" class="mt-1"></div>

            <!-- Processing overlay -->
            <div x-show="processing" class="mt-3 text-center">
                <span class="inline-flex items-center gap-2 text-sm text-gray-700 font-spartan">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing your donation…
                </span>
            </div>

        </div><!-- /x-show="!success" -->
    </div>

    <script>
        window.mtsDonationConfig = {
            restUrl:         <?php echo wp_json_encode( $rest_url ); ?>,
            nonce:           <?php echo wp_json_encode( $nonce ); ?>,
            paypalId:        <?php echo wp_json_encode( $paypal_id ); ?>,
            currency:        <?php echo wp_json_encode( $currency ); ?>,
            thankYouHeading: <?php echo wp_json_encode( $ty_heading ); ?>,
            thankYouMessage: <?php echo wp_json_encode( $ty_message_html ); ?>,
        };
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'donation_form', 'mts_donation_form_shortcode' );

/* -------------------------------------------------------------------------
 * 5. Enqueue Scripts (only when shortcode is present)
 * ---------------------------------------------------------------------- */

function mts_donation_enqueue_scripts() {
    if ( empty( $GLOBALS['mts_donation_form_rendered'] ) ) {
        return;
    }

    // Alpine.js donation component. The PayPal JS SDK itself is loaded
    // dynamically at runtime so we can request the correct intent/vault
    // params depending on whether the visitor picked one-time or recurring.
    wp_enqueue_script(
        'mts-donation-form',
        get_template_directory_uri() . '/js/donation-form.js',
        [],
        defined( 'MT_VERSION' ) ? MT_VERSION : '1.0',
        true
    );
}
add_action( 'wp_footer', 'mts_donation_enqueue_scripts', 5 );

/* -------------------------------------------------------------------------
 * 6. Donor thank-you email
 *
 * Hooks into mts_donation_completed and sends a branded HTML email to the
 * donor's PayPal email address. All copy is configurable in Settings →
 * Donation Settings → Email Receipt with token substitution.
 * ---------------------------------------------------------------------- */

/**
 * Reverse-lookup a cached plan ID to get currency / amount / frequency.
 *
 * The plan cache is keyed `{currency}_{amount}_{interval}` so we can recover
 * all three pieces from the plan_id that PayPal returns on a subscription.
 */
function mts_paypal_plan_details_from_id( $plan_id ) {
    $empty = [ 'currency' => '', 'amount' => '', 'frequency' => '' ];
    if ( empty( $plan_id ) ) {
        return $empty;
    }
    $plans = get_option( 'mts_paypal_plans', [] );
    if ( ! is_array( $plans ) ) {
        return $empty;
    }
    foreach ( $plans as $key => $id ) {
        if ( $id !== $plan_id ) {
            continue;
        }
        $parts = explode( '_', $key );
        if ( count( $parts ) < 3 ) {
            return $empty;
        }
        return [
            'currency'  => strtoupper( $parts[0] ),
            'amount'    => $parts[1],
            'frequency' => $parts[2],
        ];
    }
    return $empty;
}

function mts_donation_send_thankyou_email( $payload ) {
    $type = $payload['type'] ?? '';
    $data = $payload['data'] ?? [];

    // Pull donor email, name, amount, currency, and frequency from the
    // verified PayPal response. Structure differs between order capture
    // and subscription activation.
    if ( 'paypal_order' === $type ) {
        $donor_email = $data['payer']['email_address'] ?? '';
        $first_name  = $data['payer']['name']['given_name'] ?? '';
        $capture     = $data['purchase_units'][0]['payments']['captures'][0] ?? [];
        $amount      = $capture['amount']['value'] ?? '';
        $currency    = $capture['amount']['currency_code'] ?? '';
        $frequency   = 'one-time';
    } elseif ( 'paypal_subscription' === $type ) {
        $donor_email = $data['subscriber']['email_address'] ?? '';
        $first_name  = $data['subscriber']['name']['given_name'] ?? '';
        // Subscriptions that are still APPROVAL_PENDING / APPROVED won't
        // have a last_payment yet, so fall back to our plan cache for
        // amount/currency — that's where we wrote them at plan creation.
        $plan_details = mts_paypal_plan_details_from_id( $data['plan_id'] ?? '' );
        $amount       = $data['billing_info']['last_payment']['amount']['value']
            ?? $plan_details['amount'];
        $currency     = $data['billing_info']['last_payment']['amount']['currency_code']
            ?? $plan_details['currency'];
        $frequency    = $plan_details['frequency']; // "monthly" or "yearly"
    } else {
        return;
    }

    // Fall back to the top-level 'name' key we already stashed in the payload
    // if given_name wasn't in the response for some reason.
    if ( empty( $first_name ) ) {
        $first_name = $payload['name'] ?? '';
    }

    if ( empty( $donor_email ) || ! is_email( $donor_email ) ) {
        return;
    }

    $defaults = mts_donation_email_defaults();

    $from_name = mts_donation_option( 'donation_email_from_name' );
    if ( '' === trim( $from_name ) ) {
        $from_name = $defaults['donation_email_from_name'];
    }

    $from_email = mts_donation_option( 'donation_email_from_address' );
    if ( empty( $from_email ) || ! is_email( $from_email ) ) {
        $from_email = $defaults['donation_email_from_address'];
    }

    $subject = mts_donation_option( 'donation_email_subject' );
    if ( '' === trim( $subject ) ) {
        $subject = $defaults['donation_email_subject'];
    }

    $body = mts_donation_option( 'donation_email_body' );
    if ( '' === trim( $body ) ) {
        $body = $defaults['donation_email_body'];
    }

    // Build the token map. Subject is plain text so no escaping; body is
    // rendered as HTML so we escape the interpolated values.
    $plain_tokens = [
        '{name}'      => $first_name ?: 'friend',
        '{amount}'    => $amount,
        '{currency}'  => $currency,
        '{frequency}' => $frequency,
        '{comment}'   => $payload['comment'] ?? '',
    ];
    $html_tokens = array_map( 'esc_html', $plain_tokens );

    $subject = strtr( $subject, $plain_tokens );
    $body    = strtr( $body, $html_tokens );
    $body    = wpautop( $body );

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        sprintf( 'From: %s <%s>', $from_name, $from_email ),
        'Reply-To: ' . $from_email,
    ];

    wp_mail( $donor_email, $subject, $body, $headers );
}
add_action( 'mts_donation_completed', 'mts_donation_send_thankyou_email' );
