<?php
// Update the main plugin file to include new files
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/includes/class-freemius.php';
FPP_Freemius();

class FakePurchasePopup {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        if (!$this->check_dependencies()) {
            return;
        }

        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    private function check_dependencies() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p>' . 
                     esc_html__('Fake Purchase Popup requires WooCommerce to be installed and active.', 'fake-purchase-popup') . 
                     '</p></div>';
            });
            return false;
        }

        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p>' . 
                     esc_html__('Fake Purchase Popup requires Elementor to be installed and active.', 'fake-purchase-popup') . 
                     '</p></div>';
            });
            return false;
        }

        return true;
    }

    private function define_constants() {
        define('FPP_VERSION', '1.0.0');
        define('FPP_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('FPP_PLUGIN_URL', plugin_dir_url(__FILE__));
    }

    private function includes() {
        require_once FPP_PLUGIN_DIR . 'includes/class-settings.php';
        require_once FPP_PLUGIN_DIR . 'includes/class-popup-generator.php';
        require_once FPP_PLUGIN_DIR . 'includes/class-elementor-widget.php';
        require_once FPP_PLUGIN_DIR . 'includes/class-premium.php';
        require_once FPP_PLUGIN_DIR . 'includes/class-analytics.php';
        require_once FPP_PLUGIN_DIR . 'includes/premium-templates.php';

        if (FPP_Freemius()->is_premium()) {
            new FPP_Analytics();
        }
    }

    private function init_hooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
    }

    public function enqueue_scripts() {
        wp_enqueue_style(
            'fpp-styles',
            FPP_PLUGIN_URL . 'assets/css/popup.css',
            [],
            FPP_VERSION
        );

        wp_enqueue_script(
            'fpp-script',
            FPP_PLUGIN_URL . 'assets/js/popup.js',
            ['jquery'],
            FPP_VERSION,
            true
        );

        wp_localize_script('fpp-script', 'fppData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fpp-nonce'),
            'frequency' => get_option('fpp_frequency', 30),
            'duration' => get_option('fpp_duration', 5),
            'position' => get_option('fpp_position', 'bottom-left'),
            'isPremium' => FPP_Freemius()->is_premium()
        ]);
    }

    public function admin_scripts($hook) {
        if ('woocommerce_page_wc-settings' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'fpp-admin-styles',
            FPP_PLUGIN_URL . 'assets/css/admin.css',
            [],
            FPP_VERSION
        );
    }
}

function FPP() {
    return FakePurchasePopup::instance();
}

FPP();