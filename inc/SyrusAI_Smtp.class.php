<?php

class SyrusSmtpServer {

    public $settings;

    public function initialize($settings) {
        $this->settings = $settings;
    }

    public function sendMail($host, $port, $username, $password, $to, $from, $subject, $body) {
        // Imposta le opzioni SMTP usando i filtri di WordPress
        add_filter('wp_mail_from', array($this, 'set_email_from'));
        add_filter('wp_mail_from_name', array($this, 'set_email_from_name'));
        add_action('phpmailer_init', function ($phpmailer) use ($host, $port, $username, $password) {
            $phpmailer->isSMTP();
            $phpmailer->Host = $host;
            $phpmailer->Port = $port;
            $phpmailer->SMTPAuth = true;
            $phpmailer->SMTPSecure = 'tls';
            $phpmailer->Username = $username;
            $phpmailer->Password = $password;
        });

        // Invia l'e-mail utilizzando wp_mail()
        $sent = wp_mail($to, $subject, $body);

        // Rimuovi i filtri dopo l'invio dell'e-mail
        remove_filter('wp_mail_from', array($this, 'set_email_from'));
        remove_filter('wp_mail_from_name', array($this, 'set_email_from_name'));

        // Verifica se l'e-mail è stata inviata con successo
        if ($sent) {
            $response = array(
                'success' => "Email inviata con successo",
            );
        } else {
            $response = array(
                'error' => "Impossibile inviare l'email",
            );
        }

        return $response;
    }

    public function set_email_from($original_email_address) {
        // Imposta l'indirizzo e-mail mittente
        return 'your_gmail_account@gmail.com';
    }

    public function set_email_from_name($original_email_from) {
        // Imposta il nome mittente
        return 'Your Name';
    }
}
