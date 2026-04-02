// Newsletter archive Alpine.js component.
if (window.Alpine) {
    registerNewsletterComponent();
} else {
    document.addEventListener('alpine:init', registerNewsletterComponent);
}

function registerNewsletterComponent() {
    Alpine.data('newsletterArchive', () => ({
        posts: [],
        loading: false,
        pagination: {
            page: 1,
            perPage: 9,
            total: 0,
        },
        hasMore: true,

        async init() {
            await this.fetchPosts();
        },

        async fetchPosts(reset = false) {
            this.loading = true;

            if (reset) {
                this.pagination.page = 1;
                this.posts = [];
                this.hasMore = true;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'load_newsletters');
                formData.append('nonce', newsletterArchive.nonce);
                formData.append('page', this.pagination.page);
                formData.append('per_page', this.pagination.perPage);

                const response = await fetch(newsletterArchive.ajaxUrl, {
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
                }
            } catch (error) {
                console.error('Error fetching newsletters:', error);
            } finally {
                this.loading = false;
            }
        },

        async loadMore() {
            this.pagination.page++;
            await this.fetchPosts();
        },
    }));
}
