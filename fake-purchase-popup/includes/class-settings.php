<?php
if (!defined('ABSPATH')) {
    exit;
}

class FPP_Settings {
    public function __construct() {
        add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_tab'], 50);
        add_action('woocommerce_settings_tabs_fpp_settings', [$this, 'settings_tab']);
        add_action('woocommerce_update_options_fpp_settings', [$this, 'update_settings']);
    }

    public function add_settings_tab($settings_tabs) {
        $settings_tabs['fpp_settings'] = __('Fake Purchase Popup', 'fake-purchase-popup');
        return $settings_tabs;
    }

    public function get_settings() {
        $settings = [
            'section_title' => [
                'name' => __('Fake Purchase Popup Settings', 'fake-purchase-popup'),
                'type' => 'title',
                'desc' => '',
                'id' => 'fpp_section_title'
            ],
            'enable' => [
                'name' => __('Enable/Disable', 'fake-purchase-popup'),
                'type' => 'checkbox',
                'desc' => __('Enable Fake Purchase Popup', 'fake-purchase-popup'),
                'id'   => 'fpp_enable'
            ],
            'frequency' => [
                'name' => __('Display Frequency', 'fake-purchase-popup'),
                'type' => 'number',
                'desc' => __('Time between popups (seconds)', 'fake-purchase-popup'),
                'id'   => 'fpp_frequency',
                'default' => '30'
            ],
            'duration' => [
                'name' => __('Display Duration', 'fake-purchase-popup'),
                'type' => 'number',
                'desc' => __('How long each popup shows (seconds)', 'fake-purchase-popup'),
                'id'   => 'fpp_duration',
                'default' => '5'
            ],
            'position' => [
                'name' => __('Popup Position', 'fake-purchase-popup'),
                'type' => 'select',
                'options' => [
                    'bottom-left' => __('Bottom Left', 'fake-purchase-popup'),
                    'bottom-right' => __('Bottom Right', 'fake-purchase-popup'),
                    'top-left' => __('Top Left', 'fake-purchase-popup'),
                    'top-right' => __('Top Right', 'fake-purchase-popup')
                ],
                'id'   => 'fpp_position',
                'default' => 'bottom-left'
            ],
            'section_end' => [
                'type' => 'sectionend',
                'id' => 'fpp_section_end'
            ]
        ];

        return apply_filters('fpp_settings', $settings);
    }

    public function settings_tab() {
        woocommerce_admin_fields($this->get_settings());
    }

    public function update_settings() {
        woocommerce_update_options($this->get_settings());
    }
}

new FPP_Settings();