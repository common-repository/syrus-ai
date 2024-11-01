<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    global $wp_version;
    global $syrusAIPlugin;


?>

<h3><?php esc_html_e('Support','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can ask for help if you have encountered a problem or if you want to provide us with feedback on your experience with our plugin.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">1) <?php esc_html_e("Generic Info",'syrus-ai') ?></h4>
        <p><?php esc_html_e("This is some information about your site, it could help you solve the problem or you can communicate it to us to make us quicker in responding to you.",'syrus-ai') ?></p>
    </div>
    <div class="col-12 ps-4">
        <table class="table-support table-bordered">
            <tbody>
                <tr>
                    <th><?php esc_html_e("WordPress Version",'syrus-ai') ?></th>
                    <td><?php echo esc_html($wp_version) ?></td>
                </tr>

                <tr>
                    <th><?php esc_html_e("PHP Version",'syrus-ai') ?></th>
                    <td><?php echo esc_html(phpversion()) ?></td>
                </tr>

                <tr>
                    <th><?php esc_html_e("Your domain",'syrus-ai') ?></th>
                    <td><?php echo esc_html(wp_parse_url( get_site_url(), PHP_URL_HOST )) ?></td>
                </tr>

                <tr>
                    <th><?php esc_html_e("Syrus AI Plugin Version",'syrus-ai') ?></th>
                    <td><?php echo esc_html($syrusAIPlugin->settings['plugin_version']) ?></td>
                </tr>

                <tr>
                    <th><?php esc_html_e("Syrus AI Plugin Token",'syrus-ai') ?><span class="attention"><?php esc_html_e("Do not share this token with anyone other than a technician from our support team",'syrus-ai') ?></span></th>
                    <td style="font-size: 10px"><?php echo esc_html(base64_encode(wp_json_encode([ 't' => get_option("syrus_ai_bearer_token", true), 'f' => $_SERVER['SERVER_NAME']]))); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row mb-5">
    <div class="col-8">
        <h4 class="m-0">2) <?php esc_html_e("Contact Us",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Below you will find useful contact details to get in touch with us. Please provide us with as much information as possible regarding the problem you are experiencing by sending us the information in the table above. If they are present you can also send us screenshots.",'syrus-ai') ?></p>
    </div>

    <div class="col-12">
        <div class="mb-2 d-flex gap-5 justify-content-start align-items-center">
            <span class="dashicons dashicons-admin-site-alt fs-3"></span>
            <p class="m-0 fs-4"><a class="text-black" href="<?php echo defined("SYRUS_THEME_POWERED_BY") ? esc_html(SYRUS_THEME_POWERED_BY) : 'https://syrusindustry.com/' ?>" target="_blank"><?php esc_html_e("Go to our website",'syrus-ai') ?></a></p>
        </div>

        <div class="mb-2 d-flex gap-5 justify-content-start align-items-center">
            <span class="dashicons dashicons-email-alt fs-3"></span>
            <p class="m-0 fs-4"><a class="text-decoration-none text-black" href="mailto:info@syrusindustry.com">info@syrusindustry.com</a></p>
        </div>

        <div class="mb-2 d-flex gap-5 justify-content-start align-items-center">
            <span class="dashicons dashicons-phone fs-3"></span>
            <p class="m-0 fs-4 text-black">+39 06 4522 14565</p>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12 p-5 text-center">
        <p class="m-0">❤️ <?php esc_html_e("Thank you for the support you provide us using our plugin","syrus-ai") ?></p>
    </div>
</div>
