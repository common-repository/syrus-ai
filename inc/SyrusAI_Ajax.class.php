<?php

if(!defined('ABSPATH'))
    exit;

class SyrusAI_Ajax {
  public $settings;

  function initialize($settings) :void {
    $this->settings = $settings;

    $methods = get_class_methods($this);
    array_shift($methods); // Rimuovo initialize()
    array_shift($methods); // Rimuovo return_response()

    foreach($methods as $key => $method) {
        $method_obj = new ReflectionMethod($this,$method);
        $parameters = $method_obj->getParameters();
        $ajax_types = $parameters[0]->getDefaultValue();
        $method_slug = str_replace('_','-',$method);

        if(count($ajax_types) == 0 || in_array('priv',$ajax_types)) // Loggato
          add_action('wp_ajax_syrus-ai-' . $method_slug,[$this,$method]);

        if(in_array('no_priv',$ajax_types)) // Non loggato
          add_action('wp_ajax_nopriv_syrus-ai-' . $method_slug,[$this,$method]);
      }
  }

  public static function return_response(array $params = []) :void {
    echo wp_json_encode($params);
    wp_die();
  }

  public function get_authorization_url_syrus_api($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $social = isset($_POST['social']) ? sanitize_text_field($_POST['social']) : null;

    $url = "https://developers.syrus.it/api/wp/v1/auth_url";

    $headers = array(
      'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
    );

    $res = wp_remote_post($url, [
      'headers' => $headers,
      'body' => [
        'social' => $social
      ]
    ]);

    $response_body = wp_remote_retrieve_body($res);
    $data = json_decode($response_body, true);

    self::return_response([
      'url' => $data['url'],
    ]);
  }

  public function social_connected($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $social = isset($_POST['social']) ? sanitize_text_field($_POST['social']) : null;
    $companies = isset($_POST['companies']) ? sanitize_text_field($_POST['companies']) : null;

    $current_connected_social = get_option('syrus-ai-connected-social') ? unserialize(get_option('syrus-ai-connected-social')) : [];

    $current_connected_social[$social]['connected'] = 1;

    if($companies) {
      $current_connected_social[$social]['companies'] = $companies;
      $current_connected_social[$social]['where_post'] = 0;
    }

    update_option('syrus-ai-connected-social',serialize($current_connected_social));

    self::return_response([
        'status' => "success",
    ]);
  }

  //DA SISTEMARE
  public function revoke_linkedin_token($types = ['priv']) {
    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $url = "https://developers.syrus.it/api/wp/v1/linkedin/revoke";

    $headers = array(
      'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
    );

    $res = wp_remote_request($url, [
      'method' => "POST",
      'headers' => $headers,
      'body' => []
    ]);

    $response_body = wp_remote_retrieve_body($res);
    $data = json_decode($response_body, true);

    if(isset($data['success'])) {
      $current_connected_social = get_option('syrus-ai-connected-social') ? unserialize(get_option('syrus-ai-connected-social')) : [];
      unset($current_connected_social['linkedin']);
      update_option('syrus-ai-connected-social',serialize($current_connected_social));
    } else {
      self::return_response([
        'status' => "error",
        'message' => $data
      ]);
    }

    self::return_response([
      'status' => "success",
      'data' => $data
    ]);
  }

  public function revoke_fb_token($types = ['priv']) {
    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAISocialAdapter;

    $res = $syrusAISocialAdapter->revoke_fb_token();

    if($res == "success")
      self::return_response([
        'status' => "success",
      ]);

    self::return_response([
      'status' => "error",
      'message' => $data['error']
    ]);
  }

  public function revoke_ig_token($types = ['priv']) {
    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAISocialAdapter;

    $res = $syrusAISocialAdapter->revoke_ig_token();

    if($res == "success")
      self::return_response([
        'status' => "success",
      ]);

    self::return_response([
      'status' => "error",
      'message' => $data['error']
    ]);
  }

