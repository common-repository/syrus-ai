<?php

if (!defined('ABSPATH')) {
    exit;
}

class SyrusAI_API {

    public function initialize() :void {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    public function register_routes() :void {
        register_rest_route('syrusai/v1', '/newsmaking_cron', array(
            'methods' => 'GET',
            'callback' => array($this, 'newsmaking_cron'),
            'permission_callback' => '__return_true',
        ));
    }

    public function newsmaking_cron() {
        global $wpdb;
        global $syrusAICronAdapter;
        global $syrusAISmtp;

        $status = get_option("syrus-ai-enable-cron-newsmaking", true);

        if($status == "1"){

            $response = $syrusAICronAdapter->check_cron_newsmaking();

            return rest_ensure_response($response);
    
        } else {
            $response = array(
                'success' => "success without execute method",
            );
        }
    }
}