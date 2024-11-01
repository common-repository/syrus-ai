/**
 *
 * SCPTCF = SyrusCPT Contact Form
 *
 *
 */

jQuery(document).ready(() => {

    if(jQuery('#main-content').length)
        SCPTCF_insert_submitted_info_into_DOM();
    
});

function SCPTCF_insert_submitted_info_into_DOM() {

    let $container = jQuery('<div id="#syrus-ai-contact-form-submitted-info" class="w-auto mx-2 mx-md-5 rounded-2 border border-secodary p-2"><h5>' + CPT_SESSION.labelList + '</h5><ul class="list-unstyled"></ul></div>');
    let $list = $container.find('ul');

    for (prop of Object.entries(CPT_SESSION)) {

        let label = prop[1][0];
        let val = prop[1][1];

        if(prop[0] == 'labelList')
            continue;

        $list.append(
            jQuery('<li>' + label + ': ' + val + '</li>')
        );
    }
      
    jQuery('#main-content').append($container);
}