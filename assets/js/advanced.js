console.log("Advanced");

function importConfiguration() {
    Swal.fire({
        template: "#swal-template-import"
    }).then((swal_res) => {
        if(swal_res.isConfirmed) {
            return startImportConfiguration();
        } else {
            return false;
        }
    });
}

function startImportConfiguration() {
    let configurationToken = jQuery('input[name=import_configuration_token]').val();

    if(configurationToken == "")
        return false;

    jQuery('#btn-import-configuration').html(
        '<span class="dashicons dashicons-update spin"></span>Loading'
    );

    jQuery("#btn-import-configuration").prop('disabled', true);
    jQuery('input[name=import_configuration_token]').prop('disabled',true);

    jQuery.ajax({
        type: "POST",
        url: args_advanced.ajax_url,
        dataType: "json",
        data: {
            action: "syrus-ai-import-configuration",
            configurationToken
        },
        success: (ajax_res) => {
            let social_connessi = ajax_res.social_connessi;

            for(prop in social_connessi) {
                let $social = social_connessi[prop];
                let social_name = $social.social.toLowerCase();
                let social_companies = $social.hasOwnProperty('companies') && $social.companies.length > 0 ? $social.companies : null;
    
                let res = connectSocial(social_name, social_companies);
                console.log(social_name +  " Connesso!");
            }
    
            setTimeout(() => {
                location.reload();
            }, 4000);
        }
    });
}

function connectSocial(social, companies = null) {
    jQuery.ajax({
        url: args_advanced.ajax_url,
        type: "POST",
        dataType: "json",
        async: false,
        data: {
            action: "syrus-ai-social-connected",
            social,
            companies
        },
        success: (ajax_res) => {
            return ajax_res;
        }
    });
}