class FakePurchasePopup {
    constructor() {
        this.container = document.querySelector('.fpp-popup-container');
        this.frequency = parseInt(fppData.frequency) || 30;
        this.duration = parseInt(fppData.duration) || 5;
        this.position = fppData.position || 'bottom-left';
        
        this.init();
    }

    init() {
        if (!this.container) return;
        
        this.template = this.container.dataset.template;
        this.startPopupCycle();
    }

    async getFakePurchase() {
        try {
            const response = await fetch(fppData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'get_fake_purchase',
                    nonce: fppData.nonce
                })
            });

            const data = await response.json();
            if (data.success) {
                return data.data;
            }
        } catch (error) {
            console.error('Error fetching fake purchase:', error);
        }
        return null;
    }

    createPopup(data) {
        const popup = document.createElement('div');
        popup.className = `fpp-popup fpp-${this.position}`;
        
        const content = this.template
            .replace('{customer_name}', data.customer_name)
            .replace('{location}', data.location)
            .replace('{product_name}', `<a href="${data.product.url}">${data.product.name}</a>`);

        popup.innerHTML = `
            <div class="fpp-content">
                <img src="${data.product.image}" alt="${data.product.name}" class="fpp-product-image">
                <div class="fpp-text">${content}</div>
                <div class="fpp-time">${data.time}</div>
            </div>
            <button class="fpp-close">&times;</button>
        `;

        popup.querySelector('.fpp-close').addEventListener('click', () => {
            popup.remove();
        });

        return popup;
    }

    async showPopup() {
        const purchaseData = await this.getFakePurchase();
        if (!purchaseData) return;

        const popup = this.createPopup(purchaseData);
        this.container.appendChild(popup);

        setTimeout(() => {
            popup.classList.add('fpp-show');
        }, 100);

        setTimeout(() => {
            popup.classList.remove('fpp-show');
            setTimeout(() => popup.remove(), 500);
        }, this.duration * 1000);
    }

    startPopupCycle() {
        this.showPopup();
        setInterval(() => this.showPopup(), this.frequency * 1000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new FakePurchasePopup();
});