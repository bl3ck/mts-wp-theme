<?php
/**
 * Template Name: Blog Home
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header('blog');

// Get the current page number for initial load
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// Query for posts
$posts_query = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 9, // 3x3 grid
    'paged' => $paged,
    'post_status' => 'publish'
]);

// Get all categories for filtering (excluding uncategorized)
$categories = get_categories([
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
    'exclude' => array(1) // Exclude "Uncategorized" category (ID 1)
]);
?>

<div class="page-container" x-data="blogArchive()">
    
    <!-- Optional: Category Filter -->
    <?php if (!empty($categories)): ?>
    <div class="mb-8">
        <div class="flex flex-wrap gap-2">
            <button 
                @click="filterByCategory('')" 
                :class="selectedCategory === '' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                All Posts
            </button>
            <?php foreach ($categories as $category): ?>
            <button 
                @click="filterByCategory('<?= esc_attr($category->slug) ?>')" 
                :class="selectedCategory === '<?= esc_attr($category->slug) ?>' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                <?= esc_html($category->name) ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Loading State -->
    <div x-show="loading && posts.length === 0" class="text-center py-12">
        <svg class="animate-spin mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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
                        <template x-for="category in post.categories.slice(0, 1)" :key="category.id">
                            <span 
                                x-text="category.name"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-600/90 backdrop-blur-sm text-white">
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex flex-1 flex-col justify-between p-6">
                    <!-- Title -->
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 leading-tight select-text">
                            <a :href="post.link" class="hover:text-green-600 transition-colors duration-200 relative z-10" x-text="post.title"></a>
                        </h3>
                    </div>

                    <!-- Author and Date -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-3">
                            <!-- Author Images Stack -->
                            <div :class="post.co_authors && post.co_authors.length > 0 ? 'flex -space-x-2' : 'flex'">
                                <img 
                                    :src="post.author.avatar" 
                                    :alt="post.author.name"
                                    :class="post.co_authors && post.co_authors.length > 0 ? 'w-8 h-8 rounded-full object-cover border-2 border-white ring-1 ring-gray-200' : 'w-8 h-8 rounded-full object-cover'">
                                <template x-if="post.co_authors && post.co_authors.length > 0">
                                    <template x-for="(coAuthor, index) in post.co_authors.slice(0, 3)" :key="coAuthor.id">
                                        <img 
                                            :src="coAuthor.avatar" 
                                            :alt="coAuthor.name"
                                            class="w-8 h-8 rounded-full object-cover border-2 border-white ring-1 ring-gray-200">
                                    </template>
                                </template>
                                <template x-if="post.co_authors && post.co_authors.length > 3">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 border-2 border-white ring-1 ring-gray-200 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-600" x-text="'+' + (post.co_authors.length - 3)"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="select-text">
                                <div class="text-sm font-medium text-gray-900" x-text="post.author.name"></div>
                                <div class="text-xs text-gray-500" x-text="post.date"></div>
                            </div>
                        </div>
                        
                        <!-- Read More Link -->
                        <a :href="post.link" class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors duration-200 relative z-10">
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
            class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
            Load More Posts
        </button>
    </div>

    <!-- Loading More State -->
    <div x-show="loading && posts.length > 0" class="text-center py-4">
        <svg class="animate-spin mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <!-- No Results Message -->
    <div x-show="posts.length === 0 && !loading" class="text-center py-12 bg-white rounded-lg shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No posts found</h3>
        <p class="mt-1 text-sm text-gray-500">Try selecting a different category</p>
    </div>
</div>

<?php
get_footer();
?>