<form id="contact_form_<?php echo esc_html($id) ?>" action="<?php echo esc_url(admin_url('admin-post.php?action=syrus-contact-form-submit')) ?>" method="POST">
    <input type="hidden" name="contact_form_id" value="<?php echo esc_html($id) ?>">
    <input type="hidden" name="honeypot">
    <input type="hidden" name="referer1" value="<?php echo esc_url(wp_get_referer()) ?>">
    <input type="hidden" name="referer2" value="<?php the_permalink() ?>">
    
    <?php if(!$isPreview && $captcha == 'cloudflare_turnstile' && isset($contact_form_settings['cloudflare_turnstile']['site_key'])) : ?>
        <input type="hidden" name="cf-turnstile-response">
    <?php endif; ?>
    
    <input type="hidden" name="syrus-nonce" value="<?php echo esc_html(wp_create_nonce( 'syrus-contact-form-submit' )); ?>">

    <div class="row">
        <?php if(in_array('first_name', $fields)) { ?>
            <div class="col-<?php echo !in_array('last_name', $fields) ? '12' : '6' ?> mb-3 col-first_name">
                <label for="first_name"><?php esc_html_e('First name', 'syrus') ?><?php echo (in_array('first_name', $required_fields) ? '<span class="ms-2" style="color:red">*</span>' : '') ?></label>
                <input class="form-control" type="text" name="first_name" id="first_name" maxlength="512" <?php echo (!$isPreview && in_array('first_name', $required_fields) ? 'required' : '') ?>>
            </div>
        <?php } ?>

        <?php if(in_array('last_name', $fields)) { ?>
            <div class="col-<?php echo !in_array('first_name', $fields) ? '12' : '6' ?> mb-3 col-last_name">
                <label for="last_name"><?php esc_html_e('Last name', 'syrus') ?><?php echo (in_array('last_name', $required_fields) ? '<span class="ms-2" style="color:red">*</span>' : '') ?></label>
                <input class="form-control" type="text" name="last_name" id="last_name" maxlength="512" <?php echo (!$isPreview && in_array('last_name', $required_fields) ? 'required' : '') ?>>
            </div>
        <?php } ?>

        <?php if(in_array('number', $fields)) { ?>
            <div class="col-12 mb-3 col-number">
                <label for="number"><?php esc_html_e('Number', 'syrus') ?><?php echo (in_array('number', $required_fields) ? '<span class="ms-2" style="color:red">*</span>' : '') ?></label>
                <input class="form-control" type="tel" name="number" id="number" maxlength="512" <?php echo (!$isPreview && in_array('number', $required_fields) ? 'required' : '') ?>>
            </div>
        <?php } ?>

        <?php if(in_array('email', $fields)) { ?>
            <div class="col-12 mb-3 col-email">
                <label for="email"><?php esc_html_e('Email', 'syrus') ?><?php echo (in_array('email', $required_fields) ? '<span class="ms-2" style="color:red">*</span>' : '') ?></label>
                <input class="form-control" type="email" name="email" id="email" maxlength="512" <?php echo (!$isPreview && in_array('email', $required_fields) ? 'required' : '') ?>>
            </div>
        <?php } ?>

        <?php if(in_array('message', $fields)) { ?>
            <div class="col-12 mb-3 col-message">
                <label for="message"><?php esc_html_e('Message', 'syrus') ?><?php echo (in_array('message', $required_fields) ? '<span class="ms-2" style="color:red">*</span>' : '') ?></label>
                <textarea class="form-control" rows="3" name="message" id="message" maxlength="512" <?php echo (!$isPreview && in_array('message', $required_fields) ? 'required' : '') ?>></textarea>
            </div>
        <?php } ?>

        <?php if(in_array('privacy', $fields)) { ?>
            <div class="col-12 mb-3 col-privacy">
                <input type="checkbox" name="" <?php echo (!$isPreview && in_array('privacy', $required_fields) ? 'required' : '') ?>>
                <label for="privacy"><?php esc_html_e('I agree to ', 'syrus') ?> <a href="<?php echo esc_html($url_privacy_policy) ?>" target="_blank"><?php esc_html_e('Privacy Policy','syrus-ai') ?></a> <?php echo (in_array('privacy', $required_fields) ? '<span class="ms-2" style="color:red">*</span>' : '') ?></label>
            </div>
        <?php } ?>

        <?php if(!$isPreview) { ?>
            <div class="col-12 mb-3">
                <button class="btn btn-primary btn-submit" <?php echo $captcha != 'no_protection' ? 'disabled' : '' ?>><?php esc_html_e("Submit", 'syrus') ?></button>
            </div>
        <?php } ?>

        <?php if(!$isPreview && $captcha == 'cloudflare_turnstile' && isset($contact_form_settings['cloudflare_turnstile']['site_key'])) : ?>
            <div class="col-12">
                <div class="cf-turnstile" data-theme="light" data-sitekey="<?php echo esc_html($contact_form_settings['cloudflare_turnstile']['site_key']) ?>" data-response-field-name="cf-turnstile-response" data-cdata="<?php echo esc_html($id) ?>" data-callback="SyrusAI_cloudflareturnstile_callback_<?php echo esc_html($id) ?>" data-expired-callback="location.reload()"></div>
            </div>
        <?php endif; ?>
    </div>
</form>

<?php if(!$isPreview && $captcha == 'cloudflare_turnstile') : ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>

    <script>
        function SyrusAI_cloudflareturnstile_callback_<?php echo esc_html($id) ?>(token) {
            jQuery('#contact_form_<?php echo esc_html($id) ?> input[name=cf-turnstile-response]').val(token);
            jQuery('#contact_form_<?php echo esc_html($id) ?> button.btn-submit').attr('disabled', false);
        }
    </script>
<?php endif; ?>