  public function revoke_twitter_token($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $url = "https://developers.syrus.it/api/wp/v1/twitter/revoke";

    $headers = array(
      'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
    );

    $res = wp_remote_request($url, [
      'method' => "DELETE",
      'headers' => $headers,
      'body' => []
    ]);

    $response_body = wp_remote_retrieve_body($res);
    $data = json_decode($response_body, true);

    if(isset($data['success'])) {
      $current_connected_social = get_option('syrus-ai-connected-social') ? unserialize(get_option('syrus-ai-connected-social')) : [];
      unset($current_connected_social['twitter']);
      update_option('syrus-ai-connected-social',serialize($current_connected_social));
    } else {
      self::return_response([
        'status' => "error",
        'message' => $data['error']
      ]);
    }

    self::return_response([
      'status' => "success",
    ]);
  }

  public function test_share_twitter($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;
    global $syrusAISocialAdapter;

    $res = $syrusAISocialAdapter->share_twitter([
        'text' => "Prova di condivisione TWITTER",
        'image_url' => esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/pyasvfet0_m.jpg'),
        'post_url' => "https://syrus.today/how-to-unsubscribe-from-apple-music-39736.html",
    ]);

    $res = json_decode($res);

    if(isset($res->error)) {
      $syrusAIPlugin->insert_log("Twitter Test", "Can't share post");

      self::return_response([
        'error' => "Can't share post",
      ]);
    }

    $syrusAIPlugin->insert_log("Twitter Test", "Post share correctly");

    self::return_response([
      'success' => "Post share correctly",
    ]);
  }

  public function test_share_facebook($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;
    global $syrusAISocialAdapter;

    $res = $syrusAISocialAdapter->share_facebook([
        'text' => "Prova di condivisione FACEBOOK",
        'image_url' => esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/pyasvfet0_m.jpg'),
        'post_url' => "https://syrus.today/how-to-unsubscribe-from-apple-music-39736.html",
    ]);

    $res = json_decode($res);

    if(isset($res->error)) {
      $syrusAIPlugin->insert_log("Facebook Test", "Can't share post");

      self::return_response([
        'error' => "Can't share post",
      ]);
    }

    $syrusAIPlugin->insert_log("Facebook Test", "Post share correctly");

    self::return_response([
      'success' => "Post share correctly",
    ]);
  }

  public function test_share_linkedin($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;
    global $syrusAISocialAdapter;

    $res = $syrusAISocialAdapter->share_linkedin([
        'text' => "Prova di condivisione LINKEDIN",
        'image_url' => esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/pyasvfet0_m.jpg'),
        'post_url' => "https://syrus.today/how-to-unsubscribe-from-apple-music-39736.html",
    ]);

    $res = json_decode($res);

    if(isset($res->error)) {
      $syrusAIPlugin->insert_log("LinkedIn Test", "Can't share post");

      self::return_response([
        'error' => "Can't share post",
      ]);
    }

    $syrusAIPlugin->insert_log("LinkedIn Test", "Post share correctly");

    self::return_response([
      'success' => "Post share correctly",
    ]);
  }

  public function test_share_ig($types = ["priv"]) {

  }

  public function save_deepl_token($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $token = isset($_POST['deepl_token']) ? sanitize_text_field($_POST['deepl_token']) : null;

    update_option('syrus_ai_deepl_token',$token);

    $syrusAIPlugin->insert_log("DeepL token", "Token updated successfully");

    self::return_response([
      'status' => "success",
    ]);
  }

  public function save_chatgpt_token($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $token = isset($_POST['chatgpt_token']) ? sanitize_text_field($_POST['chatgpt_token']) : null;

    update_option('syrus_ai_chatgpt_token',$token);

    $syrusAIPlugin->insert_log("Chatgpt token", "Token updated successfully");

    self::return_response([
      'status' => "success",
    ]);
  }

