<?php
if (!defined('ABSPATH')) {
    exit;
}

class FPP_Freemius {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->init_freemius();
    }

    private function init_freemius() {
        if (!function_exists('fs_dynamic_init')) {
            require_once WP_PLUGIN_DIR . '/freemius/start.php';
        }

        fs_dynamic_init([
            'id' => '12345', // Replace with your actual Freemius plugin ID
            'slug' => 'fake-purchase-popup',
            'type' => 'plugin',
            'public_key' => 'pk_xxx', // Replace with your actual public key
            'is_premium' => false,
            'has_paid_plans' => true,
            'has_addons' => false,
            'menu' => [
                'slug' => 'fpp-settings',
                'contact' => false,
                'support' => true,
                'parent' => [
                    'slug' => 'woocommerce',
                ],
            ],
        ]);
    }

    public function is_premium() {
        return fs_is_plan__premium_only('professional');
    }
}

function FPP_Freemius() {
    return FPP_Freemius::instance();
}