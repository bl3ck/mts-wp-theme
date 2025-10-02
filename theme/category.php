<?php
/**
 * Template Name: Category Archive
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header();

// Get current category
$current_category = get_queried_object();

// Get all categories for filtering (excluding uncategorized and current)
$categories = get_categories([
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
    'exclude' => array(1, $current_category->term_id) // Exclude "Uncategorized" and current category
]);
?>

<div class="category-archive py-8 px-4 sm:px-6 lg:px-8 mx-auto max-w-7xl" x-data="categoryArchive('<?= esc_attr($current_category->slug) ?>')">
    
    <!-- Category Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4"><?= esc_html($current_category->name) ?></h1>
        <?php if ($current_category->description): ?>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto"><?= esc_html($current_category->description) ?></p>
        <?php endif; ?>
        <div class="mt-4 text-sm text-gray-500">
            <span x-text="`${pagination.total} posts in this category`"></span>
        </div>
    </div>
    
    <!-- Other Categories Filter -->
    <?php if (!empty($categories)): ?>
    <div class="mb-8">
        <div class="flex flex-wrap gap-2 justify-center">
            <a href="<?= get_permalink(get_option('page_for_posts')) ?: home_url('/blog/') ?>" 
               class="px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors duration-200">
                All Posts
            </a>
            <?php foreach ($categories as $category): ?>
            <a href="<?= get_category_link($category->term_id) ?>" 
               class="px-4 py-2 rounded-full text-sm font-medium bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors duration-200">
                <?= esc_html($category->name) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Loading State -->
    <div x-show="loading && posts.length === 0" class="text-center py-12">
        <svg class="animate-spin mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-2 text-gray-500">Loading posts...</p>
    </div>

    <!-- Posts Grid -->
    <div x-show="!loading || posts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <template x-for="post in posts" :key="post.id">
            <article class="flex flex-col rounded-3xl bg-white shadow-md ring-1 shadow-black/5 ring-black/5 hover:shadow-xl transition-shadow duration-300 select-text overflow-hidden">
                <!-- Featured Image -->
                <div class="relative overflow-hidden rounded-t-3xl">
                    <img 
                        :src="post.featured_image || '<?php echo get_template_directory_uri(); ?>/assets/default-post.jpg'" 
                        :alt="post.title"
                        class="aspect-[3/2] w-full object-cover transition-transform duration-300 hover:scale-105">
                    
                    <!-- Category Badge -->
                    <div x-show="post.categories.length > 0" class="absolute top-4 left-4">
                        <template x-for="category in post.categories.filter(cat => cat.name.toLowerCase() !== 'uncategorized').slice(0, 1)" :key="category.id">
                            <span 
                                x-text="category.name"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600/90 backdrop-blur-sm text-white">
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex flex-1 flex-col justify-between p-6">
                    <!-- Title -->
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 leading-tight select-text">
                            <a :href="post.link" class="hover:text-blue-600 transition-colors duration-200 relative z-10" x-text="post.title"></a>
                        </h3>
                    </div>

                    <!-- Author and Date -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-3">
                            <img 
                                :src="post.author.avatar" 
                                :alt="post.author.name"
                                class="w-8 h-8 rounded-full object-cover">
                            <div class="select-text">
                                <div class="text-sm font-medium text-gray-900" x-text="post.author.name"></div>
                                <div class="text-xs text-gray-500" x-text="post.date"></div>
                            </div>
                        </div>
                        
                        <!-- Read More Link -->
                        <a :href="post.link" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors duration-200 relative z-10">
                            Read More →
                        </a>
                    </div>
                </div>
            </article>
        </template>
    </div>

    <!-- Load More Button -->
    <div class="text-center" x-show="hasMore && !loading">
        <button
            @click="loadMore()"
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
            Load More Posts
        </button>
    </div>

    <!-- Loading More State -->
    <div x-show="loading && posts.length > 0" class="text-center py-4">
        <svg class="animate-spin mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <!-- No Results Message -->
    <div x-show="posts.length === 0 && !loading" class="text-center py-12 bg-white rounded-lg shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No posts found</h3>
        <p class="mt-1 text-sm text-gray-500">This category doesn't have any posts yet</p>
        <div class="mt-4">
            <a href="<?= get_permalink(get_option('page_for_posts')) ?: home_url('/blog/') ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                ← Back to all posts
            </a>
        </div>
    </div>
</div>

<?php
get_footer();
?>
