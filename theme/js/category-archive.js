// Ensure Alpine component is registered before Alpine initializes
if (window.Alpine) {
    // If Alpine is already loaded, register immediately
    registerCategoryComponent();
} else {
    // Wait for Alpine to initialize
    document.addEventListener('alpine:init', registerCategoryComponent);
}

function registerCategoryComponent() {
    Alpine.data('categoryArchive', (categorySlug) => ({
        // Reactive state
        posts: [],
        loading: false,
        categorySlug: categorySlug,
        pagination: {
            page: 1,
            perPage: 9,
            total: 0
        },
        hasMore: true,

        // Initialize component
        async init() {
            // Load initial posts for this category
            await this.fetchPosts();
        },

        // Fetch posts for the current category
        async fetchPosts(reset = false) {
            this.loading = true;

            if (reset) {
                this.pagination.page = 1;
                this.posts = [];
                this.hasMore = true;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'load_category_posts');
                formData.append('nonce', categoryArchive.nonce);
                formData.append('page', this.pagination.page);
                formData.append('per_page', this.pagination.perPage);
                formData.append('category', this.categorySlug);

                const response = await fetch(categoryArchive.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    if (reset) {
                        this.posts = data.data.posts;
                    } else {
                        this.posts = [...this.posts, ...data.data.posts];
                    }
                    this.pagination.total = data.data.total;
                    this.hasMore = data.data.has_more;
                } else {
                    console.error('Error fetching posts:', data);
                }
            } catch (error) {
                console.error('Error fetching posts:', error);
            } finally {
                this.loading = false;
            }
        },

        // Load more posts
        async loadMore() {
            this.pagination.page++;
            await this.fetchPosts();
        }
    }));
}