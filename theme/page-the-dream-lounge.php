<?php

/**
 * Template Name: MT Dream Lounge Page
 *
 * @package Michael_Taiwo_Scholarship
 * 
 **/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
?>


<section id="newsletter">
    <div class="page-container">
        <div class="grid sm:grid-cols-2 gap-8">
            <div class="hidden md:block">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/imgs/mt-newsletter.png'); ?>" alt="Subscribe to our Newsletter" class="w-full h-full object-contain">
            </div>
            <div class="mb-8">
                <h4>Connect. Grow. Inspire — Join Dream Lounge</h4>
                <p>Stay connected to the heartbeat of our community with Dream Lounge, a thoughtfully curated newsletter that illuminates the paths of achievers, offers practical wisdom, and connects you with a global tribe of dreamers.</p>
                <div class="max-w-2xl mx-auto mt-6 contact-form bg-mt-cream p-6 sm:p-8 rounded-xl shadow-md">
                    <?php echo do_shortcode('[sibwp_form id=1]'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
