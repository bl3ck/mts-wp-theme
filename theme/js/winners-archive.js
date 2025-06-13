// Ensure Alpine component is registered before Alpine initializes
if (window.Alpine) {
    // If Alpine is already loaded, register immediately
    registerWinnersComponent();
} else {
    // Wait for Alpine to initialize
    document.addEventListener('alpine:init', registerWinnersComponent);
}

function registerWinnersComponent() {
    // Localized strings from WordPress
    const i18n = window.winnersArchiveI18n || {
        showingText: 'Showing',
        ofText: 'of',
        winnersText: 'winners',
        loadingText: 'Loading...'
    };
    Alpine.data('winnersArchive', () => ({
        // Reactive state
        winners: [],
        loading: false,
        searchQuery: '',
        filters: {
            country: '',
            university: '',
            course: '',
            award_years: [],
            graduation_years: [],
            cgpa_ranges: []
        },
        pagination: {
            page: 1,
            perPage: 12,
            total: 0
        },
        showingText: i18n.showingText,
        ofText: i18n.ofText,
        winnersText: i18n.winnersText,

        // Initialize component
        async init() {
            // Check URL for initial filter state
            this.parseUrlFilters();

            // Load initial winners
            await this.fetchWinners();

            // Set up intersection observer for infinite scroll
            this.setupInfiniteScroll();
        },

        // Parse URL for initial filter state
        parseUrlFilters() {
            const params = new URLSearchParams(window.location.search);

            if (params.has('search')) {
                this.searchQuery = params.get('search');
            }

            if (params.has('country')) {
                this.filters.country = params.get('country');
            }

            if (params.has('university')) {
                this.filters.university = params.get('university');
            }

            if (params.has('course')) {
                this.filters.course = params.get('course');
            }

            if (params.has('award_years')) {
                this.filters.award_years = params.get('award_years').split(',');
            }

            if (params.has('graduation_years')) {
                this.filters.graduation_years = params.get('graduation_years').split(',');
            }
        },

        // Fetch winners with current filters
        async fetchWinners(reset = false) {
            console.log('Fetching winners with search:', this.searchQuery); // Debug log
            this.loading = true;

            if (reset) {
                this.pagination.page = 1;
                this.winners = [];
            }

            try {
                const formData = new FormData();
                formData.append('action', 'filter_winners');
                formData.append('nonce', winnersArchive.nonce);
                formData.append('search', this.searchQuery);
                formData.append('page', this.pagination.page);
                formData.append('per_page', this.pagination.perPage);

                // Append single value filters
                if (this.filters.country) {
                    formData.append('country', this.filters.country);
                }
                if (this.filters.university) {
                    formData.append('university', this.filters.university);
                }
                if (this.filters.course) {
                    formData.append('course', this.filters.course);
                }

                // Append array filters
                this.filters.award_years.forEach(year => {
                    formData.append('award_years[]', year);
                });

                this.filters.graduation_years.forEach(year => {
                    formData.append('graduation_years[]', year);
                });

                this.filters.cgpa_ranges.forEach(range => {
                    formData.append('cgpa_ranges[]', range);
                });

                // Debug: Log what we're sending
                console.log('FormData contents:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }

                const response = await fetch(winnersArchive.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Response data:', data); // Debug log

                if (data.success) {
                    if (reset) {
                        this.winners = data.data.winners;
                    } else {
                        this.winners = [...this.winners, ...data.data.winners];
                    }
                    this.pagination.total = data.data.total;
                } else {
                    console.error('Error fetching winners:', data);
                }
            } catch (error) {
                console.error('Error fetching winners:', error);
            } finally {
                this.loading = false;
            }
        },

        // Serialize filters for URL params
        serializeFilters() {
            const params = {};

            Object.entries(this.filters).forEach(([key, value]) => {
                if (Array.isArray(value) && value.length > 0) {
                    params[key] = value.join(',');
                } else if (value && !Array.isArray(value)) {
                    params[key] = value;
                }
            });

            return params;
        },

        // Update browser URL without reload
        updateURL() {
            const params = new URLSearchParams();

            if (this.searchQuery) {
                params.set('search', this.searchQuery);
            }

            Object.entries(this.serializeFilters()).forEach(([key, value]) => {
                params.set(key, value);
            });

            const newUrl = params.toString()
                ? `${window.location.pathname}?${params.toString()}`
                : window.location.pathname;

            window.history.pushState({}, '', newUrl);
        },

        // Apply filters (reset to first page)
        async applyFilters() {
            this.updateURL();
            await this.fetchWinners(true);
        },

        // Load more winners
        async loadMore() {
            this.pagination.page++;
            await this.fetchWinners();
        },

        // Reset all filters
        async resetFilters() {
            this.searchQuery = '';
            this.filters = {
                country: '',
                university: '',
                course: '',
                award_years: [],
                graduation_years: [],
                cgpa_ranges: []
            };

            // Clear URL parameters
            window.history.pushState({}, '', window.location.pathname);

            await this.fetchWinners(true);
        },

        // Set up infinite scroll
        setupInfiniteScroll() {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting &&
                    !this.loading &&
                    this.showingCount < this.pagination.total) {
                    this.loadMore();
                }
            }, { threshold: 0.1 });

            // Observe the sentinel element at bottom of page
            const sentinel = document.createElement('div');
            sentinel.classList.add('infinite-scroll-sentinel');
            sentinel.style.height = '1px';
            const archiveContainer = document.querySelector('.winners-archive');
            if (archiveContainer) {
                archiveContainer.appendChild(sentinel);
                observer.observe(sentinel);
            }
        },

        // Computed property for showing count
        get showingCount() {
            return Math.min(
                this.pagination.page * this.pagination.perPage,
                this.pagination.total
            );
        }
    }));
}
