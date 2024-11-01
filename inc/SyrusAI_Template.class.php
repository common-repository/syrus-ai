<?php

if(!defined('ABSPATH'))
    exit;

class SyrusAI_Template {
    public $settings;
    public $syrus_theme_active = false;

    function __construct() {
        
    }

    public function initialize($settings, $syrus_theme_active) {
        $this->settings = $settings;
        $this->syrus_theme_active = $syrus_theme_active;
    }

    public function include($path) {
        $file_path = $this->settings['plugin_path'] . $path . '.php';

        if(!file_exists($file_path)) {
            esc_html_e("Page does not exists!",'syrus-ai');
            return false;
        }

        include_once $file_path;
        return true;
    }

    public function _includi($percorso_file, $variabili = array(), $stampa = false) {
        $output = NULL;
        if(file_exists($percorso_file)){
            extract($variabili);
            ob_start();
            include $percorso_file;
            $output = ob_get_clean();
        }
        if ($stampa) {
            print esc_html($output);
        }
        return $output;
    }
}