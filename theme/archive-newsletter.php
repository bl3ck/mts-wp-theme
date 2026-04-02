<?php
/**
 * Archive: Newsletter
 *
 * Renders a grid of newsletters with AJAX "Load More".
 * Each card links to the Brevo newsletter URL (ACF `link` field).
 *
 * @package Michael_Taiwo_Scholarship
 */

get_header( 'blog' );
?>

<div class="page-container" x-data="newsletterArchive()">

    <!-- Loading State (initial) -->
    <div x-show="loading && posts.length === 0" class="text-center py-12">
        <svg class="animate-spin mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-2 text-gray-500">Loading newsletters…</p>
    </div>

    <!-- Posts Grid -->
    <div x-show="!loading || posts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <template x-for="post in posts" :key="post.id">
            <a :href="post.link" target="_blank" rel="noopener noreferrer"
               class="flex flex-col rounded-3xl bg-white shadow-md ring-1 shadow-black/5 ring-black/5 hover:shadow-xl transition-shadow duration-300 overflow-hidden group">

                <!-- Featured Image -->
                <div class="relative overflow-hidden rounded-t-3xl">
                    <img
                        :src="post.featured_image"
                        :alt="post.title"
                        class="aspect-[3/2] w-full object-cover transition-transform duration-300 group-hover:scale-105">
                </div>

                <!-- Content -->
                <div class="flex flex-1 flex-col justify-between p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 leading-tight group-hover:text-green-600 transition-colors duration-200"
                        x-text="post.title"></h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2" x-text="post.excerpt"></p>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-500" x-text="post.date"></span>
                        <span class="text-sm font-medium text-green-600 group-hover:text-green-700 transition-colors duration-200">
                            Read Newsletter →
                        </span>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Load More -->
    <div class="text-center" x-show="hasMore && !loading">
        <button
            @click="loadMore()"
            class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
            Load More Newsletters
        </button>
    </div>

    <!-- Loading More State -->
    <div x-show="loading && posts.length > 0" class="text-center py-4">
        <svg class="animate-spin mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <!-- No Results -->
    <div x-show="posts.length === 0 && !loading" class="text-center py-12 bg-white rounded-lg shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No newsletters found</h3>
        <p class="mt-1 text-sm text-gray-500">Check back soon for updates.</p>
    </div>
</div>

<?php
get_footer();
?>
