import Chart from 'chart.js/auto';

class FPPAnalytics {
    constructor() {
        this.initCharts();
    }

    async initCharts() {
        const data = await this.fetchAnalytics();
        if (!data) return;

        this.createDisplaysChart(data);
        this.createInteractionsChart(data);
    }

    async fetchAnalytics() {
        try {
            const response = await fetch(fppData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'fpp_get_analytics',
                    nonce: fppData.premiumNonce
                })
            });

            const result = await response.json();
            return result.success ? result.data : null;
        } catch (error) {
            console.error('Error fetching analytics:', error);
            return null;
        }
    }

    createDisplaysChart(data) {
        const ctx = document.getElementById('fppDisplaysChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.dates,
                datasets: [{
                    label: 'Popup Displays',
                    data: data.displays,
                    borderColor: '#4CAF50',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Popup Displays'
                    }
                }
            }
        });
    }

    createInteractionsChart(data) {
        const ctx = document.getElementById('fppInteractionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.dates,
                datasets: [
                    {
                        label: 'Clicks',
                        data: data.clicks,
                        backgroundColor: '#2196F3'
                    },
                    {
                        label: 'Dismissals',
                        data: data.dismissals,
                        backgroundColor: '#FF9800'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'User Interactions'
                    }
                }
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.fpp-analytics-chart')) {
        new FPPAnalytics();
    }
});