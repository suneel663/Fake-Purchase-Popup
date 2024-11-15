<?php
if (!defined('ABSPATH')) {
    exit;
}

class FPP_Premium_Templates {
    public static function get_templates() {
        return [
            'modern-card' => [
                'name' => __('Modern Card', 'fake-purchase-popup'),
                'template' => self::get_modern_card_template()
            ],
            'minimal' => [
                'name' => __('Minimal', 'fake-purchase-popup'),
                'template' => self::get_minimal_template()
            ],
            'gradient' => [
                'name' => __('Gradient', 'fake-purchase-popup'),
                'template' => self::get_gradient_template()
            ],
            'dark-mode' => [
                'name' => __('Dark Mode', 'fake-purchase-popup'),
                'template' => self::get_dark_mode_template()
            ],
            'bordered' => [
                'name' => __('Bordered', 'fake-purchase-popup'),
                'template' => self::get_bordered_template()
            ],
            'compact' => [
                'name' => __('Compact', 'fake-purchase-popup'),
                'template' => self::get_compact_template()
            ],
            'glass' => [
                'name' => __('Glass', 'fake-purchase-popup'),
                'template' => self::get_glass_template()
            ],
            'accent' => [
                'name' => __('Accent', 'fake-purchase-popup'),
                'template' => self::get_accent_template()
            ]
        ];
    }

    private static function get_modern_card_template() {
        return '<div class="fpp-modern-card">
            <div class="fpp-modern-content">
                <img src="{product_image}" alt="{product_name}" class="fpp-product-image">
                <div class="fpp-info">
                    <div class="fpp-name">{customer_name}</div>
                    <div class="fpp-location">{location}</div>
                    <div class="fpp-product">{product_name}</div>
                    <div class="fpp-time">{time}</div>
                </div>
            </div>
        </div>';
    }

    private static function get_minimal_template() {
        return '<div class="fpp-minimal">
            <div class="fpp-minimal-content">
                <span class="fpp-customer">{customer_name}</span>
                <span class="fpp-action">purchased</span>
                <span class="fpp-product">{product_name}</span>
            </div>
        </div>';
    }

    private static function get_gradient_template() {
        return '<div class="fpp-gradient">
            <div class="fpp-gradient-content">
                <img src="{product_image}" alt="{product_name}">
                <div class="fpp-details">
                    <div class="fpp-header">{customer_name} from {location}</div>
                    <div class="fpp-product">{product_name}</div>
                    <div class="fpp-time">{time}</div>
                </div>
            </div>
        </div>';
    }

    private static function get_dark_mode_template() {
        return '<div class="fpp-dark-mode">
            <div class="fpp-dark-content">
                <img src="{product_image}" alt="{product_name}">
                <div class="fpp-info">
                    <div class="fpp-purchase-info">
                        <span class="fpp-name">{customer_name}</span>
                        <span class="fpp-location">{location}</span>
                    </div>
                    <div class="fpp-product">{product_name}</div>
                </div>
            </div>
        </div>';
    }

    private static function get_bordered_template() {
        return '<div class="fpp-bordered">
            <div class="fpp-bordered-content">
                <div class="fpp-image-wrapper">
                    <img src="{product_image}" alt="{product_name}">
                </div>
                <div class="fpp-info">
                    <div class="fpp-customer">{customer_name}</div>
                    <div class="fpp-product">{product_name}</div>
                    <div class="fpp-meta">
                        <span class="fpp-location">{location}</span>
                        <span class="fpp-time">{time}</span>
                    </div>
                </div>
            </div>
        </div>';
    }

    private static function get_compact_template() {
        return '<div class="fpp-compact">
            <img src="{product_image}" alt="{product_name}">
            <div class="fpp-compact-info">
                <div class="fpp-text">
                    {customer_name} purchased {product_name}
                </div>
                <div class="fpp-meta">{time}</div>
            </div>
        </div>';
    }

    private static function get_glass_template() {
        return '<div class="fpp-glass">
            <div class="fpp-glass-content">
                <img src="{product_image}" alt="{product_name}">
                <div class="fpp-glass-info">
                    <div class="fpp-customer">{customer_name}</div>
                    <div class="fpp-product">{product_name}</div>
                    <div class="fpp-location">{location}</div>
                </div>
            </div>
        </div>';
    }

    private static function get_accent_template() {
        return '<div class="fpp-accent">
            <div class="fpp-accent-stripe"></div>
            <div class="fpp-accent-content">
                <img src="{product_image}" alt="{product_name}">
                <div class="fpp-info">
                    <div class="fpp-purchase">
                        <span class="fpp-name">{customer_name}</span>
                        <span class="fpp-product">{product_name}</span>
                    </div>
                    <div class="fpp-meta">
                        <span class="fpp-location">{location}</span>
                        <span class="fpp-time">{time}</span>
                    </div>
                </div>
            </div>
        </div>';
    }
}