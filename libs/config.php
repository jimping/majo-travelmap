<?php

add_action('admin_menu', 'travelmap_register_settings_page');

function travelmap_register_settings_page()
{
    add_options_page(
        __('TravelMap Settings', 'travelmap'), // Seitentitel
        __('TravelMap', 'travelmap'), // Menütitel
        'manage_options', // Capability
        'travelmap-settings', // Menü-Slug
        'travelmap_settings_page' // Callback-Funktion
    );
}

function travelmap_settings_page()
{
    ?>
    <div class="wrap">
        <h2><?php echo esc_html__('TravelMap Settings', 'travelmap'); ?></h2>
        <form method="post" action="options.php">
            <?php settings_fields('travelmap-settings-group'); ?>
            <?php do_settings_sections('travelmap-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo esc_html__('Google API Key', 'travelmap'); ?></th>
                    <td><input type="password" name="travelmap_google_api_key" value="<?php echo esc_attr(get_option('travelmap_google_api_key')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'travelmap_register_settings');

function travelmap_register_settings()
{
    register_setting('travelmap-settings-group', 'travelmap_google_api_key');
}