  public function save_chatgpt_organization_id($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce($_POST['_ajaxNonce'], $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $organization_id = isset($_POST['chatgpt_organization_id']) ? sanitize_text_field($_POST['chatgpt_organization_id']) : null;

    update_option('syrus_ai_chatgpt_organization_id',$organization_id);

    $syrusAIPlugin->insert_log("Chatgpt Organization ID", "Organization ID updated successfully");

    self::return_response([
      'status' => "success",
    ]);
  }

  public function save_writesonic_token($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $token = isset($_POST['writesonic_token']) ? sanitize_text_field($_POST['writesonic_token']) : null;

    update_option('syrus_ai_writesonic_token',$token);

    $syrusAIPlugin->insert_log("Writesonic token", "Token updated successfully");

    self::return_response([
      'status' => "success",
    ]);
  }

  public function test_chatgpt_token($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIAI;

    $result_message = __('API Works :D','syrus-ai');
    $prompt = "Return a json, where there are two field, 'status' where the content is 'success' and 'result' where the content is '" . $result_message . "'";
    $prompt = wp_get_recent_posts()[1]['post_content'];
    $res = json_decode($syrusAIAI->genera_contenuto_ChatGpt($prompt));
    echo wp_json_encode($res);
    wp_die();

  }

  public function save_newsapi_token($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $token = isset($_POST['newsapi_token']) ? sanitize_text_field($_POST['newsapi_token']) : null;

    update_option('syrus_ai_newsapi_token', $token);

    $syrusAIPlugin->insert_log("Newsapi token", "Token updated successfully");

    self::return_response([
      'status' => "success",
    ]);
  }

  public function save_general_settings($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $array = serialize([
      'category' => isset($_POST['category']) ? array_map('sanitize_text_field', (array) $_POST['category']) : [],
      'tags' => isset($_POST['tags']) ? array_map('sanitize_text_field', (array) $_POST['tags']) : [],
      'social' => isset($_POST['social']) ? array_map('sanitize_text_field', (array) $_POST['social']) : [],
    ]);

    update_option('syrus-ai-general-settings', $array);

    $syrusAIPlugin->insert_log("General settings", "General settings updated successfully");

    self::return_response([
      'status' => "success",
    ]);

  }

  public function save_newsmaking_settings($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : null;
    $language = isset($_POST['language']) ? sanitize_text_field($_POST['language']) : null;
    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : null;
    $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : null;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : null;
    $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : null;

    $newsmaking_settings = [
      "domain" => $domain,
      "language" => $language,
      "keyword" => $keyword,
      "date" => $date,
      "category" => $category,
      "country" => $country
    ];

    update_option("syrus-ai-newsmaking-settigs", $newsmaking_settings);

    $syrusAIPlugin->insert_log("Newsmaking", "Newsmaking settings updated successfully");
  }

  public function check_cron_newsmaking($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $status_cron = get_option("syrus-ai-enable-cron-newsmaking", true);

    $syrusAIPlugin->insert_log("Newsmaking", "Check cron");

    if($status_cron == "1") {
      $res = $this->news_making2();
      self::return_response([
        'success' => "success"
      ]);
    } else {
      self::return_response([
        'status' => "success",
        'message' => "Without do cron"
      ]);
    }

  }

  public function update_newsmaking_cron_status($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $country = isset($_POST['mode']) ? sanitize_text_field($_POST['mode']) : null;
    $country = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : null;

    $cron_settings = array();

    $cron_settings = [
      "mode" => $mode,
      "email" => $email
    ];

    update_option("syrus-ai-newsmaking-cron-settings", $cron_settings);

    $syrusAIPlugin->insert_log("Newsmaking", "Update cron status");

    $status_cron = get_option("syrus-ai-enable-cron-newsmaking", true);

    if($status_cron == "1") {
      update_option("syrus-ai-enable-cron-newsmaking", "0");
      $syrusAIPlugin->insert_log("cron_status", "Disactivated");
    } else {
      update_option("syrus-ai-enable-cron-newsmaking", "1");
      $syrusAIPlugin->insert_log("cron_status", "Activeted");
    }

    $status_cron = get_option("syrus-ai-enable-cron-newsmaking", true);

    self::return_response([
      'success' => "success",
      'status' => $status_cron
    ]);
  }

  public function news_making($types = ["priv"]) {
    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;
    global $wpdb;

    $url = "https://developers.syrus.it/api/wp/v1/newsmaking";
    
    // $apiKey = get_option("syrus_ai_newsapi_token", true);
    $token = $syrusAIPlugin->get_chatgpt_token();
    $org_token = $syrusAIPlugin->get_chatgpt_organization_id();
    
    $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : null;
    $keywords = isset($_POST['keywords']) ? sanitize_text_field($_POST['keywords']) : null;

    $res = wp_remote_request($url, [
      'method' => "POST",
      'body' => [
        'apiKey' => $token,
        'orgApiKey' => $org_token,
        'country' => $country,
        'keywords' => $keywords,
      ]
    ]);

    $response_body = wp_remote_retrieve_body($res);
    $data = json_decode($response_body, true);

    var_dump($res);die;

    if($data['code'] == "apiKeyInvalid") {
      $syrusAIPlugin->insert_log("Newsmaking", "Invalid api key");

      self::return_response([
        'status' => $data['code']
      ]);
    }

    $newsMaking = $data['articles'];

    if(!$newsMaking)
      $newsMaking = [];

    if(!empty($newsMaking)){

      $table_name = $wpdb->prefix . 'newsmaking';

      foreach($newsMaking as $news){

        $html = $news['title'];

        $pattern = '/<a\s[^>]*href=(["\'])(.*?)\1[^>]*>/i';

        // Trova tutte le corrispondenze nell'HTML
        preg_match_all($pattern, $html, $matches);

        // Ottieni i valori dell'attributo href da tutte le corrispondenze
        $link = $matches[2][0];

        $pattern = '/<a[^>]*>(.*?)<\/a>/i';

        // Trova la prima corrispondenza nell'HTML
        preg_match($pattern, $html, $match);

        $title = $match[1];

        $date = $news['date'];

        $date = gmdate('Y-m-d', strtotime(str_replace('/', '-', $date)));

        $wpdb->insert(
          $table_name,
          array(
            'title' => $title,
            'link' => $link,
            'author' => $news['author'],
            'newsmaking_type' => "keyword",
            'category' => null,
            'country' => null,
            'keywords' => $keyword,
            'domain' => $domain,
            'date' => $date
          )
        );
      }
    }

    $syrusAIPlugin->insert_log("Newsmaking", "Newsmaking keyword mode complete (Manual mode)");

    self::return_response($res);
  }

  public function news_making2($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;
    global $wpdb;

    $url = "https://developers.syrus.it/api/wp/v1/newsmaking";

    $apiKey = get_option("syrus_ai_newsapi_token", true);
    $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : null;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : null;

    $res = wp_remote_request($url, [
      'method' => "POST",
      'body' => [
        'type' => "second",
        'apiKey' => $apiKey,
        'category' => $category,
        'country' => $country
      ]
    ]);

    $response_body = wp_remote_retrieve_body($res);
    $data = json_decode($response_body, true);

    if($data['code'] == "apiKeyInvalid") {
      $syrusAIPlugin->insert_log("Newsmaking", "Invalid api key");

      self::return_response([
        'status' => $data['code']
      ]);
    }

    $newsMaking = $data['articles'];

    if(!$newsMaking)
      $newsMaking = [];

    if(!empty($newsMaking)){

      $table_name = $wpdb->prefix . 'newsmaking';

      foreach($newsMaking as $news){

        $html = $news['title'];

        $pattern = '/<a\s[^>]*href=(["\'])(.*?)\1[^>]*>/i';

        // Trova tutte le corrispondenze nell'HTML
        preg_match_all($pattern, $html, $matches);

        // Ottieni i valori dell'attributo href da tutte le corrispondenze
        $link = $matches[2][0];

        $pattern = '/<a[^>]*>(.*?)<\/a>/i';

        // Trova la prima corrispondenza nell'HTML
        preg_match($pattern, $html, $match);

        $title = $match[1];

        $date = $news['date'];

        $date = gmdate('Y-m-d', strtotime(str_replace('/', '-', $date)));

        $wpdb->insert(
          $table_name,
          array(
            'title' => $title,
            'link' => $link,
            'author' => $news['author'],
            'newsmaking_type' => "category",
            'category' => $category,
            'country' => $country,
            'keywords' => null,
            'domain' => null,
            'date' => $date
          )
        );
      }
    }

    // $html = $syrusAITemplate->_includi($syrusAIPlugin->settings['plugin_path'] . '/admin/partials/news_making_partials.php', compact('newsMaking'));

    $syrusAIPlugin->insert_log("Newsmaking", "Newsmaking basic mode complete (Manual mode)");

    self::return_response([
      'status' => "success"
    ]);
  }

  public function clearNews($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : null;

    $syrusAIPlugin->clearNews($category);

    self::return_response([
      'status' => "success",
    ]);
  }

  public function save_jet_lag($types = ['priv']) {
    global $syrusAIPlugin;
    global $syrusAIAI;

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $syrusAIAI->genera_contenuto_DeepL("", true);

    $jet_lag = isset($_POST['jet_lag']) ? sanitize_text_field($_POST['jet_lag']) : null;

    update_option("syrus-ai-jet-lag", $jet_lag);

    $syrusAIPlugin->insert_log("Jet lag", "Jet lag updated successfully");

    self::return_response([
      'status' => "success",
    ]);
  }

  public function save_cron_hour($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $cron_hour = isset($_POST['cron_hour']) ? sanitize_text_field($_POST['cron_hour']) : null;

    update_option("syrus-ai-cron-hour", $cron_hour);

    $syrusAIPlugin->insert_log("Jet lag", "Cron hour updated successfully");

    self::return_response([
      'status' => "success",
    ]);
  }

  public function save_prompt($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $prompt = isset($_POST['prompt']) ? sanitize_text_field($_POST['prompt']) : null;

    update_option('syrus_ai_prompt',$prompt);

    $syrusAIPlugin->insert_log("Chatgpt prompt", "Chatgpt prompt updated successfully");

    self::return_response([
      'status' => "success",
    ]);
  }

  //AUTOMATIC TRANSLATION

  public function automatic_translation($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;
    global $syrusAITemplate;
    global $wpdb;

    $domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : null;

    $to_exclude_db = $wpdb->get_results($wpdb->prepare(
      "SELECT id_wp FROM {$wpdb->prefix}translate_and_publish WHERE domain = %s",
      $domain,
    ));

    $to_exclude_arr = [];

    $api_url = "https://" . $domain . "/wp-json/wp/v2/posts?per_page=10&order=desc";

    if(!empty($to_exclude_db)) {
      foreach($to_exclude_db as $exc) {
        $to_exclude_arr[] = $exc->id_wp;
      }

      $excluded_ids = implode(',', array_unique($to_exclude_arr));

      $api_url .= "&exclude=" . $excluded_ids;
    }
    
    $response = wp_remote_get($api_url);

    if(isset($response->errors))
      self::return_response([
        'result' => "error"
      ]);

    $response = $response['body'];


    $data = json_decode($response, true);


    if (is_array($data)) {
      foreach ($data as $item) {
        $category = array();

        $id = $item['id'];
        $date = $item['date'];
        $link = $item['link'];
        $title = $item['title']['rendered'];
        $content = $item['content']['rendered'];
        $content = base64_encode($content);
        $categories = $item['categories'];
        $tags = $item['tags'];
        $media = $item['jetpack_featured_media_url'];

        foreach($categories as $single_category) {
          $category[] = $syrusAIPlugin->get_category_name($single_category, $domain);
        }

        $tags_arr = [];

        foreach($tags as $single_tags) {
          $tags_arr[] = $syrusAIPlugin->get_tag_name($single_tags, $domain);
        }

        $table_name = $wpdb->prefix . 'translate_and_publish';

        $wpdb->insert(
          $table_name,
          array(
            'id_wp' => $id,
            'date' => $date,
            'link' => $link,
            'title' => $title,
            'content' => $content,
            'categories' => implode(", ", $category),
            'tags' => implode(", ", $tags_arr),
            'media' => $media,
            'domain' => $domain
          )
        );

      }
    }

    self::return_response([
      'result' => "success"
    ]);
  }

  public function delete_article_translate($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $id_wp = isset($_POST['id_wp']) ? (int) sanitize_text_field($_POST['id_wp']) : null;

    $syrusAIPlugin->delete_article_translate($id_wp);

    return "1";
  }

  public function translate_publish_articles($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;
    global $syrusAIAI;

    $id_wp = isset($_POST['id_wp']) ? (int) sanitize_text_field($_POST['id_wp']) : null;

    $article = $syrusAIPlugin->get_article_db($id_wp);

    $language = get_locale();
    $language = explode('_',$language)[0];

    $short = substr($language, 0, 2);

    $language = $syrusAIPlugin->get_language($short);

    $titolo = $article[0]->title;
    $contenuto = $article[0]->content;
    $categorie = implode(', ', array_unique(explode(', ',$article[0]->categories)));

    $content = '';
    $title = '';
    $translated_categories = '';

    if(isset($_POST['ai']) && sanitize_text_field($_POST['ai']) == 'openai') {
      $prompt_content = "Translate the following title '$titolo' and then the entire content provided at the end of this message into $language, starting from the very beginning, regardless of whether it starts with a title or a paragraph.
      While translating, adapt the article for WordPress by using HTML tags like <h2>, <h3>, <li>, and <strong> to structure the text.
      Ensure that the translation adheres to the most popular SEO techniques, focusing on readability and relevant keyword usage.
      Make the translation comply with SEO rules but do not summarize the content of the article, maintain fidelity to the original content!
      The response json must have only the three entries 'title', 'content' and 'categories'.
      About categories, given the following: " . $categorie . ", find relevant categories of the content of the article in the same language $language.
      The value of 'categories' entry the in response json must be the set of translated categories separated by a single comma without
      spaces (example: category1, category2,...) and without adding words. If you find images in the article you must reuse them all, no more, no less, 
      and you must insert them into the new translated text with img tags, and their position in the text must be consistent with it. 
      The content of articles is as follows (it's encoded in base64): $contenuto";
  
      $res = $syrusAIAI->costruisciArticoloAI($prompt_content);
  
      if(is_wp_error($res)) {
        self::return_response([
          'result' => "error",
          'error' => $res
        ]);
      }
      else {
        $res = json_decode($res);
      }
  
      $content = $res->content;
      $title = $res->title;
      $translated_categories = $res->categories;
    }
    else {
      $content = $syrusAIAI->genera_contenuto_DeepL($contenuto, true);
      $title = $syrusAIAI->genera_contenuto_DeepL($titolo);
      $translated_categories = $syrusAIAI->genera_contenuto_DeepL($categorie);
    }

    $image_url = $article[0]->media;

    $regex = '/<a href="https:\/\/syrus[^"]*">(.*?)<\/a>/i';
    $content = preg_replace($regex, '$1', $content);

    $post_id = isset($_POST['post_id']) ? (int) sanitize_text_field($_POST['post_id']) : null;

    $post_id = $post_id ? $syrusAIPlugin->create_post($title, $content, "draft", $post_id) : $syrusAIPlugin->create_post($title, $content, "draft");

    $url = $image_url;
    $desc = "image description";
    $image = media_sideload_image($url, $post_id, $desc, 'id');

    set_post_thumbnail( $post_id, $image );

    foreach(explode(',',$translated_categories) as $i => $category) {
      $category_id = wp_create_category($category);
      $categories_array[$i] = $category_id;
    }

    wp_set_post_categories($post_id, $categories_array);


    $syrusAIPlugin->update_translate($article[0]->content, $post_id);

    $syrusAIPlugin->insert_log("Translate and Publish", "Create article with title: " . $title);

    self::return_response([
      'result' => "success"
    ]);
  }

  public function save_domains_automation_translation($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $domains = isset($_POST['domains']) ? sanitize_text_field($_POST['domains']) : null;

    update_option("syrus-ai-domains-automation-translation", $domains);
  }

  public function save_domain_translation($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : null;

    update_option("syrus-ai-domain-translation", $domain);
  }

  public function control_sharing($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $option = get_option("syrus-ai-enable-social", true);

    if($option == "1") {
      update_option("syrus-ai-enable-social", "0");
    } else {
      update_option("syrus-ai-enable-social", "1");
    }

    $option = get_option("syrus-ai-enable-social", true);

    self::return_response([
      'status' => "success",
      'share' => $option
    ]);
  }

  public function save_where_post($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $social = isset($_POST['social']) ? sanitize_text_field($_POST['social']) : null;
    $where = isset($_POST['where']) ? sanitize_text_field($_POST['where']) : null;


    $option = get_option('syrus-ai-connected-social') ? unserialize(get_option('syrus-ai-connected-social')) : [];

    $option[$social]['where_post'] = $where == '0' ? 0 : $where;

    update_option("syrus-ai-connected-social", serialize($option));

    self::return_response([
      'status' => "success",
    ]);
  }

  public function import_configuration($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

      $configurationToken = isset($_POST['configurationToken']) ? sanitize_text_field($_POST['configurationToken']) : null;

      if(!$configurationToken)
          self::return_response([
            'status' => "error",
            'result' => "Missing configuration token"
          ]);

      $bearerToken = get_option("syrus_ai_bearer_token", true);
      $url = "https://developers.syrus.it/api/wp/v1/import/configuration";

      $headers = [
          'Authorization' => 'Bearer ' . $bearerToken
      ];

      $res = wp_remote_post($url, [
          'headers' => $headers,
          'body' => [
              'configurationToken' => $configurationToken
          ]
      ]);

      $response_body = wp_remote_retrieve_body($res);
      $data = json_decode($response_body, true);

      self::return_response($data);
  }

  public function check_domain_translate_and_add($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : null;

    if(!$domain)
      self::return_response([
        'status' => "error",
        'message' => esc_html("Invalid domain",'syrus-ai'),
      ]);

    $currentAITranslationDomains = get_option('syrus-ai-translations-domains') ? unserialize(get_option('syrus-ai-translations-domains')) : [];

    // Check domain already added
    $exists = false;
    foreach($currentAITranslationDomains as $d) {
      if($d['domain'] != $domain)
        continue;

      $exists = true;
    }

    if($exists)
      self::return_response([
        'status' => "error",
        'message' => esc_html("The domain was already added",'syrus-ai'),
      ]);

    // Check HTTP Status
    $url = "https://" . $domain . "/";
    $headers = @get_headers($url);
    $status = intval(substr($headers[0], 9, 3));

    if($status != 200)
      self::return_response([
        'status' => "error",
        'message' => esc_html("The domain is not responding correctly",'syrus-ai'),
      ]);

    // Check is WordPress
    $wordpress_endpoint = $url . "wp-json/";
    $headers = @get_headers($wordpress_endpoint);
    $status = intval(substr($headers[0], 9, 3));

    if($status != 200)
      self::return_response([
        'status' => "error",
        'message' => esc_html("The website entered does not correspond to a WordPress website",'syrus-ai') . '(1)',
      ]);

    $res = wp_remote_get($wordpress_endpoint);

    if(is_wp_error($res))
      self::return_response([
        'status' => "error",
        'message' => esc_html("The website entered has encountered a WordPress error",'syrus-ai'),
      ]);

    $body = json_decode(wp_remote_retrieve_body($res));

    if(!$body || !isset($body->name))
      self::return_response([
        'status' => "error",
        'message' => esc_html("The website entered does not correspond to a WordPress website",'syrus-ai') . '(2)',
      ]);

    $website_name = $body->name;

    $currentAITranslationDomains[] = [
      'domain' => $domain,
      'name' => $website_name
    ];

    update_option('syrus-ai-translations-domains',serialize($currentAITranslationDomains));

    self::return_response([
      'status' => "success",
      'message' => esc_html("The domain was added successfully",'syrus-ai'),
    ]);

  }

  function remove_domain_translate($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : null;

    if(!$domain)
      self::return_response([
        'status' => "error",
        'message' => esc_html("Invalid domain",'syrus-ai'),
      ]);

    $currentAITranslationDomains = get_option('syrus-ai-translations-domains') ? unserialize(get_option('syrus-ai-translations-domains')) : [];
    $newDomains = [];

    foreach($currentAITranslationDomains as $d) {
      if($d['domain'] != $domain)
        $newDomains[] = $d;
    }

    update_option('syrus-ai-translations-domains',serialize($newDomains));

    self::return_response([
      'status' => "success",
      'message' => esc_html("The domain was removed successfully",'syrus-ai'),
    ]);

  }

  function genera_nonce($types = ['priv']) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    $actionForNonce = isset($_POST['actionForNonce']) ? sanitize_text_field($_POST['actionForNonce']) : null;

    $newNonce = wp_create_nonce($actionForNonce);

    self::return_response([
      'status' => "success",
      'nonce' => $newNonce
    ]);
  }

  function generate_article_ws($types = ['priv']) {
    global $syrusAIPlugin;

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);


    $ws_key = get_option('syrus_ai_writesonic_token');
    $link = isset($_POST['link']) ? sanitize_url($_POST['link']) : null;

    $language = substr(get_locale(), 0, 2);
    $url = 'https://api.writesonic.com/v2/business/content/article-rewriter?engine=premium&language=' . $language . '&num_copies=1';

    $headers = [
      'X-API-KEY' => $ws_key,
      'Aaccept' => 'application/json',
      'Content-Type' => 'application/json',
    ];

    $api_res = wp_remote_post($url,[
      'method' => 'POST',
      'timeout' => 120,
      'headers' => $headers,
      'body' => wp_json_encode(['link' => $link]),
    ]);

    $res = json_decode(wp_remote_retrieve_body($api_res), true);

    // var_dump($res[0]['text']);die;
    // $risposta = $res[0]['text'];

    if(!$res) {
      echo wp_json_encode([
        'status' => "error",
        'result' => $api_res,
      ]);
    }

    $htmlString = $res[0]['text'];

    $title_pos_start = strpos($htmlString, '<h1>');

    if($title_pos_start !== false) {
      $title_pos_end = strpos($htmlString, '</h1>', $title_pos_start);
      
      $title = substr($htmlString, $title_pos_start + 4, $title_pos_end - $title_pos_start - 4);

      $img_pos_start = strpos($htmlString, '<p>', $title_pos_end);
      $img_pos_end = strpos($htmlString, '</p>', $img_pos_start);

      $htmlString = substr_replace($htmlString, '', $title_pos_start, $img_pos_end - $title_pos_start + 4);

      // $htmlString = substr_replace($htmlString, '', $title_pos_start, $title_pos_end - $title_pos_start + 5);
    }

    echo wp_json_encode([
      'status' => "success",
      'result' => [
        'title' => $title,
        'content' => $htmlString
      ]
    ]);

    wp_die();
  }

