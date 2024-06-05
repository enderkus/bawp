<?php
class BAWP_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
    }

    public function add_admin_menu() {
        add_options_page(
            'BAWP',
            'BAWP',
            'manage_options',
            'bawp',
            array($this, 'options_page')
        );
    }

    public function settings_init() {
        register_setting('bawp', 'bawp_api_key', array(
            'sanitize_callback' => array($this, 'sanitize_api_key'),
        ));

        add_settings_section(
            'bawp_section',
            __('BAWP Settings', 'bawp'),
            null,
            'bawp'
        );

        add_settings_field(
            'bawp_api_key',
            __('API Key', 'bawp'),
            array($this, 'api_key_render'),
            'bawp',
            'bawp_section'
        );

        if (!get_option('bawp_api_key')) {
            update_option('bawp_api_key', wp_generate_password(32, false));
        }
    }

    public function sanitize_api_key($input) {
        if (isset($_POST['bawp_nonce']) && !wp_verify_nonce($_POST['bawp_nonce'], 'bawp_options_save')) {
            add_settings_error('bawp_api_key', 'bawp_nonce_error', __('Nonce verification failed', 'bawp'), 'error');
            return get_option('bawp_api_key');
        }
        return sanitize_text_field($input);
    }

    public function api_key_render() {
        $api_key = get_option('bawp_api_key');
        echo "<input type='text' name='bawp_api_key' value='$api_key' readonly />";
        echo "<p>" . __('To generate a new API key, delete the current one and save the settings.', 'bawp') . "</p>";
    }

    public function options_page() {
        echo '<form action="options.php" method="post">';
        settings_fields('bawp');
        do_settings_sections('bawp');
        wp_nonce_field('bawp_options_save', 'bawp_nonce'); // Nonce ekleniyor
        submit_button();
        echo '</form>';
    }
}
