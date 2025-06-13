<div class="winners-grid">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold"><?php esc_html_e('Scholarship Winners', '_tw'); ?></h2>
        <div class="text-sm text-gray-600" x-text="`${showingText} ${pagination.total} ${winnersText}`"></div>
    </div>

    <!-- Loading Indicator -->
    <div x-show="loading" class="text-center py-8" aria-live="polite">
        <span class="spinner inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary"></span>
        <span class="sr-only"><?php esc_html_e('Loading...', '_tw'); ?></span>
    </div>

    <!-- Results Grid -->
    <div x-show="!loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" aria-live="polite">
        <template x-for="winner in winners" :key="winner.id">
            <?php get_template_part('template-parts/winners/card'); ?>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && winners.length === 0" class="text-center py-12">
        <?php get_template_part('template-parts/winners/empty-state'); ?>
    </div>

    <!-- Load More Button -->
    <div x-show="!loading && winners.length > 0 && showingCount < pagination.total" class="text-center mt-8">
        <button 
            @click="loadMore()" 
            class="px-6 py-3 bg-white border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 hover:bg-gray-50"
            :disabled="loading"
            aria-label="<?php esc_attr_e('Load more winners', '_tw'); ?>"
        >
            <?php esc_html_e('Load More', '_tw'); ?>
        </button>
    </div>
</div>