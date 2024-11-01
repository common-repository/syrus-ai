function getNewArticles() {
    let domain = jQuery('input[name=domain]').val();

    let $takingButton = jQuery('#btn-take-articles');
    let loadingText = $takingButton.attr('data-loading-text');
    let defaultText = $takingButton.text();

    $takingButton.html(loadingText + '<img width="25" src="' + argsAITranslate.spinnerUrl + '" />');
    $takingButton.addClass('loading-btn');
    $takingButton.attr('disabled', 'disabled');

    jQuery.ajax({
        url: argsAITranslate.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-automatic-translation",
            domain
        },
        success: (ajax_res) => {
            location.reload();
        }
    });
}

function automatic() {
    let domain = jQuery('input[name=redirect]').val();

    jQuery('.button-automatic').html(
        '<span class="dashicons dashicons-update spin"></span>Loading'
    );

    jQuery(".button-automatic").prop('disabled', true);
    jQuery(".button-automatic").attr('disabled', 'disabled');

    jQuery.ajax({
        url: args_automatic_translate.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-automatic-translation",
            domain: domain
        },
        success: (ajax_res) => {
            console.log(ajax_res);
            if(ajax_res.result == "success") {
                jQuery('.button-automatic').html('Get Articles');
                jQuery(".button-automatic").prop('disabled', false);
                jQuery("#translate-button").prop('disabled', false);
                location.reload();
            } else {
                location.reload();
            }
        }
    });
}

function delete_article(id_wp) {
    jQuery.ajax({
        url: argsAITranslate.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-delete-article-translate",
            id_wp: id_wp
        },
        success: (ajax_res) => {
            Swal.fire({
                template: "#success_delete",
                iconHtml: argsAITranslate.logoUrl,
                timer: 3000,
                customClass: { 
                    icon: "syrus-ai-icon",
                    popup: "syrus-ai-popup"
                }
            }).then((result) => {
                location.reload();
            });
        }
    });
}

function do_translate(id_wp, post_id = null) {
    let ai;

    Swal.fire({
        title: 'Which AI do you want to use?',
        icon: 'question',
        confirmButtonColor: '#10a37f',
        confirmButtonText: 'OpenAI',
        showCancelButton: true,
        cancelButtonColor: '#0f2b46',
        cancelButtonText: 'DeepL',
        heightAuto: false,
        allowOutsideClick: false,
        showClass: {
            popup: 'animate__animated animate__bounceInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__bounceOutUp'
        }
    }).then((swal_res) => {
        if(swal_res.isConfirmed) {
            ai = 'openai';
        } else {
            ai = 'deepl';
        }

        Swal.fire({
            template: "#loading",
            iconHtml: argsAITranslate.logoUrl,
            customClass: { 
                icon: "syrus-ai-icon",
                popup: "syrus-ai-popup"
            }
        })
    
        jQuery.ajax({
            url: argsAITranslate.ajaxUrl,
            type: "POST",
            dataType: "json",
            data: {
                action: "syrus-ai-translate-publish-articles",
                id_wp: id_wp,
                post_id: post_id,
                ai: ai
            },
            success: (ajax_res) => {
                if(ajax_res.result == "success") {
                    Swal.fire({
                        template: "#success",
                        iconHtml: argsAITranslate.logoUrl,
                        timer: 3000,
                        customClass: { 
                            icon: "syrus-ai-icon",
                            popup: "syrus-ai-popup"
                        }
                    }).then((result) => {
                        location.reload();
                    });
                } else if(ajax_res.result == "exists") {
                    Swal.fire({
                        title: 'Error',
                        html: "This article is just translated",
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'I understood',
                        heightAuto: false,
                        allowOutsideClick: false,
                        showClass: {
                            popup: 'animate__animated animate__bounceInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__bounceOutUp'
                        }
                    })
                }
                else {
                    console.log(ajax_res.errore)
                    Swal.fire({
                        title: 'Error',
                        html: "Error: try again.</a>",
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'I understood',
                        heightAuto: false,
                        allowOutsideClick: false,
                        showClass: {
                            popup: 'animate__animated animate__bounceInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__bounceOutUp'
                        }
                    })
                }
            }
        });
    });
}