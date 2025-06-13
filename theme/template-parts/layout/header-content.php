<?php

/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Michael_Taiwo_Scholarship
 */

?>

<header id="masthead" class="hidden">
	<div>
		<?php
		if (is_front_page()) :
		?>
			<h1><?php bloginfo('name'); ?></h1>
		<?php
		else :
		?>
			<p><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
		<?php
		endif;

		$mt_description = get_bloginfo('description', 'display');
		if ($mt_description || is_customize_preview()) :
		?>
			<p><?php echo $mt_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
				?></p>
		<?php endif; ?>
	</div>

	<nav id="site-navigation" aria-label="<?php esc_attr_e('Main Navigation', 'michael-taiwo-scholarship'); ?>">
		<button aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e('Primary Menu', 'michael-taiwo-scholarship'); ?></button>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
				'items_wrap'     => '<ul id="%1$s" class="%2$s" aria-label="submenu">%3$s</ul>',
			)
		);
		?>
	</nav><!-- #site-navigation -->

</header><!-- #masthead -->

<?php
if (function_exists('the_custom_logo')) {
	$custom_logo_id = get_theme_mod('custom_logo');
	$logo_image = wp_get_attachment_image_src($custom_logo_id, 'full');

	if (!empty($logo_image)) {
		$logo_url = esc_url($logo_image[0]);
	}
}
$favicon_url = get_site_icon_url();
$menu_items = wp_get_nav_menu_items('main');

$is_home_page = is_front_page();
$header_bg_class = $is_home_page ? 'bg-white' : 'bg-mt-cream !text-white';

?>



