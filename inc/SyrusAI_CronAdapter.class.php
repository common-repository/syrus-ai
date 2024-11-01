<?php

if (!defined('ABSPATH')) {
    exit;
}

class SyrusCronAdapter {
    public $settings;

    public function initialize($settings) {
        $this->settings = $settings;
    }

    public function check_cron_newsmaking() {
        $status_cron = get_option("syrus-ai-enable-cron-newsmaking", true);
        $hour_cron = get_option("syrus-ai-cron-hour", true);

        $jet_lag = get_option("syrus-ai-jet-lag", true);

        // date_default_timezone_set($jet_lag);

        $current_hour = gmdate('H:i');

        if ($status_cron == "1" && $current_hour == $hour_cron) {

            $mode = get_option("syrus-ai-newsmaking-cron-settings", true);

            if($mode == "general") {
                $this->news_making2();
            } else {
                $this->news_making();
            }

            $response = array(
                'success' => "Esecuzione del metodo riuscita.",
            );
        } else {
            $response = array(
                'success' => "Metodo non eseguito.",
            );
        }

        return $response;
    }

    public function news_making() {
        global $syrusAIPlugin;
        global $wpdb;

        $url = "https://developers.syrus.it/api/wp/v1/newsmaking";

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

        $email = get_option("syrus-ai-newsmaking-cron-settings", true);

        $to = $email['email'];
        $subject = 'Newsmaking Success Notification';
        $message = 'Dear Subscriber,

        We are pleased to inform you that the newsmaking was successful. Thank you for subscribing to our updates.

        Click the link for view your newsmaking: ' . get_site_url() . '/wp-admin/admin.php?page=syrus-ai-admin-page&tab=news-making' . '

        Best regards,
        Your News Team';

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: Syrus AI',
        );

        if ( wp_mail( $to, $subject, $message, $headers ) ) {

            $syrusAIPlugin->insert_log("Newsmaking", "Newsmaking basic mode complete (CRON)");

            $response = array(
            'success' => "Esecuzione del metodo riuscita.",
            );
            return $response;
        } else {
            $response = array(
            'success' => "Esecuzione del metodo non riuscita.",
            );
            return $response;
        }
    }

    public function news_making2() {
        global $wpdb;
        global $syrusAIPlugin;

        $url = "https://developers.syrus.it/api/wp/v1/newsmaking";

        $apiKey = get_option("syrus_ai_newsapi_token", true);

        $parameters = get_option("syrus-ai-newsmaking-settigs", true);

        $category = $parameters['category'];

        $country = $parameters['country'];

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

        $newsMaking = isset($data['articles']) ? $data['articles'] : [];

        if (!empty($newsMaking)) {
            $table_name = $wpdb->prefix . 'newsmaking';
            foreach ($newsMaking as $news) {
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

        $email = get_option("syrus-ai-newsmaking-cron-settings", true);

        $to = $email['email'];
        $subject = 'Newsmaking Success Notification';
        $message = 'Dear Subscriber,

        We are pleased to inform you that the newsmaking was successful. Thank you for subscribing to our updates.

        Click the link for view your newsmaking: ' . get_site_url() . '/wp-admin/admin.php?page=syrus-ai-admin-page&tab=news-making' . '

        Best regards,
        Your News Team';

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: Syrus AI',
        );

        if ( wp_mail( $to, $subject, $message, $headers ) ) {

            $syrusAIPlugin->insert_log("Newsmaking", "Newsmaking basic mode complete (CRON)");

            $response = array(
            'success' => "Esecuzione del metodo riuscita.",
            );
            return $response;
        } else {
            $response = array(
            'success' => "Esecuzione del metodo non riuscita.",
            );
            return $response;
        }
    }
}
