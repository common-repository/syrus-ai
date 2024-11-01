jQuery(document).ready(() => {
    console.log("News Making - Script");

    jQuery('.selectCustomDomainNewsMaking').select2({
        tags: true,
        placeholder: "Select or enter one or more domains ES: syrus.today"
    });
});

function startKeywordNewsMaking() {
    let domain = jQuery('#select-domain-news-making').val();
    let language = jQuery('#select-language-news-making').val();
    let keyword = jQuery('#select-keyword-news-making').val();
    let date = jQuery('#select-date-news-making').val();
    let $saveButton = jQuery('.btn-start-news-making');
    let loadingText = $saveButton.attr('data-loading-text');
    let defaultText = $saveButton.text();

    $saveButton.html(loadingText + '<img width="30" src="' + argsKeywordsNewsMaking.spinnerUrl + '" />');
    $saveButton.addClass('loading-btn');
    $saveButton.attr('disabled', 'disabled');

    if(domain == "" || language == "" || keyword == "" || date == "") {
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
            domain, language, keyword, date
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

function clearKeywordNews() {
    let category = "keyword";

    let $saveButton = jQuery('.btn-clear-table-news');
    let loadingText = $saveButton.attr('data-loading-text');
    
    $saveButton.html(loadingText + '<img width="30" src="' + argsKeywordsNewsMaking.spinnerUrl + '" />');
    $saveButton.addClass('loading-btn');
    $saveButton.attr('disabled', 'disabled');

    jQuery.ajax({
        url: argsKeywordsNewsMaking.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-clearNews",
            category
        },
        success: (ajax_res) => {
            if(ajax_res.status == "success") {
                Swal.fire({
                    template: "#clearSuccess",
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