<header class="<?php echo $header_bg_class ?> px-4 sm:px-0">
	<nav class="mx-auto flex max-w-6xl items-center justify-between py-6" aria-label="Global">
		<div class="flex lg:flex-1 logo">
			<a href="/" class="-m-1.5 p-1.5">
				<span class="sr-only">Your Company</span>
				<img class="h-8 sm:h-11 w-auto" src="<?php echo $logo_url ?>" alt="Logo">
			</a>
		</div>
		<div class="flex lg:hidden">
			<button x-on:click="mobileMenuFlyout = true;" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
				<span class="sr-only">Open main menu</span>
				<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
					<path d="M16 112H432C440.844 112 448 104.844 448 96S440.844 80 432 80H16C7.156 80 0 87.156 0 96S7.156 112 16 112ZM496 240H80C71.156 240 64 247.156 64 256S71.156 272 80 272H496C504.844 272 512 264.844 512 256S504.844 240 496 240ZM432 400H16C7.156 400 0 407.156 0 416S7.156 432 16 432H432C440.844 432 448 424.844 448 416S440.844 400 432 400Z" />
				</svg>
			</button>
		</div>
		<div class="hidden menu lg:flex lg:gap-x-12">
			<?php
			// Retrieve the menu items
			$menu_items = wp_get_nav_menu_items('main');
			$current_url = home_url(add_query_arg(array(), $wp->request));

			// Group the menu items by parent ID
			$grouped_menu_items = array();
			foreach ($menu_items as $menu_item) {
				$parent_id = $menu_item->menu_item_parent;
				$grouped_menu_items[$parent_id][] = $menu_item;
			}

			// Function to recursively render submenu items
			function render_submenu_items($submenu_items, $current_url)
			{ ?>
				<div x-show="flyoutMenu" class="absolute -left-8 top-full z-10 mt-3 w-screen max-w-sm overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1">
					<div class="p-4">
						<?php foreach ($submenu_items as $submenu_item) {
							// Check if the submenu item URL matches the current URL
							$stripped_url = str_replace(home_url(), '', $current_url);
							$active_class = '';
							if (rtrim($submenu_item->url, '/') === rtrim($current_url, '/') || rtrim($submenu_item->url, '/') === $stripped_url) {
								$active_class = 'active';
							}
						?>
							<div class="group relative flex items-center gap-x-6 rounded-lg p-4 leading-6 hover:bg-gray-50">
								<a href="<?php echo esc_url($submenu_item->url); ?>" class="block w-full font-poppins text-base text-gray-900 <?php echo $active_class; ?>"><?php echo esc_html($submenu_item->title); ?></a>
							</div>
						<?php
							// Check if the submenu item has children and recursively render them
							if (isset($submenu_item->ID) && isset($GLOBALS['grouped_menu_items'][$submenu_item->ID])) {
								render_submenu_items($GLOBALS['grouped_menu_items'][$submenu_item->ID], $current_url);
							}
						} ?>
					</div>
				</div>
				<?php
			}

			// Loop through the menu items
			foreach ($menu_items as $menu_item) {
				// Check if the menu item has a parent ID
				if ($menu_item->menu_item_parent == 0) {
					// Check if the menu item has children
					if (isset($menu_item->ID) && isset($grouped_menu_items[$menu_item->ID])) {
						// Render menu items with dropdowns
				?>
						<div x-data="{flyoutMenu: false}" class="relative">
							<button type="button" x-on:click="flyoutMenu =! flyoutMenu" @click.away="flyoutMenu = false" class="flex items-center gap-x-1 text-base font-spartan leading-6 text-gray-900" aria-expanded="false">
								<?php echo esc_html($menu_item->title); ?>
								<svg class="h-5 w-5 flex-none text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
								</svg>
							</button>
							<?php
							// Render submenu items recursively
							render_submenu_items($grouped_menu_items[$menu_item->ID], $current_url);
							?>
						</div>
					<?php
					} else {
						// Check if the menu item URL matches the current URL
						$active_class = '';
						$stripped_url = str_replace(home_url(), '', $current_url);
						if (rtrim($menu_item->url, '/') === rtrim($current_url, '/') || rtrim($menu_item->url, '/') === $stripped_url) {
							$active_class = 'active';
						}
						// Render menu items without dropdowns
						$menu_item_url = $menu_item->url;
						$menu_item_title = $menu_item->title;
					?>
						<a href="<?php echo esc_url($menu_item_url); ?>" class="leading-6 text-gray-900 <?php echo $active_class; ?>"><?php echo esc_html($menu_item_title); ?></a>
			<?php
					}
				}
			}

			?>
		</div>
	</nav>
	<!-- Mobile menu, show/hide based on menu open state. -->
	<div class="lg:hidden menu" role="dialog" aria-modal="true">
		<!-- Background backdrop, show/hide based on slide-over state. -->
		<div x-show="mobileMenuFlyout" class="fixed inset-0 z-10"></div>
		<div x-show="mobileMenuFlyout" class=" bg-white z-50 shadow-xl rounded-lg fixed inset-y-0 right-0 w-full overflow-y-auto p-10 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
			<div class="flex items-center justify-between">
				<a href="/" class="-m-1.5 p-1.5">
					<span class="sr-only">Your Company</span>
					<img class="h-8 w-auto" src="<?php echo $favicon_url ?>" alt="">
				</a>
				<button x-on:click="mobileMenuFlyout = !mobileMenuFlyout" type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
					<span class="sr-only">Close menu</span>
					<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>
			</div>
			<div x-show="mobileMenuFlyout" class="mt-6 flow-root">
				<div class="-my-6 divide-y divide-gray-500/10">
					<div class="space-y-4 px-4 py-6">
						<nav>
							<?php
							$current_url = home_url(add_query_arg(array(), $wp->request));
							if ($menu_items) {
								echo '<ul class="mt-8 space-y-6">';

								foreach ($menu_items as $menu_item) {
									$active_class = '';
									$stripped_url = str_replace(home_url(), '', $current_url);
									if (rtrim($menu_item->url, '/') === rtrim($current_url, '/') || rtrim($menu_item->url, '/') === $stripped_url) {
										$active_class = 'active';
									}
									if ($menu_item->title !== 'Company' && $menu_item->title !== 'Partnership') {
										echo '<li><a class="' . '-mx-3 block rounded-lg leading-6 text-base font-light font-poppins text-gray-900 hover:text-black/70 ' . $active_class . '" href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
									}
								}

								echo '</ul>';
							} else {
								echo 'No menu items found.';
							}
							?>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>



