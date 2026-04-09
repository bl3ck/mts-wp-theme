/**
 * Donation Form — Alpine.js component.
 *
 * Expects window.mtsDonationConfig to be set by the shortcode:
 *   { restUrl, nonce, paypalId, currency }
 *
 * The PayPal JS SDK is loaded dynamically so we can request the right
 * intent / vault parameters depending on whether the visitor is making
 * a one-time or recurring donation (the SDK requires different params
 * for each, and they cannot be combined in a single load).
 */

if (window.Alpine) {
    registerDonationComponent();
} else {
    document.addEventListener('alpine:init', registerDonationComponent);
}

function registerDonationComponent() {
    Alpine.data('donationForm', () => ({
        // --- State ---
        frequencies: [
            { value: 'one_time', label: 'One time' },
            { value: 'monthly',  label: 'Monthly' },
            { value: 'yearly',   label: 'Yearly' },
        ],
        amounts: [5, 10, 50, 100, 200, 500, 1000],
        frequency: 'monthly',
        amount: 0,
        selectedPreset: 0,
        customActive: false,
        customAmount: '',
        comment: '',
        processing: false,
        error: '',
        success: false,
        donorName: '',

        // --- Computed ---
        get buttonLabel() {
            if (!this.amount) return 'Donate';
            const freq = this.frequencies.find(f => f.value === this.frequency);
            const formatted = 'US$' + Number(this.amount).toLocaleString();
            return this.frequency === 'one_time'
                ? `Donate ${formatted}`
                : `Donate ${formatted} ${freq.label}`;
        },

        get thankYouHeading() {
            const tpl = window.mtsDonationConfig.thankYouHeading || 'Thank you!';
            // Heading renders via x-text, so replacement can be plain text.
            const name = this.donorName || 'friend';
            return tpl.replace(/\{name\}/g, name);
        },

        get thankYouMessage() {
            const tpl = window.mtsDonationConfig.thankYouMessage || '';
            // Message renders via x-html (it contains <p>, <strong>, etc. from
            // server-side wp_kses+wpautop), so the donor name MUST be
            // HTML-escaped before substitution to prevent injection via a
            // crafted PayPal display name.
            const name = this.donorName
                ? this._escapeHtml(this.donorName)
                : 'friend';
            return tpl.replace(/\{name\}/g, name);
        },

        _escapeHtml(s) {
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        },

        // --- Lifecycle ---
        init() {
            this.$watch('customAmount', (val) => {
                if (this.customActive) {
                    this.amount = val || 0;
                    this.error = '';
                    this.debouncePayPal();
                }
            });
            this.$watch('frequency', () => {
                this.renderPayPal();
            });
        },

        // --- Methods ---
        scrollIntoView() {
            this.$nextTick(() => {
                const wrapper = this.$el.closest
                    ? this.$el.closest('.mts-donation-wrapper') || this.$el
                    : this.$el;
                if (wrapper && typeof wrapper.scrollIntoView === 'function') {
                    wrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        },

        selectAmount(val) {
            this.customActive = false;
            this.selectedPreset = val;
            this.amount = val;
            this.error = '';
            this.renderPayPal();
        },

        activateCustom() {
            this.customActive = true;
            this.selectedPreset = 0;
            this.amount = this.customAmount || 0;
            this.$nextTick(() => {
                const input = this.$el.querySelector('input[type="number"]');
                if (input) input.focus();
            });
            this.renderPayPal();
        },

        // --- PayPal SDK loader ---
        _paypalTimeout: null,
        _sdkPromise: null,
        _sdkMode: null, // 'capture' | 'subscription'

        debouncePayPal() {
            clearTimeout(this._paypalTimeout);
            this._paypalTimeout = setTimeout(() => this.renderPayPal(), 400);
        },

        loadPayPalSdk() {
            const cfg = window.mtsDonationConfig;
            if (!cfg.paypalId) {
                return Promise.reject(new Error('PayPal client ID not configured'));
            }

            const isRecurring = this.frequency !== 'one_time';
            const mode = isRecurring ? 'subscription' : 'capture';

            // Reuse the in-flight / already-loaded SDK if it matches the mode we need.
            if (this._sdkMode === mode && this._sdkPromise) {
                return this._sdkPromise;
            }

            // Remove any previously loaded PayPal SDK script + globals so
            // we can reload with different params.
            const existing = document.querySelectorAll('script[src*="paypal.com/sdk/js"]');
            existing.forEach(s => s.remove());
            if (window.paypal) {
                try { delete window.paypal; } catch (e) { window.paypal = undefined; }
            }

            const params = new URLSearchParams({
                'client-id': cfg.paypalId,
                currency: cfg.currency || 'USD',
                components: 'buttons',
                'disable-funding': 'credit,card',
            });
            if (isRecurring) {
                params.set('vault', 'true');
                params.set('intent', 'subscription');
            } else {
                params.set('intent', 'capture');
            }

            const src = 'https://www.paypal.com/sdk/js?' + params.toString();

            this._sdkMode = mode;
            this._sdkPromise = new Promise((resolve, reject) => {
                const s = document.createElement('script');
                s.src = src;
                s.async = true;
                s.onload = () => resolve(window.paypal);
                s.onerror = () => reject(new Error('Failed to load PayPal SDK'));
                document.head.appendChild(s);
            });

            return this._sdkPromise;
        },

        // --- Render PayPal button ---
        async renderPayPal() {
            const cfg = window.mtsDonationConfig;
            if (!cfg.paypalId) return;
            if (!this.amount || this.amount < 1) return;

            const container = document.getElementById('mts-paypal-button');
            if (!container) return;

            this.error = '';

            try {
                await this.loadPayPalSdk();
            } catch (e) {
                console.error(e);
                this.error = 'Could not load PayPal. Please refresh and try again.';
                return;
            }

            if (typeof paypal === 'undefined' || !paypal.Buttons) return;

            // Clear previous buttons before rendering a fresh instance.
            container.innerHTML = '';

            const self = this;
            const isRecurring = this.frequency !== 'one_time';

            const buttonConfig = {
                style: {
                    layout: 'vertical',
                    color: 'gold',
                    shape: 'rect',
                    label: 'donate',
                },
                onError(err) {
                    console.error('PayPal error', err);
                    self.error = 'PayPal encountered an error. Please try again.';
                    self.processing = false;
                },
                onCancel() {
                    self.processing = false;
                },
            };

            if (isRecurring) {
                buttonConfig.createSubscription = function (_data, actions) {
                    self.processing = true;
                    return fetch(cfg.restUrl + '/paypal/create-subscription', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': cfg.nonce,
                        },
                        body: JSON.stringify({
                            amount: Number(self.amount),
                            frequency: self.frequency,
                        }),
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.plan_id) {
                            throw new Error(data.message || 'Could not create subscription plan.');
                        }
                        return actions.subscription.create({
                            plan_id: data.plan_id,
                            custom_id: self.comment ? self.comment.substring(0, 127) : undefined,
                        });
                    })
                    .catch(err => {
                        self.processing = false;
                        self.error = err.message || 'Could not start subscription.';
                        throw err;
                    });
                };

                buttonConfig.onApprove = function (data) {
                    return fetch(cfg.restUrl + '/paypal/subscription-activated', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': cfg.nonce,
                        },
                        body: JSON.stringify({
                            subscription_id: data.subscriptionID,
                        }),
                    })
                    .then(r => r.json())
                    .then(result => {
                        if (!result.success) {
                            throw new Error(result.message || 'Subscription verification failed.');
                        }
                        self.donorName = result.name || '';
                        self.processing = false;
                        self.success = true;
                        self.scrollIntoView();
                    })
                    .catch(err => {
                        self.processing = false;
                        self.error = err.message || 'Subscription could not be verified.';
                    });
                };
            } else {
                buttonConfig.createOrder = function () {
                    self.processing = true;
                    return fetch(cfg.restUrl + '/paypal/create-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': cfg.nonce,
                        },
                        body: JSON.stringify({
                            amount: Number(self.amount),
                            comment: self.comment,
                        }),
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.id) {
                            throw new Error(data.message || 'Could not create order.');
                        }
                        return data.id;
                    })
                    .catch(err => {
                        self.processing = false;
                        self.error = err.message || 'Could not start donation.';
                        throw err;
                    });
                };

                buttonConfig.onApprove = function (data) {
                    return fetch(cfg.restUrl + '/paypal/capture-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': cfg.nonce,
                        },
                        body: JSON.stringify({
                            order_id: data.orderID,
                            comment: self.comment,
                        }),
                    })
                    .then(r => r.json())
                    .then(result => {
                        if (!result.success) {
                            throw new Error(result.message || 'Payment capture failed.');
                        }
                        self.donorName = result.name || '';
                        self.processing = false;
                        self.success = true;
                        self.scrollIntoView();
                    })
                    .catch(err => {
                        self.processing = false;
                        self.error = err.message || 'Payment could not be captured.';
                    });
                };
            }

            try {
                paypal.Buttons(buttonConfig).render('#mts-paypal-button');
            } catch (e) {
                console.error('PayPal render failed', e);
                this.error = 'Could not render PayPal button.';
            }
        },
    }));
}
