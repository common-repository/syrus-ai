<?php

use Yethee\Tiktoken\EncoderProvider;

class SyrusAI_AI {

    const ENDPOINT_CHATGPT = "https://api.openai.com/v1/completions";

    const ENDPOINT_DEEPL_FREE = "https://api-free.deepl.com/v2/translate";

    const ENDPOINT_CHECK_TOKEN = "https://developers.syrus.it/api/wp/v1/check_token";

    public function initialize() :void {

    }

    public function genera_contenuto_ChatGpt(string $prompt = "") {
        global $syrusAIPlugin;
        global $wpdb;

        $token = $syrusAIPlugin->get_chatgpt_token();
        $organization = $syrusAIPlugin->get_chatgpt_organization_id();

        $url = "https://api.openai.com/v1/chat/completions";

        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $token,
        ];

        if($organization)
            $headers["OpenAI-Organization"] = $organization;

        $body = [
            "model" => "gpt-4-1106-preview",
            "response_format" => [
                "type" => "json_object"
            ],
            "messages" => [
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ]
        ];

        $args = [
            'method' => 'POST',
            'timeout' => 180,
            'headers' => $headers,
            'body' => wp_json_encode($body),
        ];

        $api_res = wp_remote_post($url, $args);

        if(is_wp_error($api_res))
            return $api_res;


        $res = json_decode( wp_remote_retrieve_body( $api_res ), true );
        $risposta = $res['choices'][0]['message']['content'];

        // LOG CHIAMATA CHATGPT
        $wpdb->insert($wpdb->prefix . "syrus_ai_be_logs",[
            'type' => "Chat GPT",
            'generic_value' => serialize([
                'endpoint' => $url,
                'prompt' => $prompt,
                'promptToken' => $promptToken,
            ]),
            'request' => serialize($args),
            'response' => serialize(wp_remote_retrieve_body( $api_res )),
            'created_at' => gmdate("Y-m-d H:i:s"),
        ]);

        return $risposta;
    }

    public function costruisciArticoloAI($prompt) {
        global $syrusAIPlugin;
        global $wpdb;

        $token = $syrusAIPlugin->get_chatgpt_token();
        $organization = $syrusAIPlugin->get_chatgpt_organization_id();

        $url = "https://api.openai.com/v1/chat/completions";

        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $token,
        ];

        if($organization)
            $headers["OpenAI-Organization"] = $organization;

        $body = [
            "model" => "gpt-4-1106-preview",
            "response_format" => [
                "type" => "json_object"
            ],
            "messages" => [
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ]
        ];

        $args = [
            'method' => 'POST',
            'timeout' => 180,
            'headers' => $headers,
            'body' => wp_json_encode($body),
        ];

        $api_res = wp_remote_post($url, $args);

        if(is_wp_error($api_res))
            return $api_res;


        $res = json_decode( wp_remote_retrieve_body( $api_res ), true );
        $risposta = $res['choices'][0]['message']['content'];

        // LOG CHIAMATA CHATGPT
        $wpdb->insert($wpdb->prefix . "syrus_ai_be_logs",[
            'type' => "Chat GPT",
            'generic_value' => serialize([
                'endpoint' => $url,
                'prompt' => $prompt,
            ]),
            'request' => serialize($args),
            'response' => serialize(wp_remote_retrieve_body( $api_res )),
            'created_at' => gmdate("Y-m-d H:i:s"),
        ]);

        return $risposta;
    }


    public function textToToken($text) {
        $url = self::ENDPOINT_CHECK_TOKEN;

        $headers = array(
            'Authorization' => 'Bearer ' . get_option("syrus_ai_bearer_token", true)
        );
    
        $res = wp_remote_post($url, [
            'headers' => $headers,
            'body' => [
                'text' => $text
            ]
        ]);
    
        $response_body = wp_remote_retrieve_body($res);
        $tokenCount = json_decode($response_body, true);

        return $tokenCount['success'];
    }

    public function reduceToken($text) {
        $tokenCount = $this->textToToken($text);
        $diff = $tokenCount - 1850;
        $cutPosition = strlen($text) - round($diff / $tokenCount * strlen($text));
        $text = mb_substr($text, 0, $cutPosition);
        return $text;
    }

    public function genera_contenuto_DeepL(string $text_to_translate = "", bool $isHtml = false) {
        global $syrusAIPlugin;
        $token = $syrusAIPlugin->get_deepl_token();

        $url = self::ENDPOINT_DEEPL_FREE;

        $text = array();

        $text[] = $isHtml ? base64_decode($text_to_translate) : $text_to_translate;

        $language = get_locale();

        $short = (explode('_', $language)[0] != 'pt' || explode('_', $language)[0] != 'en') ? strtoupper(explode('_', $language)[0]) : strtoupper(str_replace('_', '-', $language));

        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "DeepL-Auth-Key " . $token
        ];

        $body = [
            "text" => $text,
            "target_lang" => $short,
        ];

        if($isHtml)
            $body = [
                "text" => $text,
                "target_lang" => $short,
                "tag_handling" => "html"
            ];

        if(in_array(strtoupper($short), ['DE', 'FR', 'IT', 'ES', 'NL', 'PL', 'PT-BR', 'PT-PT', 'JA', 'RU']))
            $body['formality'] = 'more';

        $args = [
            'method' => 'POST',
            'timeout' => 120,
            'headers' => $headers,
            'body' => wp_json_encode($body),
        ];

        $api_res = wp_remote_post($url, $args);

        $res = json_decode(wp_remote_retrieve_body($api_res), true);


        return $res['translations'][0]['text'];
    }

}