<?php
if (!is_front_page()) :
	$title = is_singular() ? get_the_title() : single_post_title('', false);

	// Generate proper breadcrumbs
	$breadcrumbs = array();

	// Always start with home
	$breadcrumbs[] = array(
		'url' => home_url('/'),
		'label' => __('Home', 'textdomain')
	);

	$scholarship_icons = array(
		// Graduation Cap
		'<svg class="size-70 ml-4 fill-mt-blue/10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
        </svg>',

		// Book Open
		'<svg class="size-70 ml-4 fill-mt-blue/10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
        </svg>',

		// Lightbulb (idea)
		'<svg class="size-70 ml-4 fill-mt-blue/10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path>
        </svg>',

		// Academic Cap
		'<svg class="size-70 ml-4 fill-mt-blue/10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
        </svg>',

		// Trophy
		'<svg class="size-70 ml-4 fill-mt-blue/10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.997-6.94l-.003-.002-.017-.01a20.02 20.02 0 01-1.433-.742 11.965 11.965 0 01-2.702-2.246C5.141 6.594 5 5.698 5 5c0-.7.141-1.594.454-2.442a11.96 11.96 0 012.702-2.246 20.02 20.02 0 011.433-.742.75.75 0 01.612 0l.017.01c.486.264.966.547 1.433.742 1.02.532 1.94 1.184 2.702 2.246.313.848.454 1.742.454 2.442 0 .698-.141 1.594-.454 2.442a11.96 11.96 0 01-2.702 2.246 20.02 20.02 0 01-1.433.742l-.003.001-.017.01a.75.75 0 01-.611 0l-.018-.01zM12 5a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"></path>
        </svg>'
	);

	// Get random icon
	$random_icon = $scholarship_icons[array_rand($scholarship_icons)];

	if (is_singular()) {
		// Handle posts
		if (is_single() && 'post' == get_post_type()) {
			$blog_page = get_option('page_for_posts');
			if ($blog_page) {
				$breadcrumbs[] = array(
					'url' => get_permalink($blog_page),
					'label' => get_the_title($blog_page)
				);
			}
			$categories = get_the_category();
			if ($categories) {
				$breadcrumbs[] = array(
					'url' => get_category_link($categories[0]->term_id),
					'label' => $categories[0]->name
				);
			}
		}
		// Handle custom post types
		elseif (is_single() && 'post' != get_post_type()) {
			$post_type = get_post_type_object(get_post_type());
			$breadcrumbs[] = array(
				'url' => get_post_type_archive_link($post_type->name),
				'label' => $post_type->labels->name
			);
		}
		// Handle pages
		elseif (is_page()) {
			$ancestors = get_post_ancestors(get_the_ID());
			if ($ancestors) {
				$ancestors = array_reverse($ancestors);
				foreach ($ancestors as $ancestor) {
					$breadcrumbs[] = array(
						'url' => get_permalink($ancestor),
						'label' => get_the_title($ancestor)
					);
				}
			}
		}

		// Current page (no link)
		$breadcrumbs[] = array(
			'url' => '',
			'label' => get_the_title()
		);
	} elseif (is_category()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => single_cat_title('', false)
		);
	} elseif (is_tag()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => single_tag_title('', false)
		);
	} elseif (is_author()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => get_the_author()
		);
	} elseif (is_day()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => get_the_date()
		);
	} elseif (is_month()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => get_the_date('F Y')
		);
	} elseif (is_year()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => get_the_date('Y')
		);
	} elseif (is_post_type_archive()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => post_type_archive_title('', false)
		);
	} elseif (is_tax()) {
		$term = get_queried_object();
		$taxonomy = get_taxonomy($term->taxonomy);
		if ($taxonomy->hierarchical && $term->parent) {
			$ancestors = get_ancestors($term->term_id, $term->taxonomy);
			$ancestors = array_reverse($ancestors);
			foreach ($ancestors as $ancestor) {
				$ancestor_term = get_term($ancestor, $term->taxonomy);
				$breadcrumbs[] = array(
					'url' => get_term_link($ancestor_term),
					'label' => $ancestor_term->name
				);
			}
		}
		$breadcrumbs[] = array(
			'url' => '',
			'label' => $term->name
		);
	} elseif (is_search()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => sprintf(__('Search Results for: %s', 'textdomain'), get_search_query())
		);
	} elseif (is_404()) {
		$breadcrumbs[] = array(
			'url' => '',
			'label' => __('404 Not Found', 'textdomain')
		);
	}
?>
	<div class="relative bg-mt-cream">
		<div class="page-container relative mx-auto py-16">
			<div class="grid sm:grid-cols-2 justify-between">
				<div class="col-span-1">
					<h1 class="font-extrabold text-gray-900 leading-tight">
						<span class="block">
							<?php
							if (is_post_type_archive()) {
								echo esc_html(post_type_archive_title('', false));
							} elseif (is_category() || is_tag() || is_tax()) {
								echo esc_html(single_term_title('', false));
							} elseif (is_singular()) {
								the_title();
							} elseif (is_home()) {
								echo 'Blog';
							} else {
								echo 'Untitled';
							}
							?>
						</span>
					</h1>

					<?php if ($subtitle = get_field('subtitle')) : ?>
						<p class="mt-4 max-w-xl mr-auto text-xl text-gray-600">
							<?php echo esc_html($subtitle); ?>
						</p>
					<?php elseif (has_excerpt()) : ?>
						<p class="mt-4 max-w-xl mr-auto text-xl text-gray-600">
							<?php echo esc_html(get_the_excerpt()); ?>
						</p>
					<?php endif; ?>
				</div>
				<div class="hidden lg:flex col-span-1 align-middle justify-center">
					<p class="absolute right-0 -top-5"><?php echo $random_icon; ?></p>
				</div>
			</div>
		</div>
		<div class="bg-mt-cream/50 py-4">
			<div class="px-4 md:px-0 mx-auto max-w-6xl">
				<nav class="text-sm leading-5" aria-label="Breadcrumb">
					<ol class="list-none p-0 inline-flex">
						<?php foreach ($breadcrumbs as $i => $crumb) : ?>
							<li class="flex items-center">
								<?php if ($crumb['url'] && $i < count($breadcrumbs) - 1) : ?>
									<a href="<?php echo esc_url($crumb['url']); ?>" class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">
										<?php echo esc_html($crumb['label']); ?>
									</a>
									<svg class="flex-shrink-0 mx-2 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
									</svg>
								<?php else : ?>
									<span class="text-gray-700" aria-current="page">
										<?php echo esc_html($crumb['label']); ?>
									</span>
									<?php if ($i < count($breadcrumbs) - 1) : ?>
										<svg class="flex-shrink-0 mx-2 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
											<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
										</svg>
									<?php endif; ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
				</nav>

				<?php if ($deadline): ?>
					<div
						x-data="countdownComponent($el.dataset.deadline)"
						x-init="init()"
						x-show="isActive"
						data-deadline="<?= esc_attr($deadline); ?>"
						class="application-countdown">
						<div class="countdown-display text-lg font-semibold mb-2">
							<template x-if="timeRemaining.total > 0">
								<span x-text="`${timeRemaining.days}d ${timeRemaining.hours}h ${timeRemaining.minutes}m ${timeRemaining.seconds}s left`"></span>
							</template>
						</div>
						<a href="/apply-form-url" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
							Apply Now
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>