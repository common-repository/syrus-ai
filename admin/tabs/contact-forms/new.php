<?php 
    global $syrusAIPlugin;
    $contact_form_settings = $syrusAIPlugin->get_contact_form_settings();
?>
<h3><?php esc_html_e("New contact form",'syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Create a new contact form",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">


<form id="new-form" action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="POST">

    <input type="hidden" name="action" value="syrus_cpt_add_new_form">
    <input type="hidden" name="syrus-nonce" value="<?php echo esc_html(wp_create_nonce( 'syrus_cpt_add_new_form' )); ?>">


    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="row">
                    <div class="col-12 mb-4">
                        <label for="title" class="form-label"><h5><?php esc_html_e("Form title",'syrus-ai') ?></h5></label>
                        <input type="text" value="" class="form-control" id="title" name="title" placeholder="<?php esc_html_e("My awesome form",'syrus-ai') ?>" autocomplete="off" required>
                    </div>

                    <div class="col-12 mb-4">
                        <label><h5><?php esc_html_e('Select form fields', 'syrus-ai') ?></h5></label>
                        <table class="table">
                            <thead>
                                <th></th>
                                <th><?php esc_html_e('Field', 'syrus-ai') ?></th>
                                <th><?php esc_html_e('Required', 'syrus-ai') ?></th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" name="fields[]" value="first_name" autocomplete="off"></td>
                                    <td><?php esc_html_e('First name', 'syrus-ai') ?></td>
                                    <td>
                                        <select name="required_fields[first_name]" autocomplete="off" data-field="first_name">
                                            <option value="no">No</option>
                                            <option value="yes"><?php esc_html_e('Yes', 'syrus-ai') ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="fields[]" value="last_name" autocomplete="off"></td>
                                    <td><?php esc_html_e('Last name', 'syrus-ai') ?></td>
                                    <td>
                                        <select name="required_fields[last_name]" autocomplete="off" data-field="last_name">
                                            <option value="no">No</option>
                                            <option value="yes"><?php esc_html_e('Yes', 'syrus-ai') ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="fields[]" value="number" autocomplete="off"></td>
                                    <td><?php esc_html_e('Number', 'syrus-ai') ?></td>
                                    <td>
                                        <select name="required_fields[number]" autocomplete="off" data-field="number">
                                            <option value="no">No</option>
                                            <option value="yes"><?php esc_html_e('Yes', 'syrus-ai') ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="fields[]" value="email" autocomplete="off"></td>
                                    <td><?php esc_html_e('Email', 'syrus-ai') ?></td>
                                    <td>
                                        <select name="required_fields[email]" autocomplete="off" data-field="email">
                                            <option value="no">No</option>
                                            <option value="yes"><?php esc_html_e('Yes', 'syrus-ai') ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="fields[message]" value="message" autocomplete="off"></td>
                                    <td><?php esc_html_e('Message', 'syrus-ai') ?></td>
                                    <td>
                                        <select name="required_fields[message]" autocomplete="off" data-field="message">
                                            <option value="no">No</option>
                                            <option value="yes"><?php esc_html_e('Yes', 'syrus-ai') ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="fields[privacy]" value="privacy" autocomplete="off"></td>
                                    <td><?php esc_html_e('Privacy policy', 'syrus-ai') ?></td>
                                    <td>
                                        <select name="required_fields[privacy]" autocomplete="off" data-field="privacy">
                                            <option value="no">No</option>
                                            <option value="yes"><?php esc_html_e('Yes', 'syrus-ai') ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mb-4">
                        <label for="url_privacy_policy"><h5><?php esc_html_e('Url Privacy Policy', 'syrus-ai') ?></h5></label>
                        <input type="text" class="form-control" value="" name="url_privacy_policy" id="url_privacy_policy" placeholder="https://<?php echo esc_html(wp_parse_url(get_site_url(), PHP_URL_HOST)) ?>/privacy-policy" autocomplete="off">
                    </div>

                    <div class="col-12 mb-4">
                        <label for="recipient"><h5><?php esc_html_e('Recipient', 'syrus-ai') ?></h5></label>
                        <input type="text" class="form-control" value="" name="recipient" id="recipient" placeholder="myawesome@email.com or email1@email.com,email2@email.com" autocomplete="off">
                    </div>

                    <div class="col-12 mb-4">
                        <label for="subject"><h5><?php esc_html_e('Subject', 'syrus-ai') ?></h5></label>
                        <input type="text" class="form-control" value="" name="subject" id="subject" placeholder="[<?php echo esc_html(wp_parse_url(get_site_url(), PHP_URL_HOST)) ?>] <?php esc_html_e('New contact from your site', 'syrus-ai') ?>" autocomplete="off">
                    </div>

                    <div class="col-12 mb-4">
                        <label for=""><h5><?php esc_html_e('Select thank you page', 'syrus-ai') ?></h5></label>
                        <?php wp_dropdown_pages([
                            'class'     => 'w-100' ,
                        ]) ?>
                        <p class="text-muted"><?php esc_html_e('For better compatibility make sure the page has the main container tag with the #main-container id attribute', 'syrus-ai') ?></p>
                    </div>

                    <?php if(isset($contact_form_settings['cloudflare_turnstile']['site_key']) || isset($contact_form_settings['cloudflare_turnstile']['secret_key'])): ?>
                        <div class="col-12 mb-4">
                            <label for=""><h5><?php esc_html_e('Captcha protection', 'syrus-ai') ?></h5></label>

                            <div class="mb-1">
                                <input type="radio" id="no_protection" name="captcha" value="no_protection" checked />
                                <label for="no_protection"><?php esc_html_e('Nothing', 'syrus-ai') ?></label>
                            </div>

                            <div class="mb-1">
                                <input type="radio" id="cloudflare_turnstile" name="captcha" value="cloudflare_turnstile" />
                                <label for="cloudflare_turnstile">Cloudflare Turnstile</label>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <div class="col-1"></div>
            <div class="col-5">
                <h3><?php esc_html_e('Form preview', 'syrus-ai') ?></h3>
                <div id="form-preview-container">

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button class="btn btn-sm btn-primary"><?php esc_html_e('Save', 'syrus-ai') ?></button>
                <button form="form-remove-form" class="btn btn-sm btn-danger"><?php esc_html_e('Remove', 'syrus-ai') ?></button>
            </div>
        </div>
    </div>

</form>
