<?php
if (!defined('ABSPATH')) {
    exit;
}

class FPP_Analytics {
    public function __construct() {
        add_action('wp_ajax_fpp_get_analytics', [$this, 'get_analytics']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_analytics_assets']);
    }

    public function enqueue_analytics_assets($hook) {
        if ('woocommerce_page_fpp-premium' !== $hook) {
            return;
        }

        wp_enqueue_script(
            'chart-js',
            'https://cdn.jsdelivr.net/npm/chart.js',
            [],
            '4.4.0',
            true
        );

        wp_enqueue_script(
            'fpp-charts',
            FPP_PLUGIN_URL . 'assets/js/charts.js',
            ['chart-js'],
            FPP_VERSION,
            true
        );

        wp_localize_script('fpp-charts', 'fppData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'premiumNonce' => wp_create_nonce('fpp-premium-nonce')
        ]);
    }

    public function get_analytics() {
        check_ajax_referer('fpp-premium-nonce', 'nonce');

        if (!FPP_Freemius()->is_premium()) {
            wp_send_json_error('Premium feature only');
            return;
        }

        $days = 30;
        $end_date = current_time('Y-m-d');
        $start_date = date('Y-m-d', strtotime("-$days days"));

        $analytics = [];
        $dates = [];
        $displays = [];
        $clicks = [];
        $dismissals = [];

        for ($i = 0; $i < $days; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $data = get_option('fpp_analytics_' . $date, [
                'displays' => 0,
                'clicks' => 0,
                'dismissals' => 0
            ]);

            array_unshift($dates, date('M j', strtotime($date)));
            array_unshift($displays, $data['displays']);
            array_unshift($clicks, $data['clicks']);
            array_unshift($dismissals, $data['dismissals']);
        }

        wp_send_json_success([
            'dates' => $dates,
            'displays' => $displays,
            'clicks' => $clicks,
            'dismissals' => $dismissals
        ]);
    }
}