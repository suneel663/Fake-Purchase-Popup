<?php
if (!defined('ABSPATH')) {
    exit;
}

class FPP_Popup_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'fpp_popup';
    }

    public function get_title() {
        return __('Fake Purchase Popup', 'fake-purchase-popup');
    }

    public function get_icon() {
        return 'eicon-notification';
    }

    public function get_categories() {
        return ['woocommerce-elements'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Popup Settings', 'fake-purchase-popup'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'popup_template',
            [
                'label' => __('Popup Template', 'fake-purchase-popup'),
                'type' => \Elementor\Controls_Manager::CODE,
                'default' => '{customer_name} from {location} purchased {product_name}',
                'language' => 'html',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'fake-purchase-popup'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'fake-purchase-popup'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .fpp-popup' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="fpp-popup-container" data-template="<?php echo esc_attr($settings['popup_template']); ?>">
            <!-- Popups will be dynamically inserted here -->
        </div>
        <?php
    }
}