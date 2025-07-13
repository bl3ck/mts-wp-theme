<?php

/**
 * Template Name: MT Home Page
 *
 * @package Michael_Taiwo_Scholarship
 * 
 **/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

$colors = array('black', 'gray-200', 'gray-300', 'gray-400');

// Blog
global $post;
// $posts = array(
//     'post_type'      => 'post',
//     'posts_per_page' => 4,
//     'order' => 'DESC'
// );
// $blogs = get_posts($posts);

$posts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 3,
    'orderby' => 'date',
    'order' => 'DESC'
]);

$impact_stories = new WP_Query([
    'post_type' => 'impact-story',
    'posts_per_page' => 8,
    'orderby' => 'date',
    'order' => 'DESC'
]);

$partner_query = new WP_Query([
    'post_type' => 'partner',
    'posts_per_page' => -1,
]);

$logos = [];
if ($partner_query->have_posts()) {
    while ($partner_query->have_posts()) {
        $partner_query->the_post();
        $logos[] = [
            'name' => get_the_title(),
            'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
            'url' => get_field('partner_url'),
        ];
    }
    wp_reset_postdata();
}

$deadline_raw = get_field('application_deadline');
$apply_link   = get_field('application_link');

?>

<section id="hero">
    <div class="page-container">
        <div class="flex flex-col gap-xl">
            <div class="flex flex-col items-center justify-center gap-sm py-lg">
                <div class="w-full">
                    <div class="flex flex-col items-center gap-y-7">
                        <div class="flex flex-col items-center gap-sm sm:px-7 text-center">
                            <div class="">
                                <div class="mb-4 flex align-middle text-center items-center justify-center">
                                    <?php echo do_shortcode('[application_badge]'); ?>
                                </div>
                                <h1 class="text-balance sm:!text-[3.5rem]">Funding Bright Futures in <br class="inline sm:block"> Global Education</h1>
                            </div>
                            <p class="mx-auto sm:max-w-[536px] text-balance font-normal">Supporting talented graduates from developing countries with test fees and application costs.</p>
                        </div>
                        <div class="flex flex-col md:flex-row justify-between gap-6">
                            <a class="button main-btn" href="/apply">
                                <p class="text-nowrap leading-none">Apply for Scholarship</p>
                            </a>
                            <a class="button secondary-btn" href="/support">
                                <p class=" text-nowrap leading-none">Support Our Mission</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="quick-links" class="bg-mt-cream">
    <div class="page-container space-y-28">
        <div class="grid sm:grid-cols-1 xl:grid-cols-3 gap-12 align-middle">
            <div class="flex flex-col gap-4 align-middle">
                <div class="flex flex-col gap-4">
                    <div class="border-[0.5px] border-gray-50 col-span-1 p-1 shadow-sm rounded-md h-full bg-gray-50/20">
                        <div class="overflow-hidden rounded-md aspect-video bg-gray-100 w-full h-full transition-all duration-300">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/imgs/mt-1.png'); ?>" alt="Our global impact" loading="lazy" class="w-full object-cover h-full" />
                        </div>
                    </div>
                    <p class="font-sans text-2xl">MT Scholarship</p>
                    <p class="opacity-80">Transforming academic potential into global opportunity.</p>
                    <ul>
                        <li><a href="<?php echo get_page_url_by_title('About') ?>">Our Mission</a></li>
                        <li><a href="<?php echo get_post_type_archive_link('report') ?>">Impact Reports</a></li>
                        <li><a href="<?php echo get_page_url_by_title('Volunteer') ?>">Become a Volunteer</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col gap-4 align-middle">
                <div class="flex flex-col gap-4">
                    <div class="border-[0.5px] border-gray-50 col-span-1 p-1 shadow-sm rounded-md h-full bg-gray-50/20">
                        <div class="overflow-hidden rounded-md aspect-video bg-gray-100 w-full h-full transition-all duration-300">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/imgs/mt-2.jpg'); ?>" alt="Our global impact" loading="lazy" class="w-full object-cover h-full" />
                        </div>
                    </div>
                    <p class="font-sans text-2xl">Apply</p>
                    <p class="opacity-80">Complete our application to receive funding and expert mentorship guidance.</p>
                    <ul>
                        <li><a href="<?php echo get_page_url_by_title('Eligibility') ?>">Eligibility</a></li>
                        <li><a href="<?php echo get_page_url_by_title('Timeline') ?>">Timeline</a></li>
                        <li><a href="<?php echo get_page_url_by_title('How to apply') ?>">How to apply</a></li>
                        <li><a href="<?php echo get_page_url_by_title('How we select') ?>">How we select</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col gap-4 align-middle">
                <div class="flex flex-col gap-4">
                    <div class="border-[0.5px] border-gray-50 col-span-1 p-1 shadow-sm rounded-md h-full bg-gray-50/20">
                        <div class="overflow-hidden rounded-md aspect-video bg-gray-100 w-full h-full transition-all duration-300">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/imgs/mt-3.jpg'); ?>" alt="Our global impact" loading="lazy" class="w-full object-cover h-full" />
                        </div>
                    </div>
                    <p class="font-sans text-2xl">Our Scholars</p>
                    <p class="opacity-80">Discover success stories of graduates building futures through education.</p>
                    <ul>
                        <li><a href="<?php echo get_post_type_archive_link('winner') ?>">Winners</a></li>
                        <li><a href="<?php echo get_post_type_archive_link('impact-story') ?>">Stories of Impact</a></li>
                        <li><a href="<?php echo get_page_url_by_title('Scholar Network') ?>">Scholar Network</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="impact">
    <div class="page-container space-y-28">
        <div class="grid sm:grid-cols-2 gap-12 sm:gap-24 align-middle items-center">
            <div class="order-2 md:order-1 flex flex-col gap-6">
                <h2 class="sm:line-clamp-1">Our Impact</h2>
                <p class="sm:line-clamp-2">Transforming financial barriers into global opportunities, one scholar at a time, through funding, mentorship, and lifelong connections.</p>
                <div class="flex flex-col md:flex-row gap-6">
                    <a class="button main-btn" href="<?php echo get_page_url_by_title('1% Challenge') ?>">
                        <p class="text-nowrap leading-none">The 1% Challenge</p>
                    </a>
                    <a class="button secondary-btn" href="<?php echo get_post_type_archive_link('report') ?>">
                        <p class=" text-nowrap leading-none">Annual Reports</p>
                    </a>
                </div>
            </div>
            <div class="order-1 md:order-2">
                <div class="border-[0.5px] border-gray-50 md:m-2 col-span-1 p-2 shadow-md rounded-2xl h-full bg-gray-50/20">
                    <div class="overflow-hidden rounded-2xl aspect-video bg-gray-100 w-full h-full transition-all duration-300">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/imgs/coverage.png'); ?>" alt="Our global impact" loading="lazy" class="w-full object-over h-full" />
                    </div>
                </div>
            </div>
        </div>
        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-8 align-middle">
            <div class="flex flex-col gap-4 align-middle">
                <div class="flex flex-col">
                    <h4 class="font-semibold text-2xl mb-2">43,000+</h4>
                    <p class="font-bold text-md">Applicants Worldwide</p>
                    <p class="line-clamp-3 opacity-80">From over 65 countries across 5 continents.</p>
                </div>
            </div>
            <div class="flex flex-col gap-4 align-middle">
                <div class="flex flex-col">
                    <h4 class="font-semibold text-2xl mb-2">230+</h4>
                    <p class="font-bold text-md">Scholars Supported</p>
                    <p class="line-clamp-3 opacity-80">Breaking barriers to global education.</p>
                </div>
            </div>
            <div class="flex flex-col gap-4 align-middle">
                <div class="flex flex-col">
                    <h4 class="font-semibold text-2xl mb-2">$5,000,000+</h4>
                    <p class="font-bold text-md">Total Scholarship Funding</p>
                    <p class="line-clamp-3 opacity-80">Invested directly in future global leaders.</p>
                </div>
            </div>
            <div class="flex flex-col gap-4 align-middle">
                <div class="flex flex-col">
                    <h4 class="font-semibold text-2xl mb-2">500+</h4>
                    <p class="font-bold text-md">Graduate School Acceptances</p>
                    <p class="line-clamp-3 opacity-80">Across top universities worldwide.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="about-us">
    <img class="w-full h-[16rem] sm:h-[30rem] object-top overflow-y-scroll object-cover rounded-t-2xl" src="<?php echo esc_url(get_template_directory_uri() . '/imgs/michael-taiwo-scholarship.jpg'); ?>" alt="">
    <div class="page-container -mt-24 pt-0 rounded-t-lg bg-white relative space-y-28">
        <div class="pt-6 sm:p-16">
            <h2 class="">About MT Scholarships</h2>
            <div class="items-center align-middle gap-4">
                <div class="grid sm:grid-cols-2 gap-10">
                    <div class="flex justify-between flex-col gap-10">
                        <div class="flex flex-col gap-6">
                            <p class="max-w-lg">The Michael Taiwo Scholarship supports exceptional students from developing countries take the first step toward graduate studies abroad by removing the biggest barriers—cost and access.</p>
                            <p class="max-w-lg">Founded by Dr. Michael Taiwo, our scholarship covers standardized test fees, graduate school applications, and credential evaluations. But we don’t stop at funding—we match each scholar with mentors who guide them through the competitive admissions process.</p>
                            <p class="max-w-lg">By combining financial support with mentorship, we open doors to top universities and global opportunities for students who might otherwise be left behind.</p>
                        </div>
                        <div class="flex flex-col md:flex-row gap-6">
                            <a class="button main-btn" href="<?php echo get_page_url_by_title('About') ?>">
                                <p class=" text-nowrap leading-none">Learn more</p>
                            </a>
                            <a class="button secondary-btn" href="<?php echo get_page_url_by_title('Apply') ?>">
                                <p class="text-nowrap leading-none">Apply for Scholarship</p>
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <ul class="space-y-10">
                            <li><span class="font-sans block mb-2 font-medium">Mission</span>To support high-potential students from developing countries in accessing global graduate education through funding and mentorship.</li>
                            <li class="border-y border-gray-200 py-10"><span class="font-sans block mb-2 font-medium">Vision</span>A world where no talented student is held back from advanced education due to financial or systemic barriers.</li>
                            <li><span class="font-sans block mb-2 font-medium">Impact</span>Since 2019, we’ve empowered hundreds of low-income scholars to gain admission into top graduate programs around the world—sparking lifelong change in their careers, families, and communities.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section id="impack-stories" class="overflow-hidden py-32 bg-mt-cream" x-data="impactStoriesCarousel()">
    <div class="px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:max-w-6xl">
            <div>
                <h2 class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase data-dark:text-gray-400">Unlocking Minds - Changing Worlds</h2>
                <h3 class="mt-2 text-4xl text-gray-950 data-dark:text-white sm:text-5xl">Stories of Impact</h3>
            </div>
        </div>
    </div>

    <?php if ($impact_stories->have_posts()) : ?>
        <div class="mt-16 flex gap-8 px-[var(--scroll-padding)] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden snap-x snap-mandatory overflow-x-auto overscroll-x-contain scroll-smooth [--scroll-padding:max(--spacing(6),calc((100vw-(var(--container-2xl)))/2)] lg:[--scroll-padding:max(--spacing(8),calc((100vw-(var(--container-6xl)))/2))]"
            x-ref="carousel">
            <?php while ($impact_stories->have_posts()) : $impact_stories->the_post();
                $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $testimonial = get_field('short_quote'); //get_the_content();
                $name = get_the_title();
                $position = get_field('position');
                $short_quote = get_field('short_quote');
                $university = get_field('university');
                $country = get_field('country');
                $post_link = get_permalink();
            ?>
                <a href="<?php echo esc_url($post_link); ?>" class="relative flex aspect-9/16 w-72 shrink-0 snap-start scroll-ml-[var(--scroll-padding)] flex-col justify-end overflow-hidden rounded-xl sm:aspect-3/4 sm:w-86 group transition-transform duration-300 hover:z-10 hover:shadow-xl">
                    <?php if ($image) : ?>
                        <div class="absolute inset-x-0 top-0 aspect-square w-full overflow-hidden">
                            <img alt="<?php echo esc_attr($name); ?>"
                                src="<?php echo esc_url($image); ?>"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105">
                        </div>
                    <?php endif; ?>
                    <div aria-hidden="true" class="absolute inset-0 rounded-xl bg-linear-to-t from-black from-[calc(7/16*100%)] ring-1 ring-gray-950/10 ring-inset sm:from-25%"></div>
                    <figure class="relative p-6">
                        <blockquote>
                            <p class="relative text-lg/5 text-white line-clamp-3">
                                <span aria-hidden="true" class="absolute -translate-x-full">"</span>
                                <?php echo esc_html($testimonial); ?>
                                <span aria-hidden="true" class="absolute">"</span>
                            </p>
                        </blockquote>
                        <figcaption class="mt-6 border-t border-white/20 pt-6">
                            <p class="text-md/6 font-mt font-medium text-white line-clamp-1"><?php echo esc_html($name); ?></p>
                            <p class="text-sm/6 font-medium">
                                <span class="fancy-text">
                                    <?php if ($university) echo esc_html($university) . ','; ?>
                                </span>
                                <span class="text-mt-cream"><?php echo esc_html($country); ?></span>
                            </p>
                        </figcaption>
                    </figure>
                </a>
            <?php endwhile;
            wp_reset_postdata(); ?>
            <div class="w-[42rem] shrink-0 sm:w-[54rem]"></div>
        </div>

        <div class="mt-16 px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:max-w-6xl">
                <div class="flex justify-between">
                    <div>
                        <p class="max-w-sm text-gray-600">Read more inspiring stories from our community.</p>
                        <div class="mt-2">
                            <a class="inline-flex items-center gap-2 font-medium text-mt-blue" href="<?php echo get_post_type_archive_link('impact-story'); ?>">
                                View all stories
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon" class="size-5">
                                    <path fill-rule="evenodd" d="M2 10a.75.75 0 0 1 .75-.75h12.59l-2.1-1.95a.75.75 0 1 1 1.02-1.1l3.5 3.25a.75.75 0 0 1 0 1.1l-3.5 3.25a.75.75 0 1 1-1.02-1.1l2.1-1.95H2.75A.75.75 0 0 1 2 10Z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:gap-2">
                        <?php
                        // Calculate number of navigation dots based on 3 items per view
                        $total_groups = ceil($impact_stories->post_count / 3);
                        for ($i = 0; $i < $total_groups; $i++) : ?>
                            <button
                                @click="goToGroup(<?php echo $i; ?>)"
                                aria-label="Scroll to group <?php echo $i + 1; ?>"
                                class="size-2.5 rounded-full border border-transparent bg-gray-300 transition hover:bg-gray-400"
                                :class="{ 'bg-gray-600': activeGroup === <?php echo $i; ?> }"
                                type="button">
                            </button>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>


