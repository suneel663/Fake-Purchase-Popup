class FPPPremium extends FakePurchasePopup {
    constructor() {
        super();
        this.analytics = {
            displays: 0,
            clicks: 0,
            dismissals: 0
        };
        this.initPremiumFeatures();
    }

    initPremiumFeatures() {
        this.initAnalytics();
        this.initDisplayRules();
    }

    initAnalytics() {
        this.container.addEventListener('click', (e) => {
            if (e.target.matches('.fpp-popup a')) {
                this.trackEvent('clicks');
            } else if (e.target.matches('.fpp-close')) {
                this.trackEvent('dismissals');
            }
        });
    }

    async trackEvent(type) {
        this.analytics[type]++;
        
        try {
            await fetch(fppData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'fpp_save_analytics',
                    nonce: fppData.premiumNonce,
                    ...this.analytics
                })
            });
        } catch (error) {
            console.error('Error saving analytics:', error);
        }
    }

    initDisplayRules() {
        const rules = fppData.displayRules || {};
        
        if (rules.device && !this.checkDeviceRule(rules.device)) {
            return;
        }

        if (rules.timing && rules.timing.delay > 0) {
            setTimeout(() => this.startPopupCycle(), rules.timing.delay * 1000);
            return;
        }

        if (rules.timing && rules.timing.scroll_percentage > 0) {
            this.initScrollTrigger(rules.timing.scroll_percentage);
            return;
        }

        super.startPopupCycle();
    }

    checkDeviceRule(deviceRules) {
        const isMobile = window.innerWidth <= 768;
        const isTablet = window.innerWidth <= 1024 && window.innerWidth > 768;
        const isDesktop = window.innerWidth > 1024;

        return (isMobile && deviceRules.mobile) ||
               (isTablet && deviceRules.tablet) ||
               (isDesktop && deviceRules.desktop);
    }

    initScrollTrigger(percentage) {
        let triggered = false;
        window.addEventListener('scroll', () => {
            if (triggered) return;

            const scrolled = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
            if (scrolled >= percentage) {
                triggered = true;
                this.startPopupCycle();
            }
        });
    }

    createPopup(data) {
        const popup = super.createPopup(data);
        this.trackEvent('displays');
        return popup;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (fppData.isPremium) {
        new FPPPremium();
    }
});