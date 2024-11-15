<?php
if (!defined('ABSPATH')) {
    exit;
}

class FPP_Elementor {
    public function __construct() {
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function register_widgets($widgets_manager) {
        require_once FPP_PLUGIN_DIR . 'includes/widgets/class-popup-widget.php';
        $widgets_manager->register(new \FPP_Popup_Widget());
    }
}

new FPP_Elementor();