function getNewsMaking() {
    let country = jQuery('#select-country-news-making').val();
    let keywords = jQuery('#input-keywords-news-making').val();

    let $saveButton = jQuery('.btn-start-news-making');
    let loadingText = $saveButton.attr('data-loading-text');
    let defaultText = $saveButton.text();

    $saveButton.html(loadingText + '<img width="30" src="' + argsNewsMaking.spinnerUrl + '" />');
    $saveButton.addClass('loading-btn');
    $saveButton.attr('disabled', 'disabled');
    
    jQuery.ajax({
        url: argsNewsMaking.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-news-making",
            country,
            keywords
        },
        success: (ajax_res) => {

            if(ajax_res.status == "apiKeyInvalid") {

                $saveButton.html(defaultText);
                $saveButton.removeAttr('disabled');
                $saveButton.removeClass('loading-btn');
                
                Swal.fire({
                    template: "#swalInvalidAPIKey",
                    iconHtml: argsNewsMaking.logoUrl,
                    customClass: { 
                        icon: "syrus-ai-icon",
                        popup: "syrus-ai-popup error"
                    }
                }).then((result) => {
    
                });
            } else {
    
                Swal.fire({
                    template: "#swalSuccess",
                    iconHtml: argsNewsMaking.logoUrl,
                    timer: 3000,
                    customClass: { 
                        icon: "syrus-ai-icon",
                        popup: "syrus-ai-popup"
                    }
                }).then((result) => {
                    location.reload();
                });
    
            }
        }
    });
}

function clearCategoryNews() {
    let category = "category";

    let $saveButton = jQuery('.btn-clear-table-news');
    let loadingText = $saveButton.attr('data-loading-text');

    $saveButton.html(loadingText + '<img width="30" src="' + argsNewsMaking.spinnerUrl + '" />');
    $saveButton.addClass('loading-btn');
    $saveButton.attr('disabled', 'disabled');

    jQuery.ajax({
        url: argsNewsMaking.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-clearNews",
            category
        },
        success: () => {
            if(ajax_res.status == "success") {
                Swal.fire({
                    template: "#clearSuccess",
                    iconHtml: argsNewsMaking.logoUrl,
                    timer: 3000,
                    customClass: { 
                        icon: "syrus-ai-icon",
                        popup: "syrus-ai-popup"
                    }
                }).then((result) => {
                    location.reload();
                });
            }
        }
    });
}

function startAINewsMaking() {
    let language = jQuery('#select-language-news-making').val();
    let keywords = jQuery('#select-keywords-news-making').val();
    let country = jQuery('#select-country-news-making').val();

    let $saveButton = jQuery('.btn-start-news-making');
    let loadingText = $saveButton.attr('data-loading-text');
    let defaultText = $saveButton.text();

    $saveButton.html(loadingText + '<img width="30" src="' + argsKeywordsNewsMaking.spinnerUrl + '" />');
    $saveButton.addClass('loading-btn');
    $saveButton.attr('disabled', 'disabled');

    if(keywords == "" || language == "" || country == "") {
        Swal.fire({
            template: "#swalInvalidData",
            iconHtml: argsKeywordsNewsMaking.logoUrl,
            timer: 3000,
            customClass: { 
                icon: "syrus-ai-icon",
                popup: "syrus-ai-popup error"
            }
        }).then((result) => {
            location.reload();
        });

        return true;
    }
    
    jQuery.ajax({
        url: argsKeywordsNewsMaking.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-news-making",
            language, keywords, country
        },
        success: (ajax_res) => {
            if(ajax_res.status == "apiKeyInvalid") {

                $saveButton.html(defaultText);
                $saveButton.removeAttr('disabled');
                $saveButton.removeClass('loading-btn');
                
                Swal.fire({
                    template: "#swalInvalidAPIKey",
                    iconHtml: argsKeywordsNewsMaking.logoUrl,
                    customClass: { 
                        icon: "syrus-ai-icon",
                        popup: "syrus-ai-popup error"
                    }
                }).then((result) => {
    
                });
            } else {
    
                Swal.fire({
                    template: "#swalSuccess",
                    iconHtml: argsKeywordsNewsMaking.logoUrl,
                    timer: 3000,
                    customClass: { 
                        icon: "syrus-ai-icon",
                        popup: "syrus-ai-popup"
                    }
                }).then((result) => {
                    location.reload();
                });
    
            }
        }
    });
}