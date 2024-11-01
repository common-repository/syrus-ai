<?php

if(!defined('ABSPATH'))
    exit;

class SyrusAI_Plugin {
    public $settings;
    public $plugin_prefix = "SYRUS_AI_";
    public $syrus_theme_active = false;
    public $plugin_activated = false;

    function __construct() {

    }

    public function initialize() {

        $this->define_all_consts();


        $this->settings['plugin_main_file'] = SYRUS_AI_PLUGIN_MAIN_FILE;
        $this->settings['plugin_basename'] = SYRUS_AI_PLUGIN_BASENAME;
        $this->settings['plugin_path'] = SYRUS_AI_PLUGIN_PATH;
        $this->settings['plugin_url'] = SYRUS_AI_PLUGIN_URL;
        $this->settings['plugin_version'] = get_file_data(SYRUS_AI_PLUGIN_MAIN_FILE, ['Version'], 'plugin')[0];

        $current_active_theme = wp_get_theme();
        $current_theme_name = $current_active_theme->parent() ? $current_active_theme->parent()->get('Name') : $current_active_theme->get('Name');

        if($current_theme_name === "Syrus")
            $this->syrus_theme_active = true;

        $this->register_activation_deactivation_hooks();

        $this->add_actions();

        $this->add_filters();

        $this->initClasses();

    }

    /* UTILITY PRIVATE METHODS */
    private function initClasses() {
        $this->initSyrusAITemplate();
        $this->initSyrusAIAjax();
        $this->initSyrusAIAI();
        $this->initSyrusAISocialAdapter();
        $this->initSyrusAIAPI();
        $this->initSyrusCronAdapter();
        $this->initSyrusAISmtp();
        $this->initSyrusAINewsMaking();
        $this->initSyrusCPT_ContactForm();
    }

    private function initSyrusAITemplate() {
        global $syrusAITemplate;

        if(!isset($syrusAITemplate)) {
            $syrusAITemplate = new SyrusAI_Template();
            $syrusAITemplate->initialize($this->settings, $this->syrus_theme_active);
        }

        return $syrusAITemplate;
    }

    private function initSyrusAIAjax() {
        global $syrusAIAjax;

        if(!isset($syrusAIAjax)) {
            $syrusAIAjax = new SyrusAI_Ajax();
            $syrusAIAjax->initialize($this->settings, $this->syrus_theme_active);
        }

        return $syrusAIAjax;
    }


    private function initSyrusAIAI() {
        global $syrusAIAI;

        if(!isset($syrusAIAI)) {
            $syrusAIAI = new SyrusAI_AI();
            $syrusAIAI->initialize();
        }

        return $syrusAIAI;
    }

    private function initSyrusAISocialAdapter() {
        global $syrusAISocialAdapter;

        if(!isset($syrusAISocialAdapter)) {
            $syrusAISocialAdapter = new SyrusSocialAdapter();
            $syrusAISocialAdapter->initialize($this->settings);
        }

        return $syrusAISocialAdapter;
    }

    private function initSyrusAIAPI() {
        global $syrusAIAPI;

        if(!isset($syrusAIAPI)) {
            $syrusAIAPI = new SyrusAI_API();
            $syrusAIAPI->initialize($this->settings);
        }

        return $syrusAIAPI;
    }

    private function initSyrusCronAdapter() {
        global $syrusAICronAdapter;

        if(!isset($syrusAICronAdapter)) {
            $syrusAICronAdapter = new SyrusCronAdapter();
            $syrusAICronAdapter->initialize($this->settings);
        }

        return $syrusAICronAdapter;
    }

    private function initSyrusAISmtp() {
        global $syrusAISmtp;

        if(!isset($syrusAISmtp)) {
            $syrusAISmtp = new SyrusSmtpServer();
            $syrusAISmtp->initialize($this->settings);
        }

        return $syrusAISmtp;
    }

    private function initSyrusAINewsMaking() {
        global $syrusAINewsMaking;

        if(!isset($syrusAINewsMaking)) {
            $syrusAINewsMaking = new SyrusAI_NewsMaking();
            $syrusAINewsMaking->initialize();
        }

        return $syrusAINewsMaking;
    }

    private function initSyrusCPT_ContactForm() {
        global $syrusCPT_ContactForm;

        if(!isset($syrusCPT_ContactForm)) {
            $syrusCPT_ContactForm = new SyrusCPT_ContactForm();
        }

        return $syrusCPT_ContactForm;
    }

    private function define( $name, $value = true ) {
        if(!defined($name))
            define($name,$value);
    }

    /* INITIALIZATION HOOKS */
    private function define_all_consts() {

        $this->define( $this->plugin_prefix . "PLUGIN_MAIN_FILE", dirname(__FILE__,2) . '/syrus-ai.php');
        $this->define( $this->plugin_prefix . "PLUGIN_BASENAME", plugin_basename(SYRUS_AI_PLUGIN_MAIN_FILE));
        $this->define( $this->plugin_prefix . "PLUGIN_PATH", dirname(__FILE__,2) . '/');
        $this->define( $this->plugin_prefix . "PLUGIN_URL", plugin_dir_url(SYRUS_AI_PLUGIN_MAIN_FILE));
    }

    private function register_activation_deactivation_hooks() {
        register_activation_hook($this->settings['plugin_main_file'], [$this, 'activation_hook']);
        register_deactivation_hook($this->settings['plugin_main_file'], [$this, 'deactivation_hook']);
    }

    public function activation_hook() {
        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $table_name = $wpdb->prefix . 'newsmaking';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT NOT NULL AUTO_INCREMENT,
            `title` TEXT NOT NULL,
            `link` TEXT NOT NULL,
            `language` TEXT NOT NULL,
            `category` TEXT,
            `country` TEXT,
            `keywords` TEXT,
            `domain` TEXT,
            `date` DATE NOT NULL,
            PRIMARY KEY (`id`)) $charset_collate;";

        dbDelta($sql);

