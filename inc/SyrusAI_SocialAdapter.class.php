<?php

if(!defined('ABSPATH'))
    exit;

class SyrusSocialAdapter {
    public $settings;

    public function initialize($settings) {
        $this->settings = $settings;
    }

    public function get_share_facebook_endpoint() {
      return "https://developers.syrus.it/api/wp/v1/facebook/share";
    }

    public function get_share_linkedin_endpoint() {
      return "https://developers.syrus.it/api/wp/v1/linkedin/share";
    }

    public function get_share_twitter_endpoint() {
      return "https://developers.syrus.it/api/wp/v1/twitter/share";
    }

    public function get_share_instagram_endpoint() {
      return "https://developers.syrus.it/api/wp/v1/instagram/share";
    }

    public function revoke_fb_token() {
      $url = "https://developers.syrus.it/api/wp/v1/facebook/revoke";

      $headers = array(
        'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
      );

      $res = wp_remote_request($url, [
        'method' => "DELETE",
        'timeout' => 15,
        'headers' => $headers,
        'body' => []
      ]);

      $response_body = wp_remote_retrieve_body($res);
      $data = json_decode($response_body, true);

      if(isset($data['success'])) {
        $current_connected_social = get_option('syrus-ai-connected-social') ? unserialize(get_option('syrus-ai-connected-social')) : [];
        unset($current_connected_social['facebook']);
        update_option('syrus-ai-connected-social',serialize($current_connected_social));
      } else {
        return "error";
      }

      return "success";
    }

    public function revoke_ig_token() {
      $url = "https://developers.syrus.it/api/wp/v1/instagram/revoke";

      $headers = array(
        'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
      );

      $res = wp_remote_request($url, [
        'method' => "DELETE",
        'timeout' => 15,
        'headers' => $headers,
        'body' => []
      ]);

      $response_body = wp_remote_retrieve_body($res);
      $data = json_decode($response_body, true);

      if(isset($data['success'])) {
        $current_connected_social = get_option('syrus-ai-connected-social') ? unserialize(get_option('syrus-ai-connected-social')) : [];
        unset($current_connected_social['instagram']);
        update_option('syrus-ai-connected-social',serialize($current_connected_social));
      } else {
        return "error";
      }

      return "success";
    }

    public function share_facebook($params = []) {
      $text = isset($params['text']) && $params['text'] ? $params['text'] : "Lorem ipsum";
      $image_url = $params['image_url'];
      $post_url = $params['post_url'];

      $url = "https://developers.syrus.it/api/wp/v1/facebook/share";

      $headers = array(
        'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
      );

      $res = wp_remote_post($url, [
        'headers' => $headers,
        'body' => [
          'text' => $text,
          'image_url' => $image_url,
          'post_url' => $post_url
        ],
      ]);

      $response_body = wp_remote_retrieve_body($res);

      return $response_body;
    }

    public function share_instagram($params = []) {
      $text = isset($params['text']) && $params['text'] ? $params['text'] : "Lorem ipsum";
      $image_url = $params['image_url'];
      $post_url = $params['post_url'];

      $url = "https://developers.syrus.it/api/wp/v1/instagram/share";

      $headers = array(
        'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
      );

      $res = wp_remote_post($url, [
        'headers' => $headers,
        'body' => [
          'text' => $text,
          'image_url' => $image_url,
          'post_url' => $post_url
        ],
      ]);

      $response_body = wp_remote_retrieve_body($res);

      return $response_body;
    }

    public function share_twitter($params = []) {
      $text = isset($params['text']) && $params['text'] ? $params['text'] : "Lorem ipsum";
      $image_url = $params['image_url'];
      $post_url = $params['post_url'];

      $url = "https://developers.syrus.it/api/wp/v1/twitter/share";

      $headers = array(
        'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
      );

      $res = wp_remote_post($url, [
        'headers' => $headers,
        'body' => [
          'text' => $text,
          'image_url' => $image_url,
          'post_url' => $post_url
        ],
      ]);

      $response_body = wp_remote_retrieve_body($res);

      return $response_body;
    }

    public function share_linkedin($params = []) {
      $text = isset($params['text']) && $params['text'] ? $params['text'] : "Lorem ipsum";
      $image_url = $params['image_url'];
      $post_url = $params['post_url'];

      $url = "https://developers.syrus.it/api/wp/v1/linkedin/share";

      $headers = array(
        'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
      );

      $res = wp_remote_post($url, [
        'headers' => $headers,
        'timeout' => 60,
        'body' => [
          'text' => $text,
          'image_url' => $image_url,
          'post_url' => $post_url,
        ]
      ]);

      $response_body = wp_remote_retrieve_body($res);

      return $response_body;
    }
}