  public function generate_post_thumbnail($types = ["priv"]) {
    global $syrusAIPlugin;

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);


    $url = "https://developers.syrus.it/api/wp/v1/ai/generate-post-thumbnail";

    $headers = [
      'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
    ];
  
    $organization_id = get_option('syrus_ai_chatgpt_organization_id') ?: null;
    $token = $syrusAIPlugin->get_chatgpt_token();

    $post_title = isset($_POST['post_title']) ? sanitize_text_field($_POST['post_title']) : null;
    $post_content = isset($_POST['post_content']) ? sanitize_text_field($_POST['post_content']) : null;

    $api_res = wp_remote_request($url, [
      'method'  => "POST",
      'timeout' => 120,
      'headers' => $headers,
      'body' => [
        'post_title'      => base64_encode($post_title),
        'post_content'    => base64_encode($post_content),
        'organization_id' => $organization_id,
        'token'           => $token,
      ]
    ]);

    $res = json_decode(wp_remote_retrieve_body($api_res), true);

    if(isset($res['error']) || $res['status'] == 'error') 
      return self::return_response([
        'status' => 'error',
      ]);

    $image_url = $res['image_url'];

    $tmp_file = download_url( $image_url );

    $is_webp_allowed = false;
      
    $post_title = isset($_POST['post_title']) ? sanitize_text_field($_POST['post_title']) : null;