        $table_name = $wpdb->prefix . 'translate_and_publish';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT NOT NULL AUTO_INCREMENT,
            `id_wp` TEXT NOT NULL ,
            `date` TEXT NOT NULL ,
            `link` TEXT NOT NULL ,
            `title` TEXT NOT NULL ,
            `content` TEXT NOT NULL ,
            `categories` TEXT NOT NULL ,
            `tags` TEXT NOT NULL,
            `media` TEXT NOT NULL,
            `domain` TEXT NOT NULL,
            `post_id` TEXT NULL,
            PRIMARY KEY (`id`)) $charset_collate;";

        dbDelta($sql);

        $table_name = $wpdb->prefix . 'syrus_ai_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT NOT NULL AUTO_INCREMENT,
            `name` TEXT NOT NULL ,
            `value` TEXT NOT NULL ,
            `hour` TEXT NOT NULL ,
            `date` TEXT NOT NULL ,
            PRIMARY KEY (`id`)) $charset_collate;";

        dbDelta($sql);


        // Creazione tabella logs be
        $table_name = $wpdb->prefix . 'syrus_ai_be_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT NOT NULL AUTO_INCREMENT,
            `type` varchar(512) NOT NULL ,
            `generic_value` TEXT NULL ,
            `request` TEXT NULL ,
            `response` TEXT NULL ,
            `created_at` DATETIME NOT NULL ,
            PRIMARY KEY (`id`)) $charset_collate;";

        dbDelta($sql);


        if($this->plugin_activated)
            return true;

        $url = "https://developers.syrus.it/api/wp/v1/install";

        $headers = [];

        $api_res = wp_remote_post( $url,[
            'headers' => $headers,
            'body' => array(
                'domain' => get_site_url(),
            ),
            'sslverify' => false,
        ]);

        $response_body = wp_remote_retrieve_body($api_res);
        $data = json_decode($response_body, true);
        $this->plugin_activated = true;

        $newsmaking_settings = [" ", " "];

        $cron_settings = [
            "mode" => "",
            "email" => ""
        ];

        update_option("syrus_ai_bearer_token", $data['bearer_token']);
        update_option("syrus-ai-enable-social", "1");
        update_option("syrus-ai-enable-cron-newsmaking", "0");
        update_option("syrus-ai-newsmaking-settigs", $newsmaking_settings);
        update_option("syrus-ai-newsmaking-cron-settings", $cron_settings);
        update_option("syrus-ai-cron-hour", "00:00");
        update_option("syrus-ai-jet-lag", "UTC");
    }

    public function deactivation_hook() {
        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $table_name = $wpdb->prefix . 'newsmaking';
        $query = "DROP TABLE IF EXISTS $table_name";
        dbDelta($query);

        $table_name = $wpdb->prefix . 'translate_and_publish';
        $query = "DROP TABLE IF EXISTS $table_name";
        dbDelta($query);

        $table_name = $wpdb->prefix . 'syrus_ai_logs';
        $query = "DROP TABLE IF EXISTS $table_name";
        dbDelta($query);

        $table_name = $wpdb->prefix . 'syrus_ai_be_logs';
        $query = "DROP TABLE IF EXISTS $table_name";
        dbDelta($query);

        $url = "https://developers.syrus.it/api/wp/v1/uninstall";

        $token = get_option("syrus_ai_bearer_token");

        $headers = array(
            'Authorization' => 'Bearer ' . $token,
        );

        $api_res = wp_remote_post( $url,[
            'headers' => $headers,
            'body' => array(
                'domain' => get_site_url(),
            ),
            'sslverify' => false,
        ]);

        $response_body = wp_remote_retrieve_body($api_res);
        $data = json_decode($response_body, true);

        delete_option("syrus_ai_newsapi_token");
        delete_option("syrus-ai-linkedin-settings");
        delete_option("syrus-ai-general-settings");
        delete_option("syrus_ai_bearer_token");
        delete_option("syrus-ai-connected-social");
        delete_option("syrus-ai-enable-cron-newsmaking");
        delete_option("syrus-ai-newsmaking-cron-settings");
        delete_option("syrus-ai-cron-hour");
        delete_option("syrus-ai-jet-lag");
    }

    private function add_actions() {

        // Use priority 20 because in the official Syrus theme the same action was added with priority 10
        add_action( 'init', [$this, 'init'], 20);

        add_action( 'admin_menu', [$this,"admin_menu"], 20);

        add_action( 'admin_enqueue_scripts', [$this,'admin_enqueue_scripts'], 10);

        add_action( 'publish_post',[$this,'publish_post'], 10, 3);

        // Use priority 20 because in the official Syrus theme the same action was added with priority 10
        add_action('post_submitbox_misc_actions',[$this,'post_submitbox_misc_actions'], 20);

        add_action('enqueue_block_editor_assets',[$this,'enqueue_block_editor_assets']);

        // Custom actions
        add_action('syrus_ai_async_post_sharing',[$this,'async_post_sharing'],10,2);

        add_action('add_meta_boxes',[$this,'add_metabox_article_generator_content'],10,2);

    }

    private function add_filters() {
        add_filter( 'plugin_action_links_' . $this->settings['plugin_basename'], [$this,"plugin_action_links"], 10, 4);
        add_filter( 'http_request_timeout', [$this, 'plugin_timeout_extend'] );
        add_filter( 'get_the_excerpt', 'strip_shortcodes', 20 );
        add_filter( 'upload_mimes', [$this, 'upload_mimes']);
    }

    /* WORDPRESS ACTIONS */
    public function admin_menu() {

        if($this->syrus_theme_active) {
            $parent_menu_slug = "syrus-theme-page";

            add_submenu_page(
                $parent_menu_slug,
                "AI Support",
                "AI Support",
                "edit_others_posts",
                "syrus-ai-admin-page",
                [$this,'plugin_admin_page'],
                99
            );

        } else {
            add_menu_page(
                "Syrus AI Support",
                "Syrus AI",
                "edit_others_posts",
                "syrus-ai-admin-page",
                [$this,'plugin_admin_page'],
                $this->settings['plugin_url'] . "/assets/images/logo-cropped-16x16.png",
                80
            );
        }

    }

    public function admin_enqueue_scripts() {
        global $wp_styles;
        global $pagenow;

        $page = filter_input(
            INPUT_GET,
            'page',
            FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            ['options' => 'esc_html']
        );

        $tab = filter_input(
            INPUT_GET,
            'tab',
            FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            ['options' => 'esc_html']
        );

        $action = filter_input(
            INPUT_GET,
            'action',
            FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            ['options' => 'esc_html']
        );

        $post = filter_input(
            INPUT_GET,
            'post',
            FILTER_SANITIZE_NUMBER_INT,
            ['options' => 'esc_html']
        );

        $page = $page ?: '';
        $registered = $wp_styles->registered;
        $queued = $wp_styles->queue;

        $bootstrap_already_queued = false;

        foreach($queued as $qu) {
            if(strpos($qu, 'bootstrap') !== false)
                $bootstrap_already_queued = true;
        }

        $is_edit_post = (is_admin() && isset($action) && $action == 'edit' && isset($post)) || $pagenow == 'post-new.php';

        if($page == "syrus-ai-admin-page" || $is_edit_post) {
            wp_enqueue_style('syrus-ai-admin-css', $this->settings['plugin_url'] . "/assets/css/admin.css", [], md5(uniqid()));
            wp_enqueue_script('syrus-ai-admin-js', $this->settings['plugin_url'] . "/assets/js/admin.js", ['jquery'], md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_add_inline_script('syrus-ai-admin-js','
                const argsAdminAI = ' . wp_json_encode([
                    'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
                    'nonce' => wp_create_nonce('syrus-ai-genera-nonce'),
                ]) . ';
            ');
        }

        if($page == "syrus-ai-admin-page" && $tab == "ai-newsmaking") {
            wp_enqueue_script('syrus-ai-ai-newsmaking-js', $this->settings['plugin_url'] . '/assets/js/ai-newsmaking.js', ['jquery'], md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_add_inline_script('syrus-ai-ai-newsmaking-js','
                const argsNewsMaking = '.wp_json_encode([
                    'spinnerUrl' =>  $this->settings['plugin_url'] . 'assets/images/syrus-ai-spinner.svg',
                    'ajaxUrl' => esc_url(admin_url('admin-ajax.php')),
                    'logoUrl' => $this->settings['plugin_url'] . 'assets/images/icon-128x128.png',
                ]) . ';
            ');
        }

        if($page == "syrus-ai-admin-page" && $tab == "settings-ai-translate") {
            wp_enqueue_script('syrus-ai-settings-ai-translate-js', $this->settings['plugin_url'] . '/assets/js/settings-ai-translate.js', ['jquery'], md5(uniqid()), [
                'in_footer' => true,
            ]);

            wp_add_inline_script('syrus-ai-settings-ai-translate-js','
                const argsSettingsAITranslate = '.wp_json_encode([
                    'spinnerUrl' =>  $this->settings['plugin_url'] . 'assets/images/syrus-ai-spinner.svg',
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'logoUrl' => $this->settings['plugin_url'] . 'assets/images/icon-128x128.png',
            ]).';
            ');
        }

        //deprecated
        if($page == "syrus-ai-admin-page" && $tab == "keywords-newsmaking") {
            wp_enqueue_script('syrus-ai-keywords-newsmaking-js', $this->settings['plugin_url'] . '/assets/js/keywords-newsmaking.js', ['jquery'], md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_add_inline_script('syrus-ai-keywords-newsmaking-js','
                const argsKeywordsNewsMaking = '.wp_json_encode([
                    'spinnerUrl' =>  $this->settings['plugin_url'] . 'assets/images/syrus-ai-spinner.svg',
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'logoUrl' => $this->settings['plugin_url'] . 'assets/images/icon-128x128.png',
                ]).';
            ');
        }

        if(!$bootstrap_already_queued && strpos($page,'syrus-ai-') !== false) {
            wp_enqueue_style('bootstrap-syrus-ai-css',$this->settings['plugin_url'] . '/assets/css/bootstrap.min.css',[],'5.3');
        }

        if($page == "syrus-ai-admin-page") {
            wp_register_style('syrus-ai-select2-css', $this->settings['plugin_url'] . '/assets/css/select2.min.css',[],md5(uniqid()));
            wp_enqueue_style('syrus-ai-select2-css');
        }

        if($page == "syrus-ai-admin-page" && $tab == "social-settings") {
            wp_register_script('syrus-ai-social-settings',$this->settings['plugin_url'] . '/assets/js/social-settings.js',['jquery'],md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_localize_script('syrus-ai-social-settings','args_social_settings',[
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
            wp_enqueue_script('syrus-ai-social-settings');
        }

        if($page == "syrus-ai-admin-page" && $tab == "news-making") {
            wp_register_script('syrus-ai-news-making',$this->settings['plugin_url'] . '/assets/js/news-making.js',['jquery'],md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_localize_script('syrus-ai-news-making','args_news_making',[
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
            wp_enqueue_script('syrus-ai-news-making');
        }

        if($page == "syrus-ai-admin-page") {
            wp_register_script('syrus-ai-general-settings',$this->settings['plugin_url'] . '/assets/js/general-settings.js',['jquery'],md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_localize_script('syrus-ai-general-settings','args_general_settings',[
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
            wp_enqueue_script('syrus-ai-general-settings');
        }

        if($page == "syrus-ai-admin-page") {
            wp_register_script('syrus-ai-alert',$this->settings['plugin_url'] . '/assets/js/sweetalert2.all.min.js',['jquery'],md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_localize_script('syrus-ai-alert','args_general_settings',[
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
            wp_enqueue_script('syrus-ai-alert');
        }

        if($page == "syrus-ai-admin-page") {
            wp_register_script('syrus-ai-advanced-settings',$this->settings['plugin_url'] . '/assets/js/advanced-settings.js',['jquery'],md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_localize_script('syrus-ai-advanced-settings','args_advanced_settings',[
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
            wp_enqueue_script('syrus-ai-advanced-settings');
        }

        if($page == "syrus-ai-admin-page" && $tab == "ai-translate") {
            wp_register_script('syrus-ai-translate',$this->settings['plugin_url'] . '/assets/js/ai-translate.js',['jquery'],md5(uniqid()), [
                'in_footer' => true,
            ]);
            wp_localize_script('syrus-ai-translate','argsAITranslate',[
                'spinnerUrl' =>  $this->settings['plugin_url'] . 'assets/images/syrus-ai-spinner.svg',
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'logoUrl' => $this->settings['plugin_url'] . 'assets/images/icon-128x128.png',
            ]);
            wp_enqueue_script('syrus-ai-translate');
        }

        if($page == "syrus-ai-admin-page") {
            wp_register_script('syrus-ai-select2-js',$this->settings['plugin_url'] . '/assets/js/select2.min.js',['jquery'],md5(uniqid()), [
                'in_footer' => true
            ]);
            wp_enqueue_script('syrus-ai-select2-js');
        }

        if($page == "syrus-ai-admin-page") {
            wp_register_style('animate.css', $this->settings['plugin_url'] . '/assets/css/animate.min.css',[],md5(uniqid()));
            wp_enqueue_style('animate.css');
        }

        if($page == "syrus-ai-admin-page" && $tab == "video-generator") {
            wp_register_script('video-generator',$this->settings['plugin_url'] . '/assets/js/video-generator.js',['jquery'],md5(uniqid()), [
                'in_footer' => true
            ]);
            wp_localize_script('video-generator','args_video_generator',[
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
            wp_enqueue_script('syrus-ai-automatic-translate');
        }

        if($page == "syrus-ai-admin-page" && $tab == "advanced") {
            wp_register_script('syrus-ai-advanced-js', $this->settings['plugin_url'] . 'assets/js/advanced.js',['jquery','syrus-ai-alert'],md5(uniqid()), [
                'in_footer' => true
            ]);
            wp_enqueue_script('syrus-ai-advanced-js');
            wp_add_inline_script('syrus-ai-advanced-js',"
                const args_advanced = " . wp_json_encode([
                    'ajax_url' => admin_url('admin-ajax.php')
                ]) . ";
            ","after");
        }

        if($page == "syrus-ai-admin-page" && in_array($tab, ['contact-forms-new','contact-forms','contact-forms-settings','contact-forms-edit'])) {
            wp_register_script('syrus-ai-cpt-js', $this->settings['plugin_url'] . 'assets/js/cpt.js',['jquery','syrus-ai-alert'],md5(uniqid()), [
                'in_footer' => true
            ]);
            wp_enqueue_script('syrus-ai-cpt-js');
            wp_add_inline_script('syrus-ai-cpt-js',"
                const args_cpt = " . wp_json_encode([
                    'ajax_url' => admin_url('admin-ajax.php')
                ]) . ";
            ","after");

            $ajax_nonce = wp_create_nonce('syrus-ajax-cpt');
            wp_localize_script('syrus-ai-cpt-js', 'ajax_object', array('ajax_nonce' => $ajax_nonce));
        }

        wp_enqueue_style('syrus-ai-style',$this->settings['plugin_url'] . '/assets/css/style.css',[],md5(uniqid()));



    }

    public function publish_post($post_id, $post, $old_status) {
        $share_on_social = filter_input(
            INPUT_POST,
            'syrus_ai_share_chk',
            FILTER_SANITIZE_NUMBER_INT,
            ['options' => 'esc_html']
        );

        $share_on_social = $share_on_social == 1 ? true : false;

        if($share_on_social || $old_status == "future") {
            $this->async_post_sharing($post_id, $post);
            // wp_schedule_single_event(time(), 'syrus_ai_async_post_sharing', [$post_id, $post]);
        }


        return true;
    }

    public function post_submitbox_misc_actions($post) { ?>
        <div class="misc-pub-section misc-pub-syrus-ai">
            <div class="icon-container">
                <img src="<?php echo esc_url($this->settings['plugin_url']) ?>/assets/images/logo-cropped-16x16.png" alt="" class="">
            </div>
            <?php $share = get_option("syrus-ai-enable-social", true);?>
            <?php esc_html_e('Share on social network','syrus-ai') ?>
            <input type="checkbox" name="syrus_ai_share_chk" value="1" <?php echo ($share == "1") ? "checked" : ""?>>
        </div>
    <?php }

    public function enqueue_block_editor_assets() {
        wp_enqueue_script('syrus-ai-block-editor',$this->settings['plugin_url'] . '/assets/js/block-editor.js',['wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data'],md5(uniqid()), [
            'in_footer' => true,
        ]);
        wp_add_inline_script('syrus-ai-block-editor', 'const args_block_editor = ' . wp_json_encode([
            'icon_url' =>  $this->settings['plugin_url'] . "/assets/images/logo-cropped-16x16.png",
            'label' => __('Share on social network','syrus-ai'),
        ]),'before');
    }

    public function init() {

        if(!session_id())
            session_start();
        
        global $syrusCPT_ContactForm;
        $syrusCPT_ContactForm->initialize();

    }

    /* WORDPRESS FILTERS */
    public function plugin_action_links($actions, $plugin_file, $plugin_data, $context) {
        $settings_url = esc_url(admin_url('admin.php?page=syrus-ai-admin-page'));
        $actions['settings'] = 	'<a href="' . $settings_url . '">' . __('Settings') . '</a>';
        return $actions;
    }

    function plugin_timeout_extend($time) {
        return 60;
    }

    public function upload_mimes($mimes) {
        $mimes['webp'] = 'image/webp';
        return $mimes;
    }

    /* CUSTOM ACTIONS */
    public function async_post_sharing($post_id, $post) {
        global $syrusAIAI;
        global $syrusAISocialAdapter;

        $checked_category = $this->get_general_settings()['category'];
        $checked_tags = $this->get_general_settings()['tags'];
        $checked_social = $this->get_general_settings()['social'];
        $force_share = false;

        $post_category = wp_get_post_categories($post_id);
        $matches_categories = array_intersect($checked_category, $post_category);

        $post_tags = array_map(function($item){ return (int) $item->term_taxonomy_id; },wp_get_post_tags($post_id));
        $post_tags = is_array($post_tags) ? $post_tags : [$post_tags];
        $matches_tags = array_intersect($checked_tags, $post_tags);

        if(!$checked_category && !$checked_tags)
            $force_share = true;

        if(!$matches_categories && !$matches_tags && !$force_share)
            return;

        $sharing_status = $this->status_of_sharing($post_id);
        $bearerToken = get_option("syrus_ai_bearer_token", true);
        $requests = [];

        //facebook
        if(in_array("fb",$checked_social) && !$sharing_status["facebook"]){
            $prompt = $this->get_prompt();

            $titolo = $post->post_title;
            $image = get_the_post_thumbnail_url($post_id);
            $url = get_permalink($post_id);

            $content = $post->post_content;
            $contentTokens = $syrusAIAI->textToToken($content);

            if($contentTokens > 2000)
                $content = $syrusAIAI->reduceToken($content);

            $prompt = str_replace('%title%',$titolo,$prompt);
            // $prompt = str_replace('%content%', $content,$prompt);
            $prompt = str_replace('%social%',"Facebook",$prompt);
            $res = json_decode($syrusAIAI->genera_contenuto_ChatGpt($prompt))->risposta;

            $text = wp_strip_all_tags($res);

            $requests[] = [
                'url' => $syrusAISocialAdapter->get_share_facebook_endpoint(),
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearerToken,
                ],
                'data' => [
                    'text' => $text,
                    'image_url' => $image,
                    'post_url' => $url,
                    'prompt' => $prompt
                ],
                'type' => Requests::POST,
            ];

            $sharing_status["facebook"] = true;
        }

        //linkedin
        if(in_array("linkedin",$checked_social) && !$sharing_status["linkedin"]){
            $options = unserialize(get_option('syrus-ai-connected-social'));
            $where_post = isset($options['linkedin']) && isset($options['linkedin']['where_post']) ? $options['linkedin']['where_post'] : null;

            $prompt = $this->get_prompt();

            $titolo = $post->post_title;
            $image = get_the_post_thumbnail_url($post_id);
            $url = get_permalink($post_id);

            $content = $post->post_content;
            $contentTokens = $syrusAIAI->textToToken($content);

            if($contentTokens > 2000)
                $content = $syrusAIAI->reduceToken($content);

            $prompt = str_replace('%title%',$titolo,$prompt);
            // $prompt = str_replace('%content%',$content,$prompt);
            $prompt = str_replace('%social%',"Linkedin",$prompt);
            $res = json_decode($syrusAIAI->genera_contenuto_ChatGpt($prompt))->risposta;

            $text = wp_strip_all_tags($res);

            $requests[] = [
                'url' => $syrusAISocialAdapter->get_share_linkedin_endpoint(),
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearerToken,
                ],
                'data' => [
                    'text' => $text,
                    'image_url' => $image,
                    'post_url' => $url,
                    'prompt' => $prompt,
                    'where_post' => $where_post ?: 0
                ],
                'type' => Requests::POST,
            ];

            $sharing_status["linkedin"] = true;
        }

        //twitter
        if(in_array("twitter",$checked_social) && !$sharing_status["twitter"]){
            $prompt = $this->get_prompt();

            $titolo = $post->post_title;
            $image = get_the_post_thumbnail_url($post_id);
            $url = get_permalink($post_id);

            $content = $post->post_content;
            $contentTokens = $syrusAIAI->textToToken($content);

            if($contentTokens > 2000)
                $content = $syrusAIAI->reduceToken($content);

            $prompt = str_replace('%title%',$titolo,$prompt);
            // $prompt = str_replace('%content%',$content,$prompt);
            $prompt = str_replace('%social%',"Twitter",$prompt);
            $res = json_decode($syrusAIAI->genera_contenuto_ChatGpt($prompt))->risposta;

            $text = wp_strip_all_tags($res);

            $requests[] = [
                'url' => $syrusAISocialAdapter->get_share_twitter_endpoint(),
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearerToken,
                ],
                'data' => [
                    'text' => $text,
                    'image_url' => $image,
                    'post_url' => $url,
                    'prompt' => $prompt
                ],
                'type' => Requests::POST,
            ];

            $sharing_status["twitter"] = true;
        }

        //instagram
        if(in_array("ig",$checked_social) && !$sharing_status["instagram"]){
            $prompt = $this->get_prompt();

            $titolo = $post->post_title;
            $image = get_the_post_thumbnail_url($post_id);
            $url = get_permalink($post_id);

            $content = $post->post_content;
            $contentTokens = $syrusAIAI->textToToken($content);

            if($contentTokens > 2000)
                $content = $syrusAIAI->reduceToken($content);

            $prompt = str_replace('%title%',$titolo,$prompt);
            // $prompt = str_replace('%content%', $content,$prompt);
            $prompt = str_replace('%social%',"Instagram",$prompt) . "\nLa risposta deve ";
            $res = json_decode($syrusAIAI->genera_contenuto_ChatGpt($prompt))->risposta;

            $text = wp_strip_all_tags($res);

            $requests[] = [
                'url' => $syrusAISocialAdapter->get_share_instagram_endpoint(),
                'headers' => [
                    'Authorization' => 'Bearer ' . $bearerToken,
                ],
                'data' => [
                    'text' => $text,
                    'image_url' => $image,
                    'post_url' => $url,
                    'prompt' => $prompt
                ],
                'type' => Requests::POST,
            ];

            $sharing_status["instagram"] = true;
        }

        if(count($requests) > 0)
            Requests::request_multiple($requests);

        update_post_meta($post_id,"syrus_ai_shared_on",$sharing_status);
    }


    /* PAGES */
    public function plugin_admin_page() {
        $args = [
            'page_title' => $this->syrus_theme_active ? "AI Support" : "Syrus AI Support",
        ];

        return include_once $this->settings['plugin_path'] . 'admin/index.php';
    }

    /* UTILITY PUBLIC METHODS */
    public function tags_checklist() {
        global $wpdb;

        $tags = $wpdb->get_results( "
            SELECT t.*, tt.*
            FROM {$wpdb->terms} t
            INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
            INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
            LEFT JOIN {$wpdb->posts} p ON tr.object_id = p.ID
            WHERE tt.taxonomy = 'post_tag'
        " );

        $checked_tags = $this->get_general_settings()['tags'];

        foreach ($tags as $i => $tag) { ?>
            <li id="tag-<?php echo esc_html($tag->term_taxonomy_id) ?>">
                <label class="selectit">
                    <input value="<?php echo esc_html($tag->term_taxonomy_id) ?>" type="checkbox" name="post_tag[]" id="in-tag-<?php echo esc_attr($tag->term_taxonomy_id) ?>" <?php echo in_array($tag->term_taxonomy_id,$checked_tags) ? "checked" : "" ?>>
                    <?php echo esc_html($tag->name) ?>
                </label>
            </li>
        <?php }
    }

    public function social_connected_checklist(){
        $social = $this->get_connected_social_networks();

        $checked_social = $this->get_general_settings()['social'];

        foreach($social as $id => $label) { ?>
            <label class="selectit me-3">
                <input value="<?php echo esc_attr($id) ?>" type="checkbox" name="selected_social_network[]" id="in-social-network-<?php echo esc_attr($id) ?>" <?php echo in_array($id,$checked_social) ? "checked" : "" ?>>
                <?php echo esc_html($label) ?>
            </label>
        <?php }
    }

    public function get_connected_social_networks() {
        $socials = [];

        $current_connected_social = get_option('syrus-ai-connected-social') ? unserialize(get_option('syrus-ai-connected-social')) : [];


        if(isset($current_connected_social['facebook']['connected']))
            $socials['fb'] = "Facebook";

        if(isset($current_connected_social['instagram']['connected']))
            $socials['ig'] = "Instagram";

        if(isset($current_connected_social['twitter']['connected']))
            $socials['twitter'] = "Twitter";

        if(isset($current_connected_social['linkedin']['connected']))
            $socials['linkedin'] = "Linkedin";

        return $socials;
    }

    public function get_chatgpt_token() {
        $option_key = $this->syrus_theme_active ? 'syrus_theme_generali_chatgpt_token' : 'syrus_ai_chatgpt_token';
        $option = get_option($option_key);
        return $option;
    }

    public function get_chatgpt_organization_id() {
        // $option_key = $this->syrus_theme_active ? 'syrus_theme_generali_chatgpt_token' : 'syrus_ai_chatgpt_token';
        $option = get_option('syrus_ai_chatgpt_organization_id');
        return $option;
    }

    public static function get_writesonic_token() {
        $option = get_option('syrus_ai_writesonic_token');
        return $option;
    }

    public function get_deepl_token() {
        $option = get_option("syrus_ai_deepl_token");
        return $option;
    }

    public function get_newsapi_token() {
        // $option_key = $this->syrus_theme_active ? 'syrus_theme_generali_chatgpt_token' : 'syrus_ai_newsapi_token';
        $option = get_option("syrus_ai_newsapi_token");
        return $option;
    }

    public function get_translate_domain() {
        $option = get_option("syrus-ai-domain-translation");
        return $option;
    }

    public function get_newsmaking_settings() {
        $option = get_option("syrus-ai-newsmaking-settigs");
        return $option;
    }

    public function get_contact_form_settings() {
        $option = unserialize(get_option('syrus_ai_contact_form_settings', serialize([])));
        return $option;
    }

    public function get_newsmaking_country() {
        $country = array();

        $country = [
            'ar' => 'Argentina',
            'au' => 'Australia',
            'at' => 'Austria',
            'be' => 'Belgium',
            'br' => 'Brazil',
            'bg' => 'Bulgaria',
            'ca' => 'Canada',
            'cn' => 'China',
            'co' => 'Colombia',
            'cu' => 'Cuba',
            'cz' => 'Czech Republic',
            'eg' => 'Egypt',
            'fr' => 'France',
            'de' => 'Germany',
            'gr' => 'Greece',
            'hk' => 'Hong Kong',
            'hu' => 'Hungary',
            'in' => 'India',
            'id' => 'Indonesia',
            'ie' => 'Ireland',
            'il' => 'Israel',
            'it' => 'Italy',
            'jp' => 'Japan',
            'lv' => 'Latvia',
            'lt' => 'Lithuania',
            'my' => 'Malaysia',
            'mx' => 'Mexico',
            'ma' => 'Morocco',
            'nl' => 'Netherlands',
            'nz' => 'New Zealand',
            'ng' => 'Nigeria',
            'no' => 'Norway',
            'ph' => 'Philippines',
            'pl' => 'Poland',
            'pt' => 'Portugal',
            'ro' => 'Romania',
            'ru' => 'Russia',
            'sa' => 'Saudi Arabia',
            'rs' => 'Serbia',
            'sg' => 'Singapore',
            'sk' => 'Slovakia',
            'si' => 'Slovenia',
            'za' => 'South Africa',
            'kr' => 'South Korea',
            'se' => 'Sweden',
            'ch' => 'Switzerland',
            'tw' => 'Taiwan',
            'th' => 'Thailand',
            'tr' => 'Turkey',
            'ae' => 'UAE',
            'ua' => 'Ukraine',
            'gb' => 'United Kingdom',
            'us' => 'United States',
            've' => 'Venuzuela'
        ];

        return $country;
    }

    public function get_associative_prompt() {
        $language = get_locale();
        $first = explode('_',$language)[0];

        $prompt['it'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Scrivi un post clickbait sul titolo, di massimo 15 parole in italiano, con emoji, invita gli utenti a leggere l'articolo e interagire con esso. Se il post è destinato a Twitter deve essere massimo di 8 parole.",
            'social' => "Il post è destinato a %social%.",
        ];

        $prompt['en'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Write a clickbait post about the title, max 15 words in English, containing emojis, inviting users to read the article and engage with the post. If the post is meant for Twitter, it must be a maximum of 8 words.",
            'social' => "The post is meant for %social%.",
        ];

        $prompt['es'] = [
            'title' => "%Title%",
            // 'content' => "%Content%",
            'default_main' => "Escribe un post clickbait sobre el título de hasta 15 palabras en español, con emojis, invita a los usuarios a leer el artículo e interactuar con él. Si la publicación está destinada a Twitter, debe tener un máximo de 8 palabras.",
            'social' => "La publicación está destinada a %social%.",
        ];

        $prompt['fr'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Rédigez un post clickbait sur le titre, max 15 caractères en français, contenant des emojis, invitant les utilisateurs à lire l'article et interagir avec le post. Si l'article est destiné à Twitter, il doit comporter un maximum de 8 mots.",
            'social' => "L'article est destiné à %social%.",
        ];

        $prompt['br'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Escreva um post clickbait sobre o título, com no máximo 15 palavras em português brasileiro, contendo emojis, convidando os usuários a ler o artigo e interagir com o post. Se a postagem for destinada ao Twitter, ela deve ter no máximo 8 palavras.",
            'social' => "A postagem deve ser feita para %social%.",
        ];

        $prompt['jp'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Write a clickbait post about the title, max 15 words in Japanese, containing emojis, inviting users to read the article and engage with the post. If the post is meant for Twitter, it must be a maximum of 8 words.",
            'social' => "The post is meant for %social%.",
        ];

        $prompt['ru'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Write a clickbait post about the title, max 15 words in Russian, containing emojis, inviting users to read the article and engage with the post. If the post is meant for Twitter, it must be a maximum of 8 words.",
            'social' => "The post is meant for %social%.",
        ];

        $prompt['ar'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Write a clickbait post about the title, max 15 words in Arabic, containing emojis, inviting users to read the article and engage with the post. If the post is meant for Twitter, it must be a maximum of 8 words.",
            'social' => "The post is meant for %social%.",
        ];

        $prompt['de'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Schreiben Sie einen Clickbait-Beitrag zum Titel, maximal 15 Wörter auf Deutsch, mit Emojis, laden Sie Benutzer ein, den Artikel zu lesen und mit dem Beitrag zu interagieren. Wenn der Beitrag für Twitter bestimmt ist, darf er maximal 8 Wörter enthalten.",
            'social' => "Der Beitrag soll für %social% bestimmt sein.",
        ];

        $prompt['el'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Write a clickbait post about the title, max 15 words in Greek, containing emojis, inviting users to read the article and engage with the post. If the post is meant for Twitter, it must be a maximum of 8 words.",
            'social' => "The post is meant for %social%.",
        ];

        $prompt['ro'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Scrieți o postare clickbait pe titlul de până la 15 cuvinte în limba română, conținând emoji, invitând utilizatorii să citească articolul și să interacționeze cu postarea. Dacă postarea este destinată pentru Twitter, ea trebuie să aibă maximum 8 cuvinte.",
            'social' => "Postarea este menită să fie pentru %social%.",
        ];

        $prompt['he'] = [
            'title' => "%Title%",
            'content' => "%Content%",
            'default_main' => "Write a clickbait post about the title, max 15 words in Hebrew, containing emojis, inviting users to read the article and engage with the post. If the post is meant for Twitter, it must be a maximum of 8 words.",
            'social' => "The post is meant for %social%.",
        ];


        return $prompt[$first];
    }

    public function get_prompt() {
        $array_prompt = $this->get_associative_prompt();
        return "TITOLO: %title% \n" . $array_prompt['default_main'] . "\n" . $array_prompt['social'] . ".\n" . 'Il json di risposta deve avere la sola voce "risposta" e il suo valore sarà il testo che hai scritto.';
    }

    public function get_general_settings() {
        $settings = get_option('syrus-ai-general-settings');

        if(!$settings)
            return [
                'category' => [],
                'tags' => [],
                'social' => []
            ];

        $settings = unserialize($settings);

        if (!isset($settings['category']) || !is_array($settings['category'])) {
            $settings['category'] = [];
        }

        if (!isset($settings['tags']) || !is_array($settings['tags'])) {
            $settings['tags'] = [];
        }

        if (!isset($settings['social']) || !is_array($settings['social'])) {
            $settings['social'] = [];
        }

        return $settings;
    }

    public function status_of_sharing($post_id){
        $status = get_post_meta($post_id,"syrus_ai_shared_on") ? get_post_meta($post_id,"syrus_ai_shared_on")[0] : [];

        $data = array(
            "facebook" => isset($status["facebook"]) && $status["facebook"] ? true : false,
            "twitter" => isset($status["twitter"]) && $status["twitter"] ? true : false,
            "linkedin" => isset($status["linkedin"]) && $status["linkedin"] ? true : false,
            "instagram" => isset($status["instagram"]) && $status["instagram"] ? true : false
        );
        return $data;
    }

    public function get_shared_posts(){

    }

    // Funzione per ottenere il nome di una categoria dato il suo ID
    public function get_category_name($category_id, $domain) {

        // Crea l'URL dell'API per la categoria
        $api_url = "https://" . $domain . "/wp-json/wp/v2/categories/" . $category_id;

        // Esegui la richiesta API
        $response = wp_remote_get($api_url);

        $response = $response['body'];

        // Decodifica la risposta JSON
        $category_data = json_decode($response, true);

        // Restituisce il nome della categoria
        return $category_data['name'];
    }

    // Funzione per ottenere il nome di un tag dato il suo ID
    public function get_tag_name($tag_id, $domain) {

        // Crea l'URL dell'API per il tag
        $api_url = "https://" . $domain . "/wp-json/wp/v2/tags/" . $tag_id;

        // Esegui la richiesta API
        $response = wp_remote_get($api_url);

        $response = $response['body'];

        // Decodifica la risposta JSON
        $tag_data = json_decode($response, true);

        // Restituisce il nome del tag
        return $tag_data['name'];
    }

    public function get_all_timezone() {
        $timezones = timezone_identifiers_list();
        return $timezones;
    }

    public function get_jet_lag() {
        $jet_lag = get_option("syrus-ai-jet-lag", true);

        return $jet_lag;
    }

    public function get_hours() {
        $hours = array(
            "00:00", "01:00", "02:00", "03:00", "04:00", "05:00",
            "06:00", "07:00", "08:00", "09:00", "10:00", "11:00",
            "12:00", "13:00", "14:00", "15:00", "16:00", "17:00",
            "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"
        );

        return $hours;
    }

    public function get_cron_hour() {
        $hour = get_option("syrus-ai-cron-hour", true);

        return $hour;
    }

    public function get_news_db($type) {
        global $wpdb;

        $newsMaking = [];
        $table_name = $wpdb->prefix . 'newsmaking';

        $results = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}newsmaking ORDER BY date DESC"
        );

        // Check if there are any records
        if ($results) {

            if($type == "category") {
                // Query to fetch records from the table

                // Fetch the records using $wpdb->get_results()
                $results = $wpdb->get_results(
                    "SELECT * FROM {$wpdb->prefix}newsmaking WHERE newsmaking_type = 'category' ORDER BY date DESC"
                );

                foreach ($results as $i => $result) {
                    $id = $result->id;
                    $title = $result->title;
                    $link = $result->link;
                    $author = $result->author;
                    $date = $result->date;

                    $date = gmdate('d-m-Y', strtotime(str_replace('/', '-', $date)));

                    $newsMaking[$i]['title'] = $title;
                    $newsMaking[$i]['author'] = $author;
                    $newsMaking[$i]['date'] = $date;
                    $newsMaking[$i]['link'] = $link;
                }
            } else {
                // Query to fetch records from the table
                // Fetch the records using $wpdb->get_results()
                $results = $wpdb->get_results(
                    "SELECT * FROM {$wpdb->prefix}newsmaking WHERE newsmaking_type = 'keyword' ORDER BY date DESC"
                );

                foreach ($results as $i => $result) {
                    $id = $result->id;
                    $title = $result->title;
                    $link = $result->link;
                    $author = $result->author;
                    $date = $result->date;

                    $date = gmdate('d-m-Y', strtotime(str_replace('/', '-', $date)));

                    $newsMaking[$i]['title'] = $title;
                    $newsMaking[$i]['author'] = $author;
                    $newsMaking[$i]['date'] = $date;
                    $newsMaking[$i]['link'] = $link;
                }
            }
        } else {
            $newsMaking = [];
        }

        return $newsMaking;

    }

    public function clearNews($type) {
        global $wpdb;


        if($type == "category") {
            $results = $wpdb->get_results(
                "DELETE FROM {$wpdb->prefix}newsmaking WHERE newsmaking_type = 'category'"
            );

            return $results;
        } else {
            $results = $wpdb->get_results(
                "DELETE FROM {$wpdb->prefix}newsmaking WHERE newsmaking_type = 'keyword'"
            );

            return $results;
        }
    }

    public function get_translate_db($domain) {
        global $wpdb;

        $return = array();
        $automaticTranslate = array();

        $current_page = filter_input(
            INPUT_GET,
            'page_n',
            FILTER_CALLBACK,
            ['options' => 'esc_html']
        );

        if(!$current_page)
            $current_page = 1;

        $total_records_per_page = 5;

        $offset = ($current_page - 1) * $total_records_per_page;

        $total_records = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}translate_and_publish WHERE domain = %s",
            $domain
        ));
        // $total_records = $total_records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page) - 1;


        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}translate_and_publish WHERE domain = %s ORDER BY date DESC LIMIT %d, %d",
                $domain,
                $offset,
                $total_records_per_page
            )
        );

        // echo "<pre>";
        // print_r($total_records);
        // exit;

        // Check if there are any records
        if ($results) {
            // Loop through the records
            foreach ($results as $i => $result) {
                // Access the columns of each row using object notation
                $id = $result->id;
                $id_wp = $result->id_wp;
                $date = $result->date;
                $link = $result->link;
                $title = $result->title;
                $content = $result->content;
                $categories = $result->categories;
                $tags = $result->tags;
                $media = $result->media;
                $post_id = $result->post_id;

                $automaticTranslate[$i]['id'] = $id;
                $automaticTranslate[$i]['id_wp'] = $id_wp;
                $automaticTranslate[$i]['date'] = $date;
                $automaticTranslate[$i]['link'] = $link;
                $automaticTranslate[$i]['title'] = $title;
                $automaticTranslate[$i]['content'] = $content;
                $automaticTranslate[$i]['categories'] = $categories;
                $automaticTranslate[$i]['tags'] = $tags;
                $automaticTranslate[$i]['media'] = $media;
                $automaticTranslate[$i]['post_id'] = $post_id;
            }

            $return = [
                "1" => $automaticTranslate,
                "2" => $total_no_of_pages
            ];
        } else {
            $automaticTranslate = [];
            $return = [
                "1" => $automaticTranslate,
                "2" => "0"
            ];
        }

        return $return;
    }

    public function delete_article_translate($wp_id) {
        global $wpdb;


        $results = $wpdb->get_results($wpdb->prepare(
            "DELETE FROM `{$wpdb->prefix}translate_and_publish` WHERE id_wp = %d",
            $wp_id
        ));

        return $results;
    }

    public function get_article_db($wp_id) {
        global $wpdb;


        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM `{$wpdb->prefix}translate_and_publish` WHERE id_wp = %d",
            $wp_id
        ));

        return $results;
    }

    // CHECK TRANSLATE

    public function update_translate($content, $post_id) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'translate_and_publish';

        $wpdb->update(
            $table_name,
            array(
                'post_id' => $post_id
            ),
            array(
                'content' => $content
            )
        );
    }

    public function check_is_translated($content) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'translate_and_publish';

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}translate_and_publish WHERE content = %s AND post_id IS NOT NULL",
            $content
        ));

        return $results;
    }

    // GESTIONE LOG

    public function insert_log($name, $value) {
        global $wpdb;

        $jet_lag = get_option("syrus-ai-jet-lag", true);

        // date_default_timezone_set($jet_lag);

        $current_hour = gmdate('H:i:s');
        $current_date = gmdate('d/m/y');

        $table_name = $wpdb->prefix . 'syrus_ai_logs';

        $wpdb->insert(
          $table_name,
          array(
            'name' => $name,
            'value' => $value,
            'hour' => $current_hour,
            'date' => $current_date
          )
        );
    }

    public function get_logs() {
        global $wpdb;

        $logs = array();

        $results = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}syrus_ai_logs ORDER BY id DESC"
        );

        if ($results) {
            foreach ($results as $i => $result) {
                $id = $result->id;
                $name = $result->name;
                $value = $result->value;
                $hour = $result->hour;
                $date = $result->date;

                $logs[$i]['id'] = $id;
                $logs[$i]['name'] = $name;
                $logs[$i]['value'] = $value;
                $logs[$i]['hour'] = $hour;
                $logs[$i]['date'] = $date;
            }
        } else {
            $logs = [];
        }

        return $logs;
    }

    public function create_post($title, $content, $status, $post_id = null) {
        $args = [
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => $status,
            'post_type' => 'post'
        ];

        if($post_id)
            $args['ID'] = $post_id;

        $wordpress_post = $args;

        $post_id = wp_insert_post($wordpress_post);

        return $post_id;
    }

    public function add_metabox_article_generator_content() {
        add_meta_box(
            'syrus-ai-generatore-contenuto-ws',
            __('Writesonic','syrus'),
            [new self(),'render_metabox_article_generator_content'],
            'post',
            'side',
            'high'
        );

        add_meta_box(
            'syrus-ai-image-generator',
            __('AI Featured Image', 'syrus-ai'),
            [$this, 'render_metabox_ai_featured_image'],
            'post',
            'side',
        );
    }

    public static function render_metabox_article_generator_content() {
        global $syrusAITemplate;
        $syrusAITemplate->include("admin/metaboxes/meta-box-generatore-contenuto-post-ws");
    }

    public function render_metabox_ai_featured_image() {
        global $syrusAITemplate;
        $syrusAITemplate->include("admin/metaboxes/meta-box-generate-post-thumbnail");
        // <a href="http://syrusblog.localhost/wp-admin/media-upload.php?post_id=39268&amp;type=image&amp;TB_iframe=1&amp;width=753&amp;height=822" id="set-post-thumbnail" class="thickbox">Set featured image</a>
    }

    // public function update_post($post_id, $title, $content, $status) {
    //     $wordpress_post = array(
    //         'ID'           => $post_id,
    //         'post_title'   => $title,
    //         'post_content' => $content,
    //         'post_status'  => $status,
    //         'post_type'    => 'post'
    //     );

    //     wp_update_post($wordpress_post);

    //     return $post_id;
    // }

    public function get_language($language) {
        $languages = [
            'af' => 'afrikaans',
            'sq' => 'albanese',
            'am' => 'amarico',
            'ar' => 'arabo',
            'hy' => 'armeno',
            'az' => 'azerbaigiano',
            'eu' => 'basco',
            'be' => 'bielorusso',
            'bn' => 'bengalese',
            'bs' => 'bosniaco',
            'bg' => 'bulgaro',
            'ca' => 'catalano',
            'ceb' => 'cebuano',
            'ny' => 'chichewa',
            'co' => 'corso',
            'hr' => 'croato',
            'cs' => 'ceco',
            'da' => 'danese',
            'nl' => 'olandese',
            'en' => 'inglese',
            'eo' => 'esperanto',
            'et' => 'estone',
            'tl' => 'filippino',
            'fi' => 'finlandese',
            'fr' => 'francese',
            'fy' => 'frisone',
            'gl' => 'galiziano',
            'ka' => 'georgiano',
            'de' => 'tedesco',
            'el' => 'greco',
            'gu' => 'gujarati',
            'ht' => 'haitiano creolo',
            'ha' => 'hausa',
            'haw' => 'hawaiano',
            'iw' => 'ebraico',
            'hi' => 'hindi',
            'hmn' => 'hmong',
            'hu' => 'ungherese',
            'is' => 'islandese',
            'ig' => 'igbo',
            'id' => 'indonesiano',
            'ga' => 'irlandese',
            'it' => 'italiano',
            'ja' => 'giapponese',
            'jw' => 'giavanese',
            'kn' => 'kannada',
            'kk' => 'kazako',
            'km' => 'khmer',
            'ko' => 'coreano',
            'ku' => 'curdo (kurmanji)',
            'ky' => 'kirghiso',
            'lo' => 'lao',
            'la' => 'latino',
            'lv' => 'lettone',
            'lt' => 'lituano',
            'lb' => 'lussemburghese',
            'mk' => 'macedone',
            'mg' => 'malgascio',
            'ms' => 'malese',
            'ml' => 'malayalam',
            'mt' => 'maltese',
            'mi' => 'maori',
            'mr' => 'marathi',
            'mn' => 'mongolo',
            'my' => 'birmano',
            'ne' => 'nepalese',
            'no' => 'norvegese',
            'or' => 'oriya',
            'ps' => 'pashto',
            'fa' => 'persiano',
            'pl' => 'polacco',
            'pt' => 'portoghese',
            'pa' => 'punjabi',
            'ro' => 'rumeno',
            'ru' => 'russo',
            'sm' => 'samoano',
            'gd' => 'gaelico scozzese',
            'sr' => 'serbo',
            'st' => 'sesotho',
            'sn' => 'shona',
            'sd' => 'sindhi',
            'si' => 'singalese',
            'sk' => 'slovacco',
            'sl' => 'sloveno',
            'so' => 'somalo',
            'es' => 'spagnolo',
            'su' => 'sundanese',
            'sw' => 'swahili',
            'sv' => 'svedese',
            'tg' => 'tagiko',
            'ta' => 'tamil',
            'te' => 'telugu',
            'th' => 'thai',
            'tr' => 'turco',
            'uk' => 'ucraino',
            'ur' => 'urdu',
            'ug' => 'uiguro',
            'uz' => 'uzbeko',
            'vi' => 'vietnamita',
            'cy' => 'gallese',
            'xh' => 'xhosa',
            'yi' => 'yiddish',
            'yo' => 'yoruba',
            'zu' => 'zulu'
        ];

        return $languages[$language];
    }

}