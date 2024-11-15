<?php
if (!defined('ABSPATH')) {
    exit;
}

class FPP_Premium {
    private static $instance = null;
    private $is_premium = false;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->is_premium = $this->check_license();
        add_action('admin_menu', [$this, 'add_premium_menu']);
        add_action('wp_ajax_fpp_save_analytics', [$this, 'save_analytics']);
        
        if ($this->is_premium) {
            $this->init_premium_features();
        }
    }

    private function check_license() {
        $license_key = get_option('fpp_license_key');
        // TODO: Implement actual license validation
        return !empty($license_key);
    }

    private function init_premium_features() {
        add_filter('fpp_popup_templates', [$this, 'add_premium_templates']);
        add_filter('fpp_display_rules', [$this, 'add_premium_rules']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_premium_assets']);
    }

    public function add_premium_menu() {
        add_submenu_page(
            'woocommerce',
            __('FPP Premium', 'fake-purchase-popup'),
            __('FPP Premium', 'fake-purchase-popup'),
            'manage_options',
            'fpp-premium',
            [$this, 'render_premium_page']
        );
    }

    public function render_premium_page() {
        if (!$this->is_premium) {
            $this->render_upgrade_page();
            return;
        }

        include FPP_PLUGIN_DIR . 'templates/premium-dashboard.php';
    }

    private function render_upgrade_page() {
        include FPP_PLUGIN_DIR . 'templates/upgrade.php';
    }

    public function add_premium_templates($templates) {
        if (!$this->is_premium) {
            return $templates;
        }

        $premium_templates = [
            'modern-card' => [
                'name' => __('Modern Card', 'fake-purchase-popup'),
                'template' => '<div class="fpp-modern-card">{content}</div>'
            ],
            'minimal' => [
                'name' => __('Minimal', 'fake-purchase-popup'),
                'template' => '<div class="fpp-minimal">{content}</div>'
            ],
            // Add more premium templates
        ];

        return array_merge($templates, $premium_templates);
    }

    public function add_premium_rules($rules) {
        if (!$this->is_premium) {
            return $rules;
        }

        $premium_rules = [
            'device' => [
                'mobile' => true,
                'tablet' => true,
                'desktop' => true
            ],
            'timing' => [
                'delay' => 0,
                'scroll_percentage' => 0
            ],
            'pages' => [
                'product' => true,
                'cart' => true,
                'checkout' => true
            ]
        ];

        return array_merge($rules, $premium_rules);
    }

    public function enqueue_premium_assets() {
        if (!$this->is_premium) {
            return;
        }

        wp_enqueue_style(
            'fpp-premium-styles',
            FPP_PLUGIN_URL . 'assets/css/premium.css',
            [],
            FPP_VERSION
        );

        wp_enqueue_script(
            'fpp-premium-script',
            FPP_PLUGIN_URL . 'assets/js/premium.js',
            ['jquery', 'fpp-script'],
            FPP_VERSION,
            true
        );
    }

    public function save_analytics() {
        if (!$this->is_premium || !check_ajax_referer('fpp-premium-nonce', 'nonce', false)) {
            wp_send_json_error('Unauthorized');
            return;
        }

        $data = [
            'displays' => intval($_POST['displays'] ?? 0),
            'clicks' => intval($_POST['clicks'] ?? 0),
            'dismissals' => intval($_POST['dismissals'] ?? 0),
            'date' => current_time('mysql')
        ];

        update_option('fpp_analytics_' . date('Y-m-d'), $data);
        wp_send_json_success();
    }
}

function FPP_Premium() {
    return FPP_Premium::instance();
}