<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap fpp-premium-dashboard">
    <h1><?php _e('Fake Purchase Popup Premium Dashboard', 'fake-purchase-popup'); ?></h1>

    <div class="fpp-analytics">
        <h2><?php _e('Analytics', 'fake-purchase-popup'); ?></h2>
        <div class="fpp-analytics-charts">
            <div class="fpp-chart-container">
                <canvas id="fppDisplaysChart"></canvas>
            </div>
            <div class="fpp-chart-container">
                <canvas id="fppInteractionsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="fpp-premium-templates">
        <h2><?php _e('Premium Templates', 'fake-purchase-popup'); ?></h2>
        <?php
        $templates = FPP_Premium_Templates::get_templates();
        foreach ($templates as $id => $template) :
        ?>
        <div class="fpp-template-preview" data-template="<?php echo esc_attr($id); ?>">
            <h3><?php echo esc_html($template['name']); ?></h3>
            <div class="fpp-template-demo">
                <?php echo wp_kses_post($template['template']); ?>
            </div>
            <button class="button button-primary fpp-apply-template">
                <?php _e('Apply Template', 'fake-purchase-popup'); ?>
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</div>