    $file_array = array(
      'name' => sanitize_title($post_title) . '-' . uniqid() . ($is_webp_allowed ? '.webp' : '.png'),
      'type' => $is_webp_allowed ? 'image/webp' : 'image/png',
      'tmp_name' => $tmp_file,
      'error' => 0,
    );

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    $id = media_handle_sideload($file_array, 0);
    
    if(is_wp_error($id))
      return self::return_response([
        'status' => 'error',
        'result' => $id->get_error_message(),
      ]);

    return self::return_response([
      'status' => 'success',
    ]);

  }


  public function save_cloudflare_site_key($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $site_key = isset($_POST['cloudflare_site_key']) ? sanitize_text_field($_POST['cloudflare_site_key']) : null;

    $current_contact_form_settings = $syrusAIPlugin->get_contact_form_settings();
    $current_contact_form_settings['cloudflare_turnstile']['site_key'] = $site_key;

    update_option('syrus_ai_contact_form_settings', serialize($current_contact_form_settings));

    self::return_response([
      'status' => "success",
    ]);
  }

  public function save_cloudflare_secret_key($types = ["priv"]) {

    if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
      self::return_response([
        'status' => "error",
      ]);

    global $syrusAIPlugin;

    $secret_key = isset($_POST['cloudflare_secret_key']) ? sanitize_text_field($_POST['cloudflare_secret_key']) : null;

    $current_contact_form_settings = $syrusAIPlugin->get_contact_form_settings();
    $current_contact_form_settings['cloudflare_turnstile']['secret_key'] = $secret_key;

    update_option('syrus_ai_contact_form_settings', serialize($current_contact_form_settings));

    self::return_response([
      'status' => "success",
    ]);
  }
}
