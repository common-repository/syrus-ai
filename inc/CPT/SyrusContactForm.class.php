<?php

if(!defined('ABSPATH'))
    exit;

if ( ! class_exists( 'SyrusCPT_ContactForm' ) ) {

    class SyrusCPT_ContactForm {
        private static $cloudflare_turnstile_verify_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

        public function initialize() {
            $this->register();
            $this->registerShortcodes();
            $this->add_actions();
        }

        private function register() {
            $labels = [
                'name'                  => __('Contact form', 'syrus-ai'),
                'singular_name'         => __('Contact form', 'syrus-ai'),
                'menu_name'             => __('Contact forms', 'syrus-ai'),
                'add_new'               => __('Add new', 'syrus-ai'),
                'add_new_item'          => __('Add new contact form', 'syrus-ai'),
                'new_item'              => __('New contact form', 'syrus-ai'),
                'edit_item'             => __('Edit contact form', 'syrus-ai'),
                'view_item'             => __('Show contact form', 'syrus-ai'),
                'all_items'             => __('All contact forms', 'syrus-ai'),
                'search_items'          => __('Search contact forms', 'syrus-ai'),
                'not_found'             => __('Contact form not found', 'syrus-ai'),
                'not_found_in_trash'    => __('Contact form not found inside trash', 'syrus-ai'),
            ];

            $args = [
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => false,
                'show_ui'            => true,     // L'interfaccia utente è ancora accessibile
                'show_in_menu'       => false,    // Nasconde la voce dal menu di amministrazione
                'query_var'          => true,
                'rewrite'            => array('slug' => 'contact-form'),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           =>[],
            ];

            register_post_type('syrus_contact_form', $args);
        }

        private function add_actions() {

            add_action('admin_post_syrus_cpt_add_new_form', [$this, 'addNewForm'], 10);
            add_action('admin_post_syrus_cpt_edit_form', [$this, 'editForm'], 10);
            add_action('admin_post_syrus_cpt_remove_form', [$this, 'removeForm'], 10);
            add_action('admin_post_syrus-contact-form-submit', [$this, 'submitForm'], 10);
            add_action('admin_post_nopriv_syrus-contact-form-submit', [$this, 'submitForm'], 10);

            add_action('wp_ajax_syrus-cpt-contact-form-refresh-preview', [$this, 'refreshFormPreview'], 10);

            add_action('wp_enqueue_scripts', [$this, 'wp_enqueue_scripts'], 90);
        }

        public function addNewForm() {

            if (!isset($_REQUEST['syrus-nonce']) ||  ! wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['syrus-nonce'])), 'syrus_cpt_add_new_form' ) ) {
                wp_redirect(wp_get_referer());
                exit;
            }

            $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : null;
            $recipient = isset($_POST['recipient']) ? sanitize_text_field($_POST['recipient']) : null;
            $fields = isset($_POST['fields']) ? array_map('sanitize_text_field', (array) $_POST['fields']) : [];
            $required_fields = isset($_POST['required_fields']) ? array_map('sanitize_text_field', (array) $_POST['required_fields']) : [];
            $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : null;
            $captcha = isset($_POST['captcha']) ? sanitize_text_field($_POST['captcha']) : 'no_protection';

            $required_fields = array_filter($required_fields, function($item) {
                return $item == "yes";
            });

            $required_fields = array_keys($required_fields);

            $page_id = isset($_POST['page_id']) ? (int) sanitize_text_field($_POST['page_id']) : null;
            $url_privacy_policy = isset($_POST['url_privacy_policy']) ? sanitize_url($_POST['url_privacy_policy']) : null;

            $form_id = wp_insert_post([
                'post_title'    => $title,
                'post_status'   => 'publish',
                'post_author'   => get_current_user_id(),
                'post_type'     => 'syrus_contact_form',
            ]);

            update_post_meta($form_id, 'syrus_contact_form_recipient', $recipient);
            update_post_meta($form_id, 'syrus_contact_form_thank_you_page', $page_id);
            update_post_meta($form_id, 'syrus_contact_form_fields', serialize($fields));
            update_post_meta($form_id, 'syrus_contact_form_required_fields', serialize($required_fields));
            update_post_meta($form_id, 'syrus_contact_form_subject', $subject);
            update_post_meta($form_id, 'syrus_contact_form_captcha', $captcha);
            update_post_meta($form_id, 'syrus_contact_form_url_privacy_policy', $url_privacy_policy);

            return wp_safe_redirect(admin_url('admin.php?page=syrus-ai-admin-page&tab=contact-forms-edit&form=' . $form_id));
        }

        public function editForm() {

            if (!isset($_REQUEST['syrus-nonce']) ||  ! wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['syrus-nonce'])), 'syrus_cpt_mod_form' ) ) {
                wp_redirect(wp_get_referer());
                exit;
            }

            $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : null;
            $recipient = isset($_POST['recipient']) ? sanitize_text_field($_POST['recipient']) : null;
            $fields = isset($_POST['fields']) ? array_map('sanitize_text_field', (array) $_POST['fields']) : [];
            $required_fields = isset($_POST['required_fields']) ? array_map('sanitize_text_field', (array) $_POST['required_fields']) : [];
            $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : null;
            $captcha = isset($_POST['captcha']) ? sanitize_text_field($_POST['captcha']) : 'no_protection';
            $form_id = isset($_POST['form_id']) ? (int) sanitize_text_field($_POST['form_id']) : null;

            $required_fields = array_filter($required_fields, function($item) {
                return $item == "yes";
            });

            $required_fields = array_keys($required_fields);

            $page_id = isset($_POST['page_id']) ? (int) sanitize_text_field($_POST['page_id']) : null;
            $url_privacy_policy = isset($_POST['url_privacy_policy']) ? sanitize_url($_POST['url_privacy_policy']) : null;

            wp_update_post([
                'ID'            => $form_id,
                'post_title'    => $title,
            ]);

            update_post_meta($form_id, 'syrus_contact_form_recipient', $recipient);
            update_post_meta($form_id, 'syrus_contact_form_thank_you_page', $page_id);
            update_post_meta($form_id, 'syrus_contact_form_fields', serialize($fields));
            update_post_meta($form_id, 'syrus_contact_form_required_fields', serialize($required_fields));
            update_post_meta($form_id, 'syrus_contact_form_subject', $subject);
            update_post_meta($form_id, 'syrus_contact_form_captcha', $captcha);
            update_post_meta($form_id, 'syrus_contact_form_url_privacy_policy', $url_privacy_policy);

            return wp_safe_redirect(admin_url('admin.php?page=syrus-ai-admin-page&tab=contact-forms-edit&form=' . $form_id));
        }

        public function removeForm() {

            if (!isset($_REQUEST['syrus-nonce']) ||  ! wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['syrus-nonce'])), 'syrus_cpt_del_form' ) ) {
              wp_redirect(wp_get_referer());
              exit;
            }

            $form_id = isset($_POST['form_id']) ? (int) sanitize_text_field($_POST['form_id']) : null;

            wp_update_post([
                'ID'             => $form_id,
                'post_status'    => 'trash',
            ]);

            return wp_safe_redirect(admin_url('admin.php?page=syrus-theme-page&tab=forms'));
        }

        public function refreshFormPreview() {

            if (!isset($_REQUEST['syrus-nonce']) ||  ! wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['syrus-nonce'])), 'syrus-ajax-cpt' ) ) {
                wp_die();
            }

            $fields = isset($_POST['fields']) && $_POST['fields'] ? array_map('sanitize_text_field', (array) $_POST['fields']) : [];
            $required_fields = isset($_POST['required_fields']) && $_POST['required_fields'] ? array_map('sanitize_text_field', (array) $_POST['required_fields']) : [];

            $html = $this->renderForm([
                'fields' => $fields,
                'required_fields' => $required_fields,
                'isPreview' => true
            ]);

            echo wp_json_encode([
                'status' => "success",
                'html' => $html,
            ]);

            wp_die();
        }

        public function getForms($params = []) {
            return new WP_Query([
                'post_type' => 'syrus_contact_form',
                'posts_per_page' => -1
            ]);
        }

        private function registerShortcodes() {
            add_shortcode('syrus_contact_form', [$this, 'displayShortcode']);
        }

        private function renderForm($params = []) {
            global $syrusAITemplate, $syrusAIPlugin;
            $path = SYRUS_AI_PLUGIN_PATH . "/template-parts/shortcodes/shortcode-contact-form.php";

            $id = isset($params['id']) && $params['id'] ? $params['id'] : null;
            $fields = isset($params['fields']) && $params['fields'] ? $params['fields'] : [];
            $required_fields = isset($params['required_fields']) && $params['required_fields'] ? $params['required_fields'] : [];
            $isPreview = isset($params['isPreview']) && $params['isPreview'] ? true : false;
            $captcha = $id ? (get_post_meta($id, 'syrus_contact_form_captcha', true) ?: 'no_protection') : 'no_protection';
            $url_privacy_policy = $id ? (get_post_meta($id, 'syrus_contact_form_url_privacy_policy', true) ?: '#') : '#';
            $n1 = wp_rand(0,10);
            $n2 = wp_rand(20,60);
            $contact_form_settings = $syrusAIPlugin->get_contact_form_settings();

            $html = $syrusAITemplate->_includi($path,compact('id','fields','required_fields','isPreview', 'n1', 'n2', 'url_privacy_policy', 'captcha', 'contact_form_settings'));

            return $html;
        }

        public function displayShortcode($atts = []) {

            $form_id = $atts['id'];
            $form = $form_id ? get_post($form_id) : null;
            $current_fields = $form ? get_post_meta($form_id, 'syrus_contact_form_fields', true) : null;
            $current_required_fields = $form ? get_post_meta($form_id, 'syrus_contact_form_required_fields', true) : null;
            $url_privacy_policy = $form ? (get_post_meta($form_id, 'url_privacy_policy', true) ?: '#') : '#';

            $current_fields = $current_fields ? unserialize($current_fields) : [];
            $current_required_fields = $current_required_fields ? unserialize($current_required_fields) : [];

            $html = $this->renderForm([
                'id' => $form_id,
                'fields' => $current_fields,
                'required_fields' => $current_required_fields,
            ]);

            return $html;
        }

        public function submitForm() {

            if (!isset($_REQUEST['syrus-nonce']) || ! wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['syrus-nonce'])), 'syrus-contact-form-submit' ) ) {
                wp_redirect(wp_get_referer());
                exit;
            }

            if(!isset($_SESSION['syrus_ai_contact_form_submissions']) || gmdate('Y-m-d H:i:s') >= gmdate('Y-m-d H:i:s', strtotime($_SESSION['syrus_ai_contact_form_submissions']['first_send_at'] . ' + 10 minutes')))
                $_SESSION['syrus_ai_contact_form_submissions'] = [
                    'try'           => 0,
                    'first_send_at' => gmdate('Y-m-d H:i:s'),
                ];


            if($_SESSION['syrus_ai_contact_form_submissions']['try'] > 2)
                return wp_safe_redirect('/');

            $_SESSION['syrus_ai_contact_form_submissions']['try'] = $_SESSION['syrus_ai_contact_form_submissions']['try'] + 1;
            
            $form_id = isset($_POST['contact_form_id']) ? (int) sanitize_text_field($_POST['contact_form_id']) : null;
            $form = get_post($form_id);

            if(!$form)
                return wp_safe_redirect('/');

            $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : null;
            $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : null;
            $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : null;
            $number = isset($_POST['number']) ? sanitize_text_field($_POST['number']) : null;
            $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : null;
            $country = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
            $honeypot = !isset($_POST['honeypot']) || (isset($_POST['honeypot']) && sanitize_text_field($_POST['honeypot']) != "") ? true : false;

            $referer1 = isset($_POST['referer1']) ? sanitize_url($_POST['referer1']) : null;
            $referer2 = isset($_POST['referer2']) ? sanitize_url($_POST['referer2']) : null;

            $input_values = [$first_name, $last_name, $email, $number, $message];
            $unique_values = array_unique($input_values);
            
            $captcha_protection = get_post_meta($form_id, 'syrus_contact_form_captcha', true);
            $cloudflare_turnstile_response = isset($_POST['cf-turnstile-response']) ? sanitize_text_field($_POST['cf-turnstile-response']) : false;


            if($captcha_protection == 'cloudflare_turnstile' && !$cloudflare_turnstile_response) {
                return wp_safe_redirect('/');
            }

            if($captcha_protection == 'cloudflare_turnstile' && self::validate_cloudflare_turnstile_response($cloudflare_turnstile_response) == false) {
                return wp_safe_redirect('/');
            }

            if (count($unique_values) === 1 && end($unique_values) !== null) {
                return wp_safe_redirect('/');
            }

            if($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return wp_safe_redirect('/');
            }

            if($country != 'it-IT') {
                return wp_safe_redirect('/');
            }

            if($honeypot) {
                return wp_safe_redirect('/');
            }

            $thank_you_page = get_post_meta($form_id, 'syrus_contact_form_thank_you_page', true);
            $redirect_url = get_the_permalink($thank_you_page);
            $recipient = get_post_meta($form_id, 'syrus_contact_form_recipient', true);


            // Email body
            $site_url = get_site_url();
            $site_domain = wp_parse_url($site_url, PHP_URL_HOST);

            $subject = get_post_meta($form_id, 'syrus_contact_form_subject', true) ?: '[' . $site_domain . '] ' . __('New contact from your site', 'syrus-ai');
            $html = "";

            $_SESSION['syrus_ai_contact_form_info'] = []; 
            $_SESSION['syrus_ai_contact_form_info']['thank_you_page_id'] = $thank_you_page; 

            if($first_name) {
                $html .= __('First name', 'syrus-ai') . ': ' . $first_name . "<br>";
                $_SESSION['syrus_ai_contact_form_info']['first_name'] = [__('First name', 'syrus-ai'), $first_name];
            } 

            if($last_name) {
                $html .= __('Last name', 'syrus-ai') . ': ' . $last_name . "<br>";
                $_SESSION['syrus_ai_contact_form_info']['last_name'] = [__('Last name', 'syrus-ai'), $last_name];
            } 

            if($email) {
                $html .= __('Email', 'syrus-ai') . ': ' . $email . "<br>";
                $_SESSION['syrus_ai_contact_form_info']['email'] = [__('Email', 'syrus-ai'), $email];
            } 

            if($number) {
                $html .= __('Number', 'syrus-ai') . ': ' . $number . "<br>";
                $_SESSION['syrus_ai_contact_form_info']['number'] = [__('Number', 'syrus-ai'), $number];
            } 

            if($message) {
                $html .= __('Message', 'syrus-ai') . ":<br>" . $message . "<br>";
                $_SESSION['syrus_ai_contact_form_info']['message'] = [__('Message', 'syrus-ai'), $message];
            } 

            if($referer1) {
                $html .= __('Referring page', 'syrus-ai') . ': ' . $referer1 . '<br>';
            }  

            if($referer2) {
                $html .= __('Form page', 'syrus-ai') . ': ' . $referer2 . '<br>';
            }  

            $res = wp_mail($recipient, $subject, $html, [
                'Content-Type: text/html; charset=UTF-8',
            ]);

            return wp_safe_redirect($redirect_url);
        }

        private static function validate_cloudflare_turnstile_response($response_token) {
            global $syrusAIPlugin;            
            
            $contact_form_settings = $syrusAIPlugin->get_contact_form_settings();
            $cloudflare_turnstile_secret_key = isset($contact_form_settings['cloudflare_turnstile']['secret_key']) ? $contact_form_settings['cloudflare_turnstile']['secret_key'] : null;

            $cf_response = wp_remote_post(self::$cloudflare_turnstile_verify_url,[
                'body' => [
                    'secret'    => $cloudflare_turnstile_secret_key,
                    'response'  => $response_token,
                ],
                'timeout' => 45,
                'redirection' => 5,
                'blocking' => true,
                'headers' => [],
                'cookies' => [],
            ]);

            if(is_wp_error($cf_response))
                return false;

            $result = json_decode(wp_remote_retrieve_body($cf_response));

            if(!isset($result->success) || $result->success != 1)
                return false;


            return true;
        }
        
        public function wp_enqueue_scripts() {
            global $post, $syrusAIPlugin;

            $thank_you_page_id = isset($_SESSION['syrus_ai_contact_form_info']['thank_you_page_id']) ? (int) $_SESSION['syrus_ai_contact_form_info']['thank_you_page_id'] : null;

            if($post && $thank_you_page_id === $post->ID) {
                unset($_SESSION['syrus_ai_contact_form_info']['thank_you_page_id']);

                $submitted_info = array_merge($_SESSION['syrus_ai_contact_form_info'], [
                    'labelList'    => __('Data transmitted', 'syrus-ai')
                ]);

                wp_register_script('syrus-ai-cpt-frontend', $syrusAIPlugin->settings['plugin_url'] . 'assets/js/cpt-frontend.js', ['jquery'], md5(uniqid()), [
                    'in_footer' => true
                ]);
                wp_enqueue_script('syrus-ai-cpt-frontend');
                wp_add_inline_script('syrus-ai-cpt-frontend', '
                    const CPT_SESSION = ' . wp_json_encode($submitted_info) . ';
                ', 'before');
            }

        }
    }
}
