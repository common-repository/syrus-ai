jQuery(document).ready(() => {
    let anchor = window.location.hash.substring(1);

    if(anchor && jQuery('#tr-' + anchor).length > 0)
        jQuery('#tr-' + anchor).addClass('highlight');

})

function syrusAI_save_jet_lag() {
    let jet_lag = jQuery('.selectJetLag').val();

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-jet-lag",
            jet_lag,
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function syrusAI_save_deepl_token() {
    let deepl_token = jQuery('input[name=deepl_token]').val();

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-deepl-token",
            deepl_token
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function syrusAI_save_chatgpt_token() {
    let chatgpt_token = jQuery('input[name=chatgpt_token]').val();

    jQuery('input[name=chatgpt_token]').attr('readonly',true);

    jQuery('#btn-save-chatgpt-token').html(
        '<span class="dashicons dashicons-update spin"></span>Saving'
    );

    jQuery('#btn-save-chatgpt-token').attr('disabled',true);

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-chatgpt-token",
            chatgpt_token
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function syrusAI_save_chatgpt_organization_id() {
    let chatgpt_organization_id = jQuery('input[name=chatgpt_organization_id]').val();

    jQuery('input[name=chatgpt_organization_id]').attr('readonly',true);

    jQuery('#btn-save-chatgpt-organization-id').html(
        '<span class="dashicons dashicons-update spin"></span>Saving'
    );

    jQuery('#btn-save-chatgpt-organization-id').attr('disabled',true);

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-chatgpt-organization-id",
            chatgpt_organization_id
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function syrusAI_save_writesonic_token() {
    let writesonic_token = jQuery('input[name=writesonic_token]').val();

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-writesonic-token",
            writesonic_token
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function syrusAI_test_chatgpt_token() {
    jQuery('#btn-test-chatgpt-token').html(
        '<span class="dashicons dashicons-update spin"></span>Testing'
    );

    jQuery('#btn-test-chatgpt-token').attr('disabled',true);

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-test-chatgpt-token",
        },
        success: (ajax_res) => {
            if(ajax_res.status == "success") {
                alert(ajax_res.result);
            }
    
            jQuery('#btn-test-chatgpt-token').html(
                'Test'
            );
        
            jQuery('#btn-test-chatgpt-token').attr('disabled',false);
        }
    });

}

function syrusAI_save_newsapi_token() {
    let newsapi_token = jQuery('input[name=newsapi_token]').val();

    jQuery('input[name=newsapi_token]').attr('readonly',true);

    jQuery('#btn-save-newsapi-token').html(
        '<span class="dashicons dashicons-update spin"></span>Saving'
    );

    jQuery('#btn-save-newsapi-token').attr('disabled',true);

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-newsapi-token",
            newsapi_token
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function syrusAI_save_prompt() {
    let prompt = jQuery('textarea[name=default_main]').val();

    jQuery('textarea[name=default_main]').attr('readonly',true);

    jQuery('#btn-save-prompt').html(
        '<span class="dashicons dashicons-update spin"></span>Saving'
    );

    jQuery('#btn-save-prompt').attr('disabled',true);

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-prompt",
            prompt
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function syrusAI_save_domains_automation_translation() {
    let domains = jQuery('.selectCustomDomainNewsMaking').val();

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-domains-automation-translation",
            domains: domains
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function restoreThisRow(el) {
    console.log("here");
    jQuery(el).removeClass('highlight');
}

function syrusAI_save_cloudflare_site_key() {
    let cloudflare_site_key = jQuery('input[name=cloudflare_site_key]').val();

    jQuery('input[name=cloudflare_site_key]').attr('readonly',true);

    jQuery('#btn-save-cloudflare-site-key').html(
        '<span class="dashicons dashicons-update spin"></span>Saving'
    );

    jQuery('#btn-save-cloudflare-site-key').attr('disabled',true);

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-cloudflare-site-key",
            cloudflare_site_key
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function syrusAI_save_cloudflare_secret_key() {
    let cloudflare_secret_key = jQuery('input[name=cloudflare_secret_key]').val();

    jQuery('input[name=cloudflare_secret_key]').attr('readonly',true);

    jQuery('#btn-save-cloudflare-secret-key').html(
        '<span class="dashicons dashicons-update spin"></span>Saving'
    );

    jQuery('#btn-save-cloudflare-secret-key').attr('disabled',true);

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-cloudflare-secret-key",
            cloudflare_secret_key
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

