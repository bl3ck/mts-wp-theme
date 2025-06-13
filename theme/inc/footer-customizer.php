<?php
/**
 * MT Footer customizer settings
 * 
 * @package WordPress
 * @since 6.5.0
 */

/**
 * Register footer customizer settings
 * 
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function theme_footer_customizer($wp_customize) {
    // Social Media Section
    $wp_customize->add_section('social_media_section', array(
        'title' => __('Social Media Links', 'theme'),
        'priority' => 30,
        'description' => __('Configure social media links that appear in the footer', 'theme'),
    ));
    
    // Twitter URL - Set a default URL so icons always show unless explicitly cleared
    $wp_customize->add_setting('social_twitter', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('social_twitter', array(
        'label' => __('Twitter URL', 'theme'),
        'section' => 'social_media_section',
        'type' => 'url',
    ));
    
    // LinkedIn URL
    $wp_customize->add_setting('social_linkedin', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('social_linkedin', array(
        'label' => __('LinkedIn URL', 'theme'),
        'section' => 'social_media_section',
        'type' => 'url',
    ));
    
    // YouTube URL
    $wp_customize->add_setting('social_youtube', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('social_youtube', array(
        'label' => __('YouTube URL', 'theme'),
        'section' => 'social_media_section',
        'type' => 'url',
    ));
    
    // Instagram URL
    $wp_customize->add_setting('social_instagram', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('social_instagram', array(
        'label' => __('Instagram URL', 'theme'),
        'section' => 'social_media_section',
        'type' => 'url',
    ));
    
    // Facebook URL
    $wp_customize->add_setting('social_facebook', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('social_facebook', array(
        'label' => __('Facebook URL', 'theme'),
        'section' => 'social_media_section',
        'type' => 'url',
    ));
    
    // Copyright Text
    $wp_customize->add_setting('footer_copyright', array(
        'default' => get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('footer_copyright', array(
        'label' => __('Copyright Text', 'theme'),
        'description' => __('Text to display in copyright notice', 'theme'),
        'section' => 'footer_links_section',
        'type' => 'text',
    ));
}
add_action('customize_register', 'theme_footer_customizer');
