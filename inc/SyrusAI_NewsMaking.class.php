<?php

if(!defined('ABSPATH'))
    exit;

class SyrusAI_NewsMaking {
    public $settings;
    protected $table = "newsmaking";
    const ENDPOINT = "https://developers.syrus.it/api/wp/v1/newsmaking";

    public function getTable() {
        global $wpdb;
        return $wpdb->prefix . $this->table;
    }

    public function initialize($settings = []) {
        $this->settings = $settings;
    }


    public function checkOldArticles() {
        global $wpdb;
        $table_name = $this->getTable();
        $current_date = gmdate("Y-m-d H:i:s",strtotime("-20 days"));
        
        $query = "SELECT 
            $table_name.*
        FROM 
            $table_name
        WHERE 
            $table_name.date <= '$current_date' 
        ";
        
        $query = $wpbd->prepare($query);
        $res = $query->get_results($query);
    }

    public function makeCountryNewsMaking($doing_mode = "manual") {

        if(!isset($_POST['_ajaxNonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_ajaxNonce'])) , $_POST['action']))
            self::return_response([
            'status' => "error",
            ]);

        global $syrusAIPlugin;
        global $wpdb;

        $url = self::ENDPOINT;

        $apiKey = get_option("syrus_ai_newsapi_token", true);
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : null;
        $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : null;

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
            $xml = simplexml_load_string($news['title']);
            $title = wp_strip_all_tags($news['title']);
            $link = $xml->attributes()->href->__toString();
            $date_arr = explode('/',$news['date']);
            $date_arr = array_reverse($date_arr);
            $date = implode('-',$date_arr); 

            $wpdb->insert( 
            $table_name, 
            array( 
                'title' => $title,
                'link' => $link,
                'author' => $news['author'], 
                'date' => $news['date'], 
            )
            );
        }
        }

        $syrusAIPlugin->insert_log("Newsmaking", "Newsmaking basic mode complete (Manual mode)");

        self::return_response([
        'status' => "success"
        ]);
    }

    public function makeKeywordNewsMaking($doing_mode = "manual") {
        global $syrusAIPlugin;
        global $wpdb;
        $table_name = $this->getTable();
        $url = self::ENDPOINT;
        $parameters = get_option("syrus-ai-newsmaking-settigs", true);
        $apiKey = get_option("syrus_ai_newsapi_token", true);

        $domain = $parameters['domain'];
        $language = $parameters['language'];
        $keyword = $parameters['keyword'];
        $date = $parameters['date'];

        $res = wp_remote_request($url, [
            'method' => "POST",
            'body' => [
                'type' => "first",
                'apiKey' => $apiKey,
                'domain' => $domain,
                'language' => $language,
                'q' => $keyword,
                'from' => $date
            ]
        ]);
            
        $response_body = wp_remote_retrieve_body($res);
        $data = json_decode($response_body, true);

        if($data['code'] == "apiKeyInvalid") {
            $syrusAIPlugin->insert_log("Newsmaking", "Invalid api key");
            return [
                'status' => $data['code']
            ];
        }

        $newsMaking = $data['articles'];

        if(!$newsMaking)
            $newsMaking = [];
          
        foreach($newsMaking as $news){
            $xml = simplexml_load_string($news['title']);
            $title = wp_strip_all_tags($news['title']);
            $link = $xml->attributes()->href->__toString();
            $author = $news['author'];
            $date_arr = explode('/',$news['date']);
            $date_arr = array_reverse($date_arr);
            $date = implode('-',$date_arr); 
            
            $wpdb->insert($table_name, 
                [
                  'title' => $title,
                  'link' => $link,
                  'author' => $author, 
                  'date' => $date, 
                ]
            );

        }
        
        if($doing_mode == "manual") {
            $syrusAIPlugin->insert_log("Newsmaking", "Newsmaking basic mode complete (Manual mode)");
        } else {
            // QUello che cazzo ti pare!
        }

        return ['status' => 'success'];
    }

}