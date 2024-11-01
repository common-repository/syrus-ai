const REGEX_DOMAIN = /^(?!https?:\/\/)([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;

function addDomainTranslate() {
    let $inputDomain = jQuery('#input-domain');
    let domain = $inputDomain.val();
    $inputDomain.removeClass('is-invalid');
    $inputDomain.attr('disabled', 'disabled');


    let $saveButton = jQuery('.btn-add-domain');
    let loadingText = $saveButton.attr('data-loading-text');
    let defaultText = $saveButton.text();

    $saveButton.html(loadingText + '<img width="25" src="' + argsSettingsAITranslate.spinnerUrl + '" />');
    $saveButton.addClass('loading-btn');
    $saveButton.attr('disabled', 'disabled');

    if(!REGEX_DOMAIN.test(domain)) {
        $inputDomain.addClass('is-invalid');
        $saveButton.html(defaultText);
        $saveButton.removeAttr('disabled');
        $inputDomain.removeAttr('disabled');
        $saveButton.removeClass('loading-btn');
        return false;
    }

    jQuery.ajax({
        url: argsSettingsAITranslate.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-check-domain-translate-and-add",
            domain
        },
        success: (ajax_res) => {
            Swal.fire({
                text: ajax_res.message, 
                toast: true,
                showConfirmButton: false,
                showCloseButton: true,
                position: "bottom",
                iconHtml: argsSettingsAITranslate.logoUrl,
                timer: 2500,
                customClass: { 
                    icon: "syrus-ai-icon",
                    popup: "syrus-ai-popup"
                }
            }).then((swal_res) => {
                location.reload();
            });

            $saveButton.html(defaultText);
            $saveButton.removeAttr('disabled');
            $inputDomain.removeAttr('disabled');
            $saveButton.removeClass('loading-btn');
        },
        error: (ajax_err) => {
            Swal.fire({
                text: ajax_err.message, 
                toast: true,
                showConfirmButton: false,
                showCloseButton: true,
                position: "bottom",
                iconHtml: argsSettingsAITranslate.logoUrl,
                customClass: { 
                    icon: "syrus-ai-icon",
                    popup: "syrus-ai-popup error"
                }
            });

            $saveButton.html(defaultText);
            $saveButton.removeAttr('disabled');
            $inputDomain.removeAttr('disabled');
            $saveButton.removeClass('loading-btn');
        }
    });

    checkDomainTranslateAndAdd(domain).then((res) => {
        if(res.status == "success") {
            Swal.fire({
                text: res.message, 
                toast: true,
                showConfirmButton: false,
                showCloseButton: true,
                position: "bottom",
                iconHtml: argsSettingsAITranslate.logoUrl,
                timer: 2500,
                customClass: { 
                    icon: "syrus-ai-icon",
                    popup: "syrus-ai-popup"
                }
            }).then((swal_res) => {
                location.reload();
            });
        } else {
            Swal.fire({
                text: res.message, 
                toast: true,
                showConfirmButton: false,
                showCloseButton: true,
                position: "bottom",
                iconHtml: argsSettingsAITranslate.logoUrl,
                customClass: { 
                    icon: "syrus-ai-icon",
                    popup: "syrus-ai-popup error"
                }
            });
            $inputDomain.addClass('is-invalid');
        }

        $saveButton.html(defaultText);
        $saveButton.removeAttr('disabled');
        $inputDomain.removeAttr('disabled');
        $saveButton.removeClass('loading-btn');
    });

}

async function checkDomainTranslateAndAdd(domain) {
    return await jQuery.ajax({
        url: argsSettingsAITranslate.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-check-domain-translate-and-add",
            domain
        },
        success: (ajax_res) => {
            return ajax_res;
        },
        error: (err_aj) => {
            console.log(err_aj)
        }
    });
}

function removeDomainTranslate(domain) {
    Swal.fire({
        template: "#swalRemoveDomain"
    }).then((swal_res) => {
        if(swal_res.isConfirmed)
            removeDomainTranslateAjax(domain);
    });
}

function removeDomainTranslateAjax(domain) {
    
    jQuery.ajax({
        url: argsSettingsAITranslate.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-remove-domain-translate",
            domain
        },
        success: (ajax_res) => {
            location.reload();
        }
    });

}