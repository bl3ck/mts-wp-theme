<?php
get_header();

// Get Course terms
$courses = get_terms([
    'taxonomy' => 'course',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);

// Get University terms
$universities = get_terms([
    'taxonomy' => 'university',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);

// Get Country terms (now a taxonomy)
$countries = get_terms([
    'taxonomy' => 'country',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
]);

$award_years = get_terms([
    'taxonomy'   => 'awarded_year',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'DESC',
]);

$graduation_years = get_terms([
    'taxonomy'   => 'graduation_year',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'DESC',
]);

?>

<div class="winners-archive flex flex-col md:flex-row gap-8 py-8 px-4 sm:px-6 lg:px-8 mx-auto" x-data="winnersArchive()">
    <!-- Sticky Filters Sidebar -->
    <div class="w-full md:w-72 flex-shrink-0 lg:sticky lg:top-4 h-fit">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                <button @click="resetFilters()" class="text-sm text-mt-blue hover:text-indigo-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset
                </button>
            </div>

            <!-- Search -->
            <div class="mb-6">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        x-model="searchQuery"
                        @input.debounce.300ms="applyFilters()"
                        type="text"
                        id="search"
                        placeholder="Name, university..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-mt-blue/50 focus:border-mt-blue/50">
                </div>
            </div>

            <!-- Country Filter -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                <select
                    x-model="filters.country"
                    @change="applyFilters()"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-mt-blue/50 focus:border-mt-blue/50 sm:text-sm rounded-md">
                    <option value="">All Countries</option>
                    <?php foreach ($countries as $term): ?>
                        <option value="<?= esc_attr($term->slug) ?>"><?= esc_html($term->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- University Filter -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">University</label>
                <select
                    x-model="filters.university"
                    @change="applyFilters()"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-mt-blue/50 focus:border-mt-blue/50 sm:text-sm rounded-md">
                    <option value="">All Universities</option>
                    <?php foreach ($universities as $term): ?>
                        <option value="<?= esc_attr($term->slug) ?>"><?= esc_html($term->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Course Filter -->
            <div class="hidden md:block mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                <select
                    x-model="filters.course"
                    @change="applyFilters()"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-mt-blue/50 focus:border-mt-blue/50 sm:text-sm rounded-md">
                    <option value="">All Courses</option>
                    <?php foreach ($courses as $term): ?>
                        <option value="<?= esc_attr($term->slug) ?>"><?= esc_html($term->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Award Year Filter -->
            <div class="mb-6">
                <div class="block text-sm font-medium text-gray-700 mb-2">Award Year</div>
                <div class="gap-2 grid grid-cols-2 sm:grid-cols-3 align-middle">
                    <?php foreach ($award_years as $term): ?>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                x-model="filters.award_years"
                                @change="applyFilters()"
                                value="<?= esc_attr($term->slug) ?>"
                                class="h-4 w-4 text-mt-blue focus:ring-mt-blue/50 border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-700"><?= esc_html($term->name) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Graduation Year Filter -->
            <div class="hidden md:block mb-6">
                <div class="block text-sm font-medium text-gray-700 mb-2">Graduation Year</div>
                <div class="gap-2 grid grid-cols-2 sm:grid-cols-3 align-middle">
                    <?php foreach ($graduation_years as $term): ?>
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                x-model="filters.graduation_years"
                                @change="applyFilters()"
                                value="<?= esc_attr($term->slug) ?>"
                                class="h-4 w-4 text-mt-blue focus:ring-mt-blue/50 border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-700"><?= esc_html($term->name) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">All Winners</h1>
            <p x-text="`${showingText} ${showingCount} ${ofText} ${pagination.total} ${winnersText}`" class="text-sm text-gray-600 mt-1"></p>
        </div>

        <!-- Loading State -->
        <div x-show="loading && winners.length === 0" class="text-center py-12">
            <svg class="animate-spin mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2 text-gray-500">Loading winners...</p>
        </div>

        <!-- Winners Grid -->
        <div x-show="!loading || winners.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
            <template x-for="winner in winners" :key="winner.id">
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                    <a :href="winner.link" class="grid sm:grid-cols-2 group">
                        <div class="relative h-76 overflow-hidden">
                            <img
                                :src="winner.image || '<?php echo get_template_directory_uri(); ?>/assets/default-winner.jpg'"
                                :alt="winner.title"
                                class="h-full w-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                            <div class="absolute bottom-4 left-4">
                                <span x-text="`${winner.awarded_year} Winner`" class="bg-mt-blue text-white text-xs font-semibold px-2 py-1 rounded"></span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-sans font-semibold text-gray-900 mb-2" x-text="winner.title"></h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span x-text="winner.university"></span>
                                </p>
                                <p class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span x-text="winner.course"></span>
                                </p>
                                <p class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="winner.country.label"></span>
                                </p>
                                <p class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="winner.graduation_year"></span>
                                </p>
                                <p class="flex items-start" x-show="winner.cgpa">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span><span class="font-medium">CGPA:</span> <span x-text="winner.cgpa"></span></span>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </template>
        </div>

        <!-- Load More Button -->
        <div class="text-center" x-show="showingCount < pagination.total && !loading">
            <button
                @click="loadMore()"
                class="inline-flex items-center px-6 py-2.5 bg-mt-blue text-white font-medium rounded-md hover:bg-mt-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mt-blue/50">
                Load More
            </button>
        </div>

        <!-- Loading More State -->
        <div x-show="loading && winners.length > 0" class="text-center py-4">
            <svg class="animate-spin mx-auto h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- No Results Message -->
        <div x-show="winners.length === 0 && !loading" class="text-center py-12 bg-white rounded-lg shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No winners found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria</p>
            <div class="mt-6">
                <button
                    @click="resetFilters()"
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-mt-blue hover:bg-mt-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mt-blue/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filters
                </button>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
