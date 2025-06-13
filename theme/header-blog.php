<?php

/**
 * The header for our theme
 *
 * This is the template that displays the `head` element and everything up
 * until the `#content` element.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Michael_Taiwo_Scholarship
 */

?>
<!doctype html>
<html x-cloak <?php language_attributes(); ?> class="scroll-smooth h-full" x-data="{
		open: false,
		day: '0',
		mobileMenuFlyout: false,
        modal: false, 
        tab: window.location.hash ? window.location.hash : '',
        action: '',
        sidebar: false
    }" @hashchange.window="tab = location.hash">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none
        }
    </style>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php if (!is_front_page()): ?>
        <div class="page-container py-0">
            <div class="bg-transparent my-2 rounded-full flex flex-col align-middle items-end justify-end">
                <?php echo do_shortcode('[application_badge]'); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php wp_body_open(); ?>

    <div id="page">
        <a href="#content" class="sr-only"><?php esc_html_e('Skip to content', 'michael-taiwo-scholarship'); ?></a>

        <?php get_template_part('template-parts/layout/header', 'blog'); ?>

        <div id="content">