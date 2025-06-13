<?php

/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Michael_Taiwo_Scholarship
 */

// Get the custom logo
$custom_logo_id = get_theme_mod('custom_logo');
$logo_image = $custom_logo_id ? wp_get_attachment_image_src($custom_logo_id, 'full') : null;
// $logo_url = $logo_image ? esc_url($logo_image[0]) : '';
$logo_url = get_stylesheet_directory_uri() . "/imgs/Michael_Taiwo_Scholarship_white.png";
$site_name = get_bloginfo('name');
$site_url = home_url('/');

// Footer Menus
$first = wp_get_nav_menu_items('footer-1');
$second = wp_get_nav_menu_items('footer-2');
$third = wp_get_nav_menu_items('footer-3');
$privacy = wp_get_nav_menu_items('footer-privacy');

// Social Media URLs with default fallbacks
$twitter_url = get_theme_mod('social_twitter', 'https://twitter.com/MtScholarships');
$linkedin_url = get_theme_mod('social_linkedin', 'https://www.linkedin.com/company/mt');
$youtube_url = get_theme_mod('social_youtube', 'https://www.youtube.com/@mt');

// Year for copyright
$current_year = date('Y');
?>

<footer id="colophon" class="hidden">

	<?php if (is_active_sidebar('sidebar-1')) : ?>
		<aside role="complementary" aria-label="<?php esc_attr_e('Footer', 'michael-taiwo-scholarship'); ?>">
			<?php dynamic_sidebar('sidebar-1'); ?>
		</aside>
	<?php endif; ?>

	<?php if (has_nav_menu('menu-2')) : ?>
		<nav aria-label="<?php esc_attr_e('Footer Menu', 'michael-taiwo-scholarship'); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-2',
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
				)
			);
			?>
		</nav>
	<?php endif; ?>

	<div>
		<?php
		$mt_blog_info = get_bloginfo('name');
		if (! empty($mt_blog_info)) :
		?>
			<a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>,
		<?php
		endif;

		/* translators: 1: WordPress link, 2: WordPress. */
		printf(
			'<a href="%1$s">proudly powered by %2$s</a>.',
			esc_url(__('https://wordpress.org/', 'michael-taiwo-scholarship')),
			'WordPress'
		);
		?>
	</div>

</footer><!-- #colophon -->

<div class="page-container py-0">
		<div class="bg-mt-cream -mb-4 rounded-full flex flex-col text-center align-middle items-center justify-center">
			<?php echo do_shortcode('[application_badge]'); ?>
		</div>
	</div>
<footer class="bg-black/90 text-background footer pt-20 sm:pt-32 pb-6 px-4 md:px-0">
	<div class="max-w-6xl mx-auto">
		<div class="flex flex-col sm:grid sm:grid-cols-12 gap-4">
			<div class="sm:col-span-4">
				<a title="<?php echo esc_attr($site_name); ?>" class="gap-2 items-center align-middle inline-flex" href="<?php echo esc_url($site_url); ?>">
					<?php
					if (!empty($logo_url)) {
						echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr($site_name) . '" class="w-50 sm:w-75 inline-block" />';
					} else {
					?>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pyramid font-light size-8">
							<path d="M2.5 16.88a1 1 0 0 1-.32-1.43l9-13.02a1 1 0 0 1 1.64 0l9 13.01a1 1 0 0 1-.32 1.44l-8.51 4.86a2 2 0 0 1-1.98 0Z"></path>
							<path d="M12 2v20"></path>
						</svg>
					<?php } ?>
					<span class="font-bold text-xl hidden uppercase antialiased tracking-wider"><?php echo esc_html($site_name); ?></span>
				</a>
				<p class="py-8">The Michael Taiwo Scholarships Inc is  501(c)(3) nonprofit organization, EIN 88-1437535. Donations are tax-deductible.</p>
				<nav class="flex mt-12 md:mb-0 gap-6 align-baseline text-white/60">
					<?php
					// Twitter/X
					$twitter_url = get_theme_mod('social_twitter');
					if ($twitter_url) : ?>
						<a target="_blank" class="p-0 fill-white rounded-full transition hover:fill-green-700" href="<?php echo esc_url($twitter_url); ?>">
							<svg class="size-8" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
								<path d="M 5.9199219 6 L 20.582031 27.375 L 6.2304688 44 L 9.4101562 44 L 21.986328 29.421875 L 31.986328 44 L 44 44 L 28.681641 21.669922 L 42.199219 6 L 39.029297 6 L 27.275391 19.617188 L 17.933594 6 L 5.9199219 6 z M 9.7167969 8 L 16.880859 8 L 40.203125 42 L 33.039062 42 L 9.7167969 8 z"></path>
							</svg>
						</a>
					<?php endif;

					// LinkedIn
					$linkedin_url = get_theme_mod('social_linkedin');
					if ($linkedin_url) : ?>
						<a target="_blank" class="p-0 fill-white rounded-full transition hover:fill-green-700" href="<?php echo esc_url($linkedin_url); ?>">
							<svg class="size-8" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
								<path d="M 9 4 C 6.2504839 4 4 6.2504839 4 9 L 4 41 C 4 43.749516 6.2504839 46 9 46 L 41 46 C 43.749516 46 46 43.749516 46 41 L 46 9 C 46 6.2504839 43.749516 4 41 4 L 9 4 z M 9 6 L 41 6 C 42.668484 6 44 7.3315161 44 9 L 44 41 C 44 42.668484 42.668484 44 41 44 L 9 44 C 7.3315161 44 6 42.668484 6 41 L 6 9 C 6 7.3315161 7.3315161 6 9 6 z M 14 11.011719 C 12.904779 11.011719 11.919219 11.339079 11.189453 11.953125 C 10.459687 12.567171 10.011719 13.484511 10.011719 14.466797 C 10.011719 16.333977 11.631285 17.789609 13.691406 17.933594 A 0.98809878 0.98809878 0 0 0 13.695312 17.935547 A 0.98809878 0.98809878 0 0 0 14 17.988281 C 16.27301 17.988281 17.988281 16.396083 17.988281 14.466797 A 0.98809878 0.98809878 0 0 0 17.986328 14.414062 C 17.884577 12.513831 16.190443 11.011719 14 11.011719 z M 14 12.988281 C 15.392231 12.988281 15.94197 13.610038 16.001953 14.492188 C 15.989803 15.348434 15.460091 16.011719 14 16.011719 C 12.614594 16.011719 11.988281 15.302225 11.988281 14.466797 C 11.988281 14.049083 12.140703 13.734298 12.460938 13.464844 C 12.78117 13.19539 13.295221 12.988281 14 12.988281 z M 11 19 A 1.0001 1.0001 0 0 0 10 20 L 10 39 A 1.0001 1.0001 0 0 0 11 40 L 17 40 A 1.0001 1.0001 0 0 0 18 39 L 18 33.134766 L 18 20 A 1.0001 1.0001 0 0 0 17 19 L 11 19 z M 20 19 A 1.0001 1.0001 0 0 0 19 20 L 19 39 A 1.0001 1.0001 0 0 0 20 40 L 26 40 A 1.0001 1.0001 0 0 0 27 39 L 27 29 C 27 28.170333 27.226394 27.345035 27.625 26.804688 C 28.023606 26.264339 28.526466 25.940057 29.482422 25.957031 C 30.468166 25.973981 30.989999 26.311669 31.384766 26.841797 C 31.779532 27.371924 32 28.166667 32 29 L 32 39 A 1.0001 1.0001 0 0 0 33 40 L 39 40 A 1.0001 1.0001 0 0 0 40 39 L 40 28.261719 C 40 25.300181 39.122788 22.95433 37.619141 21.367188 C 36.115493 19.780044 34.024172 19 31.8125 19 C 29.710483 19 28.110853 19.704889 27 20.423828 L 27 20 A 1.0001 1.0001 0 0 0 26 19 L 20 19 z M 12 21 L 16 21 L 16 33.134766 L 16 38 L 12 38 L 12 21 z M 21 21 L 25 21 L 25 22.560547 A 1.0001 1.0001 0 0 0 26.798828 23.162109 C 26.798828 23.162109 28.369194 21 31.8125 21 C 33.565828 21 35.069366 21.582581 36.167969 22.742188 C 37.266572 23.901794 38 25.688257 38 28.261719 L 38 38 L 34 38 L 34 29 C 34 27.833333 33.720468 26.627107 32.990234 25.646484 C 32.260001 24.665862 31.031834 23.983076 29.517578 23.957031 C 27.995534 23.930001 26.747519 24.626988 26.015625 25.619141 C 25.283731 26.611293 25 27.829667 25 29 L 25 38 L 21 38 L 21 21 z"></path>
							</svg>
						</a>
					<?php endif;
					
					// Instagram
					$instagram_url = get_theme_mod('social_instagram');
					if ($instagram_url) : ?>
						<a target="_blank" class="p-0 fill-white rounded-full transition hover:fill-green-700" href="<?php echo esc_url($instagram_url); ?>">
							<svg class="size-8" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
								<path d="M 16 3 C 8.8324839 3 3 8.8324839 3 16 L 3 34 C 3 41.167516 8.8324839 47 16 47 L 34 47 C 41.167516 47 47 41.167516 47 34 L 47 16 C 47 8.8324839 41.167516 3 34 3 L 16 3 z M 16 5 L 34 5 C 40.086484 5 45 9.9135161 45 16 L 45 34 C 45 40.086484 40.086484 45 34 45 L 16 45 C 9.9135161 45 5 40.086484 5 34 L 5 16 C 5 9.9135161 9.9135161 5 16 5 z M 37 11 A 2 2 0 0 0 35 13 A 2 2 0 0 0 37 15 A 2 2 0 0 0 39 13 A 2 2 0 0 0 37 11 z M 25 14 C 18.936712 14 14 18.936712 14 25 C 14 31.063288 18.936712 36 25 36 C 31.063288 36 36 31.063288 36 25 C 36 18.936712 31.063288 14 25 14 z M 25 16 C 29.982407 16 34 20.017593 34 25 C 34 29.982407 29.982407 34 25 34 C 20.017593 34 16 29.982407 16 25 C 16 20.017593 20.017593 16 25 16 z"></path>
							</svg>
						</a>
					<?php endif;

					// YouTube
					$youtube_url = get_theme_mod('social_youtube');
					if ($youtube_url) : ?>
						<a target="_blank" class="p-0 fill-white rounded-full transition hover:fill-green-700" href="<?php echo esc_url($youtube_url); ?>">
							<svg class="size-8" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
								<path d="M 24.402344 9 C 17.800781 9 11.601563 9.5 8.300781 10.199219 C 6.101563 10.699219 4.199219 12.199219 3.800781 14.5 C 3.402344 16.898438 3 20.5 3 25 C 3 29.5 3.398438 33 3.898438 35.5 C 4.300781 37.699219 6.199219 39.300781 8.398438 39.800781 C 11.902344 40.5 17.898438 41 24.5 41 C 31.101563 41 37.097656 40.5 40.597656 39.800781 C 42.800781 39.300781 44.699219 37.800781 45.097656 35.5 C 45.5 33 46 29.402344 46.097656 24.902344 C 46.097656 20.402344 45.597656 16.800781 45.097656 14.300781 C 44.699219 12.101563 42.800781 10.5 40.597656 10 C 37.097656 9.5 31 9 24.402344 9 Z M 24.402344 11 C 31.601563 11 37.398438 11.597656 40.199219 12.097656 C 41.699219 12.5 42.898438 13.5 43.097656 14.800781 C 43.699219 18 44.097656 21.402344 44.097656 24.902344 C 44 29.199219 43.5 32.699219 43.097656 35.199219 C 42.800781 37.097656 40.800781 37.699219 40.199219 37.902344 C 36.597656 38.601563 30.597656 39.097656 24.597656 39.097656 C 18.597656 39.097656 12.5 38.699219 9 37.902344 C 7.5 37.5 6.300781 36.5 6.101563 35.199219 C 5.300781 32.398438 5 28.699219 5 25 C 5 20.398438 5.402344 17 5.800781 14.902344 C 6.101563 13 8.199219 12.398438 8.699219 12.199219 C 12 11.5 18.101563 11 24.402344 11 Z M 19 17 L 19 33 L 33 25 Z M 21 20.402344 L 29 25 L 21 29.597656 Z"></path>
							</svg>
						</a>
					<?php endif;
					
					// Facebook
					$facebook_url = get_theme_mod('social_facebook');
					if ($facebook_url) : ?>
						<a target="_blank" class="p-0 fill-white rounded-full transition hover:fill-green-700" href="<?php echo esc_url($facebook_url); ?>">
							<svg class="size-8" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
								<path d="M 25 3 C 12.861562 3 3 12.861562 3 25 C 3 36.019135 11.127533 45.138355 21.712891 46.728516 L 22.861328 46.902344 L 22.861328 29.566406 L 17.664062 29.566406 L 17.664062 26.046875 L 22.861328 26.046875 L 22.861328 21.373047 C 22.861328 18.494965 23.551973 16.599417 24.695312 15.410156 C 25.838652 14.220896 27.528004 13.621094 29.878906 13.621094 C 31.758714 13.621094 32.490022 13.734993 33.185547 13.820312 L 33.185547 16.701172 L 30.738281 16.701172 C 29.349697 16.701172 28.210449 17.475903 27.619141 18.507812 C 27.027832 19.539724 26.84375 20.771816 26.84375 22.027344 L 26.84375 26.044922 L 32.966797 26.044922 L 32.421875 29.564453 L 26.84375 29.564453 L 26.84375 46.929688 L 27.978516 46.775391 C 38.71434 45.319366 47 36.126845 47 25 C 47 12.861562 37.138438 3 25 3 z M 25 5 C 36.057562 5 45 13.942438 45 25 C 45 34.729791 38.035799 42.731796 28.84375 44.533203 L 28.84375 31.564453 L 34.136719 31.564453 L 35.298828 24.044922 L 28.84375 24.044922 L 28.84375 22.027344 C 28.84375 20.989871 29.033574 20.060293 29.353516 19.501953 C 29.673457 18.943614 29.981865 18.701172 30.738281 18.701172 L 35.185547 18.701172 L 35.185547 12.009766 L 34.318359 11.892578 C 33.718567 11.811418 32.349197 11.621094 29.878906 11.621094 C 27.175808 11.621094 24.855567 12.357448 23.253906 14.023438 C 21.652246 15.689426 20.861328 18.170128 20.861328 21.373047 L 20.861328 24.046875 L 15.664062 24.046875 L 15.664062 31.566406 L 20.861328 31.566406 L 20.861328 44.470703 C 11.816995 42.554813 5 34.624447 5 25 C 5 13.942438 13.942438 5 25 5 z"></path>
							</svg>
						</a>
					<?php endif; ?>
				</nav>
			</div>
			<div class="mt-12 sm:mt-0 sm:ml-auto sm:col-span-8">
				<div class="grid grid-cols-2 justify-right sm:grid-cols-3 gap-24 gap-y-10">
					<div class="text-lg">
						<h5 class="text-white/60">Quick links</h5>
						<ul role="list" class="mt-5 text-white/85 text-left md:mt-10 md:max-w-[185px] space-y-4">
							<?php
							if ($first) {
								foreach ($first as $menu_item) {
									echo '<li><a class="leading-6 hover:opacity-50" href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
								}
							} else {
								echo 'No menu items found.';
							}
							?>
						</ul>
					</div>
					<div class="text-lg">
						<h5 class="text-white/60">Resources</h5>
						<ul role="list" class="mt-5 text-white/85 text-left md:mt-10 md:max-w-[185px] space-y-4">
							<?php
							if ($second) {
								foreach ($second as $menu_item) {
									echo '<li><a class="leading-6 hover:opacity-50" href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
								}
							} else {
								echo 'No menu items found.';
							}
							?>
						</ul>
					</div>
					<div class="text-lg">
						<h5 class="text-white/60">Company</h5>
						<ul role="list" class="mt-5 text-white/85 text-left md:mt-10 md:max-w-[185px] space-y-4">
							<?php
							if ($third) {
								foreach ($third as $menu_item) {
									echo '<li><a class="leading-6 hover:opacity-50" href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
								}
							} else {
								echo 'No menu items found.';
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="max-w-6xl mx-auto pb-4 pt-12 sm:pt-32">
		<div class="flex flex-col sm:flex-row justify-between align-baseline sm:items-center text-md text-gray-500 md:mt-0">
			<p class="order-2 sm:order-1 text-center text-white mt-14 md:mt-0 sm:text-left">© <?php echo esc_html($current_year); ?> <?php echo esc_html(get_theme_mod('footer_copyright', $site_name)); ?>. All rights reserved.</p>
			<ul class="list-none flex order-1 sm:order-2 gap-10 align-baseline">
				<?php
				if ($privacy) {
					foreach ($privacy as $menu_item) {
						echo '<li><a class="leading-6 text-gray-300 hover:opacity-50" href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
					}
				} else {
					echo 'No menu items found.';
				}
				?>
			</ul>
		</div>
	</div>
</footer>