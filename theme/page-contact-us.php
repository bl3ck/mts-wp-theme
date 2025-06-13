<?php

/**
 * Template Name: MT Contact Page
 *
 * @package Michael_Taiwo_Scholarship
 * 
 **/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
?>

<section>
    <div class="page-container">
        <div class="grid sm:grid-cols-2 gap-8 sm:gap-20">
            <div class="flex gap-8 flex-col">
                <p class="max-w-lg">If you have any questions, suggestions, or need guidance on our services, please fill out the form below.</p>
                <p class="max-w-lg">Our team is eager to support you in your admission journey.</p>

                <div class="rounded-2xl bg-gray-50 p-8">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">US Office Contact</h2>
                    <ul class="mb-4 grid sm:grid-cols-2 gap-4">
                        <li>
                            <span class="font-sans block mb-2 font-medium">Email:</span>
                            <a href="mailto:">info@mtscholarships.org</a>
                        </li>
                        <li>
                            <span class="font-sans block mb-2 font-medium">Phone:</span>
                            <a href="tel:+19515544975">+1 479 966 7776</a>
                        </li>
                    </ul>
                    <div>
                        <span class="font-sans block mb-2 font-medium">Address:</span>
                        <address>
                            31500 Grape Street, Suite 3-199, Lake Elsinore, CA 92532
                        </address>
                    </div>
                </div>
            </div>
            <div class="contact-form">
                <?php echo do_shortcode('[contact-form-7 title="Contact form 1"]') ?>
                <p class="mt-8 pt-6 text-sm border-t font-poppins font-light [&amp;>a]:underline [&amp;>a]:underline-offset-4">By submitting this form, you agree to the <a class="text-sm text-green-700 hover:opacity-90" href="<?php echo get_page_url_by_title('Privacy Policy') ?>">Terms &amp; Conditions and Privacy Policy</a>, and grant your consent to the Michael Taiwo Scholarship team contacting you.</p>
            </div>
        </div>
    </div>
</section>


<?php
get_footer();
