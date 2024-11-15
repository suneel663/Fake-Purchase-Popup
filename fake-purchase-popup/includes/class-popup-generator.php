<?php
if (!defined('ABSPATH')) {
    exit;
}

class FPP_Popup_Generator {
    private $fake_names = [
        'John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Williams',
        'David Brown', 'Emily Davis', 'Michael Wilson', 'Lisa Anderson'
    ];

    private $fake_locations = [
        'New York', 'Los Angeles', 'Chicago', 'Houston',
        'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego'
    ];

    public function __construct() {
        add_action('wp_ajax_get_fake_purchase', [$this, 'get_fake_purchase']);
        add_action('wp_ajax_nopriv_get_fake_purchase', [$this, 'get_fake_purchase']);
    }

    public function get_fake_purchase() {
        check_ajax_referer('fpp-nonce', 'nonce');

        $products = $this->get_random_product();
        if (!$products) {
            wp_send_json_error('No products found');
            return;
        }

        $data = [
            'customer_name' => $this->fake_names[array_rand($this->fake_names)],
            'location' => $this->fake_locations[array_rand($this->fake_locations)],
            'product' => $products,
            'time' => sprintf(__('%d minutes ago', 'fake-purchase-popup'), rand(1, 60))
        ];

        wp_send_json_success($data);
    }

    private function get_random_product() {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => 1,
            'orderby' => 'rand'
        ];

        $products = get_posts($args);

        if (empty($products)) {
            return false;
        }

        $product = wc_get_product($products[0]->ID);

        return [
            'name' => $product->get_name(),
            'url' => get_permalink($product->get_id()),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
            'price' => $product->get_price_html()
        ];
    }
}

new FPP_Popup_Generator();