<!-- Partners & Clients -->
<section id="partners">
    <div class="page-container border-b-[1px] border-gray-200">
        <div class="grid grid-cols-1 items-center gap-x-8 gap-y-16 lg:grid-cols-2">
            <div class="flex flex-col justify-between mx-auto w-full max-w-xl lg:mx-0">
                <h2 class="">Our Partners</h2>
                <p class="">It takes a village—and our village is extraordinary. Every scholarship, every success story, every thank-you letter started with a partner who believed.</p>
                <p class="mt-4">Thank you for turning potential into purpose.</p>
                <div class="mt-8 flex items-center gap-x-6">
                    <a class="button main-btn" href="<?php echo get_page_url_by_title('1% Challenge') ?>">
                        <p class="text-nowrap leading-none">Join The 1% Challenge</p>
                    </a>
                </div>
            </div>

            <div class="-mx-6 grid grid-cols-2 gap-0.5 overflow-hidden sm:mx-0 sm:rounded-2xl md:grid-cols-2">
                <?php foreach ($logos as $index => $l) : ?>
                    <div class="bg-gray-400/5 p-8 sm:p-10">
                        <img class="max-h-12 w-full object-contain" src="<?php echo $l['image'] ?>" alt="<?php echo $l['name'] ?>" width="158" height="48">
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <div class="page-container hidden py-0 xl:pb-0 xl:pt-16">
        <div class="a-slider" style="
            --width: 160px;
            --height: 120px;
            --quantity: <?php echo count($logos) ?>;
        ">
            <div class="list">
                <?php foreach ($logos as $index => $l) : ?>
                    <div class="item" style="--position: <?php echo $index + 1; ?>">
                        <a href="<?php echo $l['url'] ?>" target="_blank">
                            <img class="grayscale object-contain" src="<?php echo $l['image'] ?>" alt="<?php echo $l['name'] ?>">
                        </a>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div class="sm:text-center text-center hidden">
            <div class="p-logos grid grid-cols-3 gap-3 sm:gap-6 md:grid-cols-9">
                <?php
                foreach ($logos as $l) : ?>
                    <a href="<?php echo $l['url'] ?>" target="_blank" class="group col-span-1 sm:flex sm:justify-center ">
                        <img class="h-16 mx-auto w-full sm:max-h-36 grayscale duration-300 transition-all delay-50 scale-100 hover:scale-105 object-contain" src="<?php echo $l['image'] ?>" alt="<?php echo $l['name'] ?>">
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>


<section id="news" class="">
    <div class="page-container">
        <div>
            <h2 class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase data-dark:text-gray-400">Our Blog</h2>
            <h3 class="mt-2 text-4xl font-medium tracking-tighter text-pretty text-gray-950 data-dark:text-white sm:text-5xl">The Dream Lounge</h3>
        </div>

        <?php if ($posts->have_posts()) : ?>
            <div class="mt-16 grid sm:grid-cols-3 gap-4">
                <?php while ($posts->have_posts()) : $posts->the_post();
                    $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    $post_link = get_permalink();
                    $post_title = get_the_title();
                    $post_date = get_the_date();
                    $author_id = get_the_author_meta('ID');
                    $categories = get_the_category();

                    $author_avatar = get_avatar_url($author_id, ['size' => 96]);
                    $author_name = get_the_author();

                    $author_image = get_field('profile-image', 'user_' . $author_id);
                    $first_name = get_user_meta($author_id, 'first_name', true);
                    $last_name = get_user_meta($author_id, 'last_name', true);

                    $author_full_name = trim($first_name . ' ' . $last_name);

                ?>
                    <article class="flex flex-col rounded-3xl bg-white shadow-md ring-1 shadow-black/5 ring-black/5 hover:shadow-xl transition-shadow duration-300 select-text overflow-hidden">
                        <!-- Featured Image -->
                        <div class="relative overflow-hidden rounded-t-3xl">
                            <?php if ($image) : ?>
                                <img
                                    src="<?php echo esc_url($image); ?>"
                                    alt="<?php echo esc_attr($post_title); ?>"
                                    class="aspect-[3/2] w-full object-cover transition-transform duration-300 hover:scale-105">
                            <?php endif; ?>

                            <!-- Category Badge -->
                            <?php if (!empty($categories)) : ?>
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-600/90 backdrop-blur-sm text-white">
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content -->
                        <div class="flex flex-1 flex-col justify-between p-6">
                            <!-- Title -->
                            <div class="flex-1">
                                <h3 class="text-lg line-clamp-2 font-semibold text-gray-900 mb-4 leading-tight select-text">
                                    <a href="<?php echo esc_url($post_link); ?>" class="hover:text-green-600 transition-colors duration-200 relative z-10">
                                        <?php echo esc_html($post_title); ?>
                                    </a>
                                </h3>
                            </div>

                            <!-- Author and Date -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-3">
                                    <img
                                        src="<?php echo esc_url($author_image); ?>"
                                        alt="<?php echo esc_attr($author_full_name); ?>"
                                        class="w-8 h-8 rounded-full object-cover">
                                    <div class="select-text">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo esc_html($author_full_name); ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo esc_html($post_date); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Read More Link -->
                                <a href="<?php echo esc_url($post_link); ?>" class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors duration-200 relative z-10">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<section id="newsletter">
    <div class="page-container border-t-[1px] border-black/10 md:pb-0">
        <div class="grid sm:grid-cols-2 gap-8">
            <div class="mb-8">
                <h4>Connect. Grow. Inspire — Join Dream Lounge</h4>
                <p>Stay connected to the heartbeat of our community with Dream Lounge, a thoughtfully curated newsletter that illuminates the paths of achievers, offers practical wisdom, and connects you with a global tribe of dreamers.</p>
                <div class="max-w-2xl mx-auto mt-6 contact-form bg-mt-cream p-6 sm:p-8 rounded-xl shadow-md">
                    <?php echo do_shortcode('[sibwp_form id=1]'); ?>
                </div>
            </div>
            <div class="hidden md:block">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/imgs/mt-newsletter.png'); ?>" alt="Subscribe to our Newsletter" class="w-full h-full object-contain">
            </div>
        </div>
    </div>
</section>

<section id="faqs" class="bg-mt-cream">
    <div class="page-container space-y-28">
        <div class="grid sm:grid-cols-1 xl:grid-cols-2 gap-12 align-middle">
            <div class="flex flex-col gap-4 align-middle">
                <div class="flex flex-col gap-4">
                    <h2 class="">FAQs</h2>
                    <p class="opacity-80">Transforming academic potential into global opportunity.</p>
                </div>
            </div>
            <div class="group">
                <h3 class="px-4 py-2 sm:px-2 font-mono text-[0.8125rem]/6 font-medium tracking-widest text-pretty uppercase text-gray-600">General Questions</h3>
                <dl>
                    <details class="group border-t border-gray-950/5 px-4 py-3 sm:px-2">
                        <summary id="faq-are-figma-sketch-or-adobe-xd-files-included" class="flex w-full cursor-pointer justify-between gap-4 select-none group-open:text-mt-blue [&amp;::-webkit-details-marker]:hidden">
                            <div class="text-left text-sm/7 font-semibold text-pretty">Who do we serve and how?</div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 group-open:hidden">
                                <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 not-group-open:hidden">
                                <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z"></path>
                            </svg>
                        </summary>
                        <div class="mt-4 grid grid-cols-1 gap-6 text-sm/7 text-gray-600 [&amp;_strong]:font-semibold [&amp;_strong]:text-gray-950 [&amp;_h2]:text-base/7 [&amp;_h2]:font-semibold [&amp;_h2]:text-gray-950 [&amp;_h3]:font-semibold [&amp;_h3]:text-gray-950 [&amp;_a]:font-semibold [&amp;_a]:text-gray-950 [&amp;_a]:underline [&amp;_a]:decoration-sky-400 [&amp;_a]:underline-offset-4 [&amp;_a]:hover:text-mt-blue [&amp;_li]:relative [&amp;_li]:before:absolute [&amp;_li]:before:-top-0.5 [&amp;_li]:before:-left-6 [&amp;_li]:before:text-gray-300 [&amp;_li]:before:content-[&quot;▪&quot;] [&amp;_ul]:pl-9 [&amp;_pre]:overflow-x-auto [&amp;_pre]:rounded-xl [&amp;_pre]:border-4 [&amp;_pre]:border-gray-950 [&amp;_pre]:bg-gray-900 [&amp;_pre]:p-4 [&amp;_pre]:text-white [&amp;_pre]:outline-1 [&amp;_pre]:-outline-offset-5 [&amp;_pre]:outline-white/10 [&amp;_pre_code]:bg-gray-900 [&amp;_code]:not-in-[pre]:font-medium [&amp;_code]:not-in-[pre]:text-gray-950 [&amp;_code]:not-in-[pre]:before:content-[&quot;\`&quot;] [&amp;_code]:not-in-[pre]:after:content-[&quot;\`&quot;]">
                            <p>We serve high achieving low income students in developing countries who are seeking post baccalaureate studies. The award typically goes to recent college graduates.</p>
                            <p>We cover the cost of standard exams, application fees and credential evaluation for these students. This typically averages about $1,000 per student. Coming up with a thousand US dollars is a steep hill to climb for many students in a developing country!</p>
                        </div>
                    </details>
                    <details class="group border-t border-gray-950/5 px-4 py-3 sm:px-2">
                        <summary id="faq-what-js-framework-is-used" class="flex w-full cursor-pointer justify-between gap-4 select-none group-open:text-mt-blue [&amp;::-webkit-details-marker]:hidden">
                            <div class="text-left text-sm/7 font-semibold text-pretty">How do you select award recipients?</div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 group-open:hidden">
                                <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 not-group-open:hidden">
                                <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z"></path>
                            </svg>
                        </summary>
                        <div class="mt-4 grid grid-cols-1 gap-6 text-sm/7 text-gray-600 [&amp;_strong]:font-semibold [&amp;_strong]:text-gray-950 [&amp;_h2]:text-base/7 [&amp;_h2]:font-semibold [&amp;_h2]:text-gray-950 [&amp;_h3]:font-semibold [&amp;_h3]:text-gray-950 [&amp;_a]:font-semibold [&amp;_a]:text-gray-950 [&amp;_a]:underline [&amp;_a]:decoration-sky-400 [&amp;_a]:underline-offset-4 [&amp;_a]:hover:text-mt-blue [&amp;_li]:relative [&amp;_li]:before:absolute [&amp;_li]:before:-top-0.5 [&amp;_li]:before:-left-6 [&amp;_li]:before:text-gray-300 [&amp;_li]:before:content-[&quot;▪&quot;] [&amp;_ul]:pl-9 [&amp;_pre]:overflow-x-auto [&amp;_pre]:rounded-xl [&amp;_pre]:border-4 [&amp;_pre]:border-gray-950 [&amp;_pre]:bg-gray-900 [&amp;_pre]:p-4 [&amp;_pre]:text-white [&amp;_pre]:outline-1 [&amp;_pre]:-outline-offset-5 [&amp;_pre]:outline-white/10 [&amp;_pre_code]:bg-gray-900 [&amp;_code]:not-in-[pre]:font-medium [&amp;_code]:not-in-[pre]:text-gray-950 [&amp;_code]:not-in-[pre]:before:content-[&quot;\`&quot;] [&amp;_code]:not-in-[pre]:after:content-[&quot;\`&quot;]">
                            <p>We mirror the holistic process favored by many graduate school admission committees. The GPA of the students, of course, is a strong consideration. But we look at other factors as well such as indigency, drive, leadership potential and reasons for applying.</p>
                            <p>A strong consideration is also diversity. We aim to have a broad spectrum of students across a wide range of demographics represented because part of the experience of being an MT Scholar is having those with dissimilar backgrounds interact with one another.</p>
                        </div>
                    </details>
                    <details class="group border-t border-gray-950/5 px-4 py-3 sm:px-2">
                        <summary id="faq-what-version-of-tailwind-css-is-used" class="flex w-full cursor-pointer justify-between gap-4 select-none group-open:text-mt-blue [&amp;::-webkit-details-marker]:hidden">
                            <div class="text-left text-sm/7 font-semibold text-pretty">Why did you start the Michael Taiwo Scholarship?</div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 group-open:hidden">
                                <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 not-group-open:hidden">
                                <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z"></path>
                            </svg>
                        </summary>
                        <div class="mt-4 grid grid-cols-1 gap-6 text-sm/7 text-gray-600 [&amp;_strong]:font-semibold [&amp;_strong]:text-gray-950 [&amp;_h2]:text-base/7 [&amp;_h2]:font-semibold [&amp;_h2]:text-gray-950 [&amp;_h3]:font-semibold [&amp;_h3]:text-gray-950 [&amp;_a]:font-semibold [&amp;_a]:text-gray-950 [&amp;_a]:underline [&amp;_a]:decoration-sky-400 [&amp;_a]:underline-offset-4 [&amp;_a]:hover:text-mt-blue [&amp;_li]:relative [&amp;_li]:before:absolute [&amp;_li]:before:-top-0.5 [&amp;_li]:before:-left-6 [&amp;_li]:before:text-gray-300 [&amp;_li]:before:content-[&quot;▪&quot;] [&amp;_ul]:pl-9 [&amp;_pre]:overflow-x-auto [&amp;_pre]:rounded-xl [&amp;_pre]:border-4 [&amp;_pre]:border-gray-950 [&amp;_pre]:bg-gray-900 [&amp;_pre]:p-4 [&amp;_pre]:text-white [&amp;_pre]:outline-1 [&amp;_pre]:-outline-offset-5 [&amp;_pre]:outline-white/10 [&amp;_pre_code]:bg-gray-900 [&amp;_code]:not-in-[pre]:font-medium [&amp;_code]:not-in-[pre]:text-gray-950 [&amp;_code]:not-in-[pre]:before:content-[&quot;\`&quot;] [&amp;_code]:not-in-[pre]:after:content-[&quot;\`&quot;]">
                            <p>I started this scholarship to give a younger Michael Taiwo the type of opportunity he would have killed for. In 2005 when I was looking for how to get into graduate programs in developed countries, I couldn’t come up with the money to pay for the tests and school application fees.</p>
                            <p>And when I found the money, it was even more challenging to pay, in part because of lack of trust in online financial transactions from developing countries. Finally, I did not know enough about the whole admissions process which meant I made some costly mistakes. In short, the Michael Taiwo Scholarship was founded to solve the problems a younger Michael Taiwo faced at a pivotal point in his development.</p>
                        </div>
                    </details>
                    <details class="group border-t border-gray-950/5 px-4 py-3 sm:px-2">
                        <summary id="faq-what-browsers-are-supported" class="flex w-full cursor-pointer justify-between gap-4 select-none group-open:text-mt-blue [&amp;::-webkit-details-marker]:hidden">
                            <div class="text-left text-sm/7 font-semibold text-pretty">What are your plans for this programme?</div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 group-open:hidden">
                                <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 not-group-open:hidden">
                                <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z"></path>
                            </svg>
                        </summary>
                        <div class="mt-4 grid grid-cols-1 gap-6 text-sm/7 text-gray-600 [&amp;_strong]:font-semibold [&amp;_strong]:text-gray-950 [&amp;_h2]:text-base/7 [&amp;_h2]:font-semibold [&amp;_h2]:text-gray-950 [&amp;_h3]:font-semibold [&amp;_h3]:text-gray-950 [&amp;_a]:font-semibold [&amp;_a]:text-gray-950 [&amp;_a]:underline [&amp;_a]:decoration-sky-400 [&amp;_a]:underline-offset-4 [&amp;_a]:hover:text-mt-blue [&amp;_li]:relative [&amp;_li]:before:absolute [&amp;_li]:before:-top-0.5 [&amp;_li]:before:-left-6 [&amp;_li]:before:text-gray-300 [&amp;_li]:before:content-[&quot;▪&quot;] [&amp;_ul]:pl-9 [&amp;_pre]:overflow-x-auto [&amp;_pre]:rounded-xl [&amp;_pre]:border-4 [&amp;_pre]:border-gray-950 [&amp;_pre]:bg-gray-900 [&amp;_pre]:p-4 [&amp;_pre]:text-white [&amp;_pre]:outline-1 [&amp;_pre]:-outline-offset-5 [&amp;_pre]:outline-white/10 [&amp;_pre_code]:bg-gray-900 [&amp;_code]:not-in-[pre]:font-medium [&amp;_code]:not-in-[pre]:text-gray-950 [&amp;_code]:not-in-[pre]:before:content-[&quot;\`&quot;] [&amp;_code]:not-in-[pre]:after:content-[&quot;\`&quot;]">
                            <p>We started in 2019 by just paying for the GRE exams because we believe the GRE is a key factor in securing fully funded graduate admissions. Today, it has developed to encompass all the typical fees a student will need to pay during this process including, English language tests, school application fees, additional score reports and transcript evaluation fees. We plan to keep increasing the award package as well as the number of beneficiaries each year, subject to funds availability.</p>
                        </div>
                    </details>
                    <details class="group border-t border-gray-950/5 px-4 py-3 sm:px-2">
                        <summary id="faq-what-browsers-are-supported" class="flex w-full cursor-pointer justify-between gap-4 select-none group-open:text-mt-blue [&amp;::-webkit-details-marker]:hidden">
                            <div class="text-left text-sm/7 font-semibold text-pretty">How do you define or measure impact?</div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 group-open:hidden">
                                <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 not-group-open:hidden">
                                <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z"></path>
                            </svg>
                        </summary>
                        <div class="mt-4 grid grid-cols-1 gap-6 text-sm/7 text-gray-600 [&amp;_strong]:font-semibold [&amp;_strong]:text-gray-950 [&amp;_h2]:text-base/7 [&amp;_h2]:font-semibold [&amp;_h2]:text-gray-950 [&amp;_h3]:font-semibold [&amp;_h3]:text-gray-950 [&amp;_a]:font-semibold [&amp;_a]:text-gray-950 [&amp;_a]:underline [&amp;_a]:decoration-sky-400 [&amp;_a]:underline-offset-4 [&amp;_a]:hover:text-mt-blue [&amp;_li]:relative [&amp;_li]:before:absolute [&amp;_li]:before:-top-0.5 [&amp;_li]:before:-left-6 [&amp;_li]:before:text-gray-300 [&amp;_li]:before:content-[&quot;▪&quot;] [&amp;_ul]:pl-9 [&amp;_pre]:overflow-x-auto [&amp;_pre]:rounded-xl [&amp;_pre]:border-4 [&amp;_pre]:border-gray-950 [&amp;_pre]:bg-gray-900 [&amp;_pre]:p-4 [&amp;_pre]:text-white [&amp;_pre]:outline-1 [&amp;_pre]:-outline-offset-5 [&amp;_pre]:outline-white/10 [&amp;_pre_code]:bg-gray-900 [&amp;_code]:not-in-[pre]:font-medium [&amp;_code]:not-in-[pre]:text-gray-950 [&amp;_code]:not-in-[pre]:before:content-[&quot;\`&quot;] [&amp;_code]:not-in-[pre]:after:content-[&quot;\`&quot;]">
                            <p>We started with the goal of just giving a few students money for their GRE exams. As such, we have gone way past our goal! Now, we want to reach more students with more comprehensive award packages. We want to ensure the students have a successful graduate school experience and that they land their dream jobs.</p>
                            <p>Since our inception, other organizations have started with a similar vision, many credit us for inspiring them to do this. We are grateful to start this.</p>
                            <p>Ultimately, our impact would be measured in how many lives have been changed because we existed.</p>
                        </div>
                    </details>
                    <details class="group border-t border-gray-950/5 px-4 py-3 sm:px-2">
                        <summary id="faq-what-browsers-are-supported" class="flex w-full cursor-pointer justify-between gap-4 select-none group-open:text-mt-blue [&amp;::-webkit-details-marker]:hidden">
                            <div class="text-left text-sm/7 font-semibold text-pretty">When does the application open?</div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 group-open:hidden">
                                <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-7 w-4 not-group-open:hidden">
                                <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z"></path>
                            </svg>
                        </summary>
                        <div class="mt-4 grid grid-cols-1 gap-6 text-sm/7 text-gray-600 [&amp;_strong]:font-semibold [&amp;_strong]:text-gray-950 [&amp;_h2]:text-base/7 [&amp;_h2]:font-semibold [&amp;_h2]:text-gray-950 [&amp;_h3]:font-semibold [&amp;_h3]:text-gray-950 [&amp;_a]:font-semibold [&amp;_a]:text-gray-950 [&amp;_a]:underline [&amp;_a]:decoration-sky-400 [&amp;_a]:underline-offset-4 [&amp;_a]:hover:text-mt-blue [&amp;_li]:relative [&amp;_li]:before:absolute [&amp;_li]:before:-top-0.5 [&amp;_li]:before:-left-6 [&amp;_li]:before:text-gray-300 [&amp;_li]:before:content-[&quot;▪&quot;] [&amp;_ul]:pl-9 [&amp;_pre]:overflow-x-auto [&amp;_pre]:rounded-xl [&amp;_pre]:border-4 [&amp;_pre]:border-gray-950 [&amp;_pre]:bg-gray-900 [&amp;_pre]:p-4 [&amp;_pre]:text-white [&amp;_pre]:outline-1 [&amp;_pre]:-outline-offset-5 [&amp;_pre]:outline-white/10 [&amp;_pre_code]:bg-gray-900 [&amp;_code]:not-in-[pre]:font-medium [&amp;_code]:not-in-[pre]:text-gray-950 [&amp;_code]:not-in-[pre]:before:content-[&quot;\`&quot;] [&amp;_code]:not-in-[pre]:after:content-[&quot;\`&quot;]">
                            <p>For 2025, the application opens on June 16th and closes on June 27th. For 2026 and beyond, the application window would be announced several months in advance so that students can adequately prepare.</p>
                        </div>
                    </details>
                </dl>
            </div>
        </div>
    </div>
</section>



<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('impactStoriesCarousel', () => ({
            activeGroup: 0,
            itemsPerView: 3,
            carousel: null,

            init() {
                this.carousel = this.$refs.carousel;
                this.setupIntersectionObserver();
                this.handleResponsive();
                window.addEventListener('resize', this.handleResponsive.bind(this));

                // Prevent card clicks while dragging
                let isDragging = false;
                let startX = 0;

                this.carousel.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    startX = e.clientX;
                });

                this.carousel.addEventListener('mousemove', (e) => {
                    if (isDragging && Math.abs(e.clientX - startX) > 5) {
                        isDragging = true;
                    }
                });

                this.carousel.addEventListener('mouseup', (e) => {
                    isDragging = false;
                });

                this.carousel.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }, true);
            },

            handleResponsive() {
                // Always show 3 items in desktop, 1 on mobile
                this.itemsPerView = window.innerWidth < 768 ? 1 : 3;
            },

            setupIntersectionObserver() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const slideIndex = Array.from(this.carousel.children)
                                .filter(el => el.classList.contains('snap-start'))
                                .indexOf(entry.target);
                            if (slideIndex !== -1) {
                                this.activeGroup = Math.floor(slideIndex / this.itemsPerView);
                            }
                        }
                    });
                }, {
                    root: this.carousel,
                    threshold: 0.7
                });

                Array.from(this.carousel.children)
                    .filter(el => el.classList.contains('snap-start'))
                    .forEach(slide => observer.observe(slide));
            },

            goToGroup(groupIndex) {
                const slides = Array.from(this.carousel.children).filter(el => el.classList.contains('snap-start'));
                const targetIndex = groupIndex * this.itemsPerView;

                if (targetIndex >= 0 && targetIndex < slides.length) {
                    slides[targetIndex].scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'center'
                    });
                    this.activeGroup = groupIndex;
                }
            }
        }));
    });
</script>
<?php
get_footer();
