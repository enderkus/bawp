<?php
class BAWP_API {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    public function register_routes() {
        register_rest_route('bawp/v1', '/info', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_info'),
            'permission_callback' => array($this, 'check_api_key')
        ));
    }

    public function get_info() {
		if (!function_exists('get_core_updates')) {
            require_once(ABSPATH . 'wp-admin/includes/update.php');
        }
      
		if (!function_exists('get_plugin_updates') || !function_exists('get_plugins')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        // Get WordPress version and update information
        global $wp_version;
        $wp_updates = get_core_updates();
        $wp_update_available = !empty($wp_updates) && $wp_updates[0]->response === 'upgrade';

        // Get list of installed plugins and update information
        $plugins = get_plugins();
        $plugin_updates = get_plugin_updates();

        $plugins_info = array();
        foreach ($plugins as $plugin_file => $plugin_data) {
            $plugins_info[] = array(
                'name' => $plugin_data['Name'],
                'version' => $plugin_data['Version'],
				'author' => $plugin_data['Author'],
                'update_available' => isset($plugin_updates[$plugin_file]),
                'active' => is_plugin_active($plugin_file)
            );
        }

        return array(
            'wp_version' => $wp_version,
            'wp_update_available' => $wp_update_available,
            'plugins' => $plugins_info
        );
    }

    public function check_api_key(WP_REST_Request $request) {
        $api_key = $request->get_param('api_key');
        $stored_api_key = get_option('bawp_api_key');
        return $api_key && $api_key === $stored_api_key;
    }
}
