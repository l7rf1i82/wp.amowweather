<?php

/**
 * Add a widget to the dashboard.
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function amoweather_dashboard_widget_render() {
    global $wpdb;
    ?>
    <div style="height: 20px; display: flex; align-items: center;">
        <input id="amoweather-toggle" type="checkbox" style="margin-right: 5px;" onclick="amoweather_togglePublicWidget(this)"
            <?php echo get_option('amoweather_toggle_public_widget') == 1 ? 'checked' : ''; ?>>
        <label style="margin-bottom: 4px; margin-right: 4px;" for="amoweather-toggle">Show on public pages</label>
        <span class="amoweather-spinner-wrapper" style="display: none">
            <span class="amoweather-spinner dashicons dashicons-image-rotate"></span>
        </span>
    </div>
    <hr>
    <div>
        <div style="display: flex;">
            <label for="amoweather-custom-css" style="display: inline-block; margin-bottom: 5px; margin-right: 5px;">CSS (for save, click out)</label>
            <span class="amoweather-spinner-wrapper-css" style="display: none">
                <span class="amoweather-spinner dashicons dashicons-image-rotate"></span>
            </span>
        </div>
        <textarea onchange="amoweather_editCssWidget(this)" name="" id="amoweather-custom-css" rows="10" style="width: 100%;"><?php echo file_get_contents(__DIR__ . '/style.css'); ?></textarea>
    </div>
    <h4>History</h4>
    <table class="widefat striped">
        <thead>
        <td>Time</td>
        <td>Location</td>
        <td>Temperature</td>
        </thead>
        <tbody>
        <?php foreach ($wpdb->get_results("SELECT * FROM wp_amoweather ORDER BY `id` DESC LIMIT 5") ?? [] as $history) { ?>
            <tr>
                <td><?php echo $history->time; ?></td>
                <td><?php echo $history->location; ?></td>
                <td><?php echo $history->temperature; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php
}

function amoweather_add_dashboard_widgets()
{
    wp_add_dashboard_widget('amoweather_dashboard_widget', esc_html__( 'Amo Weather Widget', 'amoweather' ), 'amoweather_dashboard_widget_render');
}
add_action( 'wp_dashboard_setup', 'amoweather_add_dashboard_widgets' );