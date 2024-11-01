<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    global $syrusAIPlugin;
    $chatgpt_token = $syrusAIPlugin->get_chatgpt_token();
    $chatgpt_organization_id = $syrusAIPlugin->get_chatgpt_organization_id();
    $writesonic_token = $syrusAIPlugin->get_writesonic_token(); 
    $deepl_token = $syrusAIPlugin->get_deepl_token(); 
    $newsapi_token = $syrusAIPlugin->get_newsapi_token(); 
    $is_syrus_theme_active = $syrusAIPlugin->syrus_theme_active; 
    $translate_domain = $syrusAIPlugin->get_translate_domain(); 
    $newsmaking_settings = $syrusAIPlugin->get_newsmaking_settings(); 
    $timezone = $syrusAIPlugin->get_all_timezone(); 
    $jet_lag = $syrusAIPlugin->get_jet_lag(); 
    $hours = $syrusAIPlugin->get_hours(); 
    $cron_hour = $syrusAIPlugin->get_cron_hour();
    $contact_form_settings = $syrusAIPlugin->get_contact_form_settings();

?>
<h3><?php esc_html_e('Settings','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can add and edit tokens or API keys, which are necessary for the functioning of some plugin features.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row mb-5">
    <div class="col-auto">
        <h4 class="m-0">1) <?php esc_html_e("Tokens and API Keys",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Enter the tokens or API keys you received from the platforms in the appropriate spaces",'syrus-ai') ?></p>
    </div>

    <div class="col-12">
        <table class="table-advanced-settings">
            <tbody>
                <tr id="tr-NewsAPI" onclick="restoreThisRow(this)">
                    <th><label for="newsapi_token">NewsAPI</label></th>
                    <td><input type="text" class="form-control form-control-sm w-100" id="newsapi_token" name="newsapi_token" placeholder="Insert NewsAPI API Key" value="<?php echo esc_html($newsapi_token) ?: '' ?>"></td>
                    <td><button type="button" class="btn btn-primary btn-sm" id="btn-save-newsapi-token" onclick="syrusAI_save_newsapi_token()"><?php esc_html_e('Save') ?></button></td>
                    <td><p class="m-0"><?php esc_html_e("You can get a free API key from",'syrus-ai') ?><a href="https://newsapi.org/" target="_blank" class="ms-2"><?php esc_html_e("Here",'syrus-ai') ?></a></p></td>
                </tr>

                <tr id="tr-ChatGPT" onclick="restoreThisRow(this)">
                    <th><label for="chatgpt_token">ChatGPT</label></th>
                    <td><input type="text" class="form-control form-control-sm w-100" id="chatgpt_token" name="chatgpt_token" placeholder="ChatGPT token" autocomplete="off" value="<?php echo $chatgpt_token ? esc_html($chatgpt_token) : '' ?>" <?php echo esc_html($is_syrus_theme_active) ? 'readonly' : '' ?>></td>
                    <td>
                        <?php if(!$is_syrus_theme_active) { ?>
                            <button type="button" class="btn btn-primary btn-sm" id="btn-save-chatgpt-token" onclick="syrusAI_save_chatgpt_token()"><?php esc_html_e('Save') ?></button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-primary btn-sm disabled" disabled><?php esc_html_e('Save') ?></button>
                        <?php } ?>
                    </td>
                    <?php if($is_syrus_theme_active) { ?>
                        <td><p class="m-0"><?php esc_html_e("It appears that the Syrus theme is active. You can change this setting from",'syrus-ai') ?><a href="<?php echo esc_url(admin_url('admin.php?page=syrus-theme-page&tab=menu')) ?>" class="ms-2"><?php esc_html_e("Here",'syrus-ai') ?></a></p></td>
                    <?php } ?>
                </tr>

                <tr id="tr-ChatGPT-org" onclick="restoreThisRow(this)">
                    <th></th>
                    <td><input type="text" class="form-control form-control-sm w-100" id="chatgpt_organization_id" name="chatgpt_organization_id" placeholder="ChatGPT Organization ID" autocomplete="off" value="<?php echo $chatgpt_organization_id ? esc_html($chatgpt_organization_id) : '' ?>"></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-save-chatgpt-organization-id" onclick="syrusAI_save_chatgpt_organization_id()"><?php esc_html_e('Save') ?></button>
                    </td>
                </tr>

                <tr id="tr-DeepL" onclick="restoreThisRow(this)">
                    <th><label for="deepl_token">DeepL</label></th>
                    <td><input type="text" class="form-control form-control-sm w-100" id="deepl_token" name="deepl_token" placeholder="DeepL token" autocomplete="off" value="<?php echo $deepl_token ? esc_html($deepl_token) : '' ?>"></td>
                    <td><button type="button" class="btn btn-primary btn-sm" id="btn-save-deepl_token" onclick="syrusAI_save_deepl_token()"><?php esc_html_e('Save') ?></button></td>
                </tr>

                <tr id="tr-Writesonic" onclick="restoreThisRow(this)">
                    <th><label for="writesonic_token">Writesonic</label></th>
                    <td><input type="text" class="form-control form-control-sm w-100" id="writesonic_token" name="writesonic_token" placeholder="Writesonic token" autocomplete="off" value="<?php echo $writesonic_token ? esc_html($writesonic_token) : '' ?>"></td>
                    <td><button type="button" class="btn btn-primary btn-sm" id="btn-save-writesonic_token" onclick="syrusAI_save_writesonic_token()"><?php esc_html_e('Save') ?></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row mb-5">
    <div class="col-auto">
        <h4 class="m-0">2) <?php esc_html_e("News Making settings",'syrus-ai') ?></h4>
    </div>
    <div class="col-12">
        <table class="table-advanced-settings">
            <tbody>
                <tr>
                    <th><label for="">Timezone</label></th>
                    <td>
                        <select class="form-control selectJetLag">
                            <?php foreach($timezone as $zone) { ?>
                                <option <?php echo $zone == $jet_lag ? 'selected' : ''?>><?php echo esc_html($zone) ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-primary btn-sm" id="btn-save-newsapi-token" onclick="syrusAI_save_jet_lag()"><?php esc_html_e('Save') ?></button></td>
                    <td><p class="m-0"><?php esc_html_e("Change this configuration to change the time zone for sending the automatic news making email",'syrus-ai') ?></p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row mb-5">
    <div class="col-auto mb-3">
        <h4 class="m-0">2) <?php esc_html_e("Contact Forms settings",'syrus-ai') ?></h4>
    </div>
    <div class="col-12">
        <table class="table-advanced-settings">
            <tbody>
                <tr><th>Cloudflare Turnstile</th></tr>
                <tr>    
                    <th>Site key</th>
                    <td>
                        <input type="text" class="form-control form-control-sm w-100" id="cloudflare_site_key" name="cloudflare_site_key" placeholder="Site Key" autocomplete="off" value="<?php echo isset($contact_form_settings['cloudflare_turnstile']['site_key']) ? esc_html($contact_form_settings['cloudflare_turnstile']['site_key']) : '' ?>">
                    </td>
                    <td><button type="button" class="btn btn-primary btn-sm" id="btn-save-cloudflare-site-key" onclick="syrusAI_save_cloudflare_site_key()"><?php esc_html_e('Save') ?></button></td>
                </tr>
                <tr>    
                    <th>Secret key</th>
                    <td>
                        <input type="password" class="form-control form-control-sm w-100" id="cloudflare_secret_key" name="cloudflare_secret_key" placeholder="Secret Key" autocomplete="off" value="<?php echo isset($contact_form_settings['cloudflare_turnstile']['secret_key']) ? esc_html($contact_form_settings['cloudflare_turnstile']['secret_key']) : '' ?>">
                    </td>
                    <td><button type="button" class="btn btn-primary btn-sm" id="btn-save-cloudflare-secret-key" onclick="syrusAI_save_cloudflare_secret_key()"><?php esc_html_e('Save') ?></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>