function save_general_settings(){

    let category = [];
    let tags = [];
    let social = [];

    jQuery('.syrus-ai-category-checklist-container li input[type=checkbox]:checked').each((i,el) => {
        category.push(jQuery(el).val());
    });

    jQuery('.syrus-ai-tags-checklist-container li input[type=checkbox]:checked').each((i,el) => {
        tags.push(jQuery(el).val());
    });

    jQuery('.syrus-ai-social-checklist-container label input[type=checkbox]:checked').each((i,el) => {
        social.push(jQuery(el).val());
    });

    jQuery.ajax({
        url: args_general_settings.ajax_url,
        method: 'POST',
        dataType: "json",
        data: {
            action: "syrus-ai-save-general-settings",
            category, tags, social
        }, 
        success: (ajax_res) => {
            location.reload();

        }
    });
}

function control_sharing() {
    jQuery.ajax({
        url: args_general_settings.ajax_url,
        method: 'POST',
        dataType: "json",
        data: {
            action: "syrus-ai-control-sharing"
        }, 
        success: (ajax_res) => {
            location.reload();
        }
    });
}