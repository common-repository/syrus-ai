/**
 *
 * SCPTCF = SyrusCPT Contact Form
 *
 *
 */


jQuery(document).ready(() => {

    let $newForm = jQuery('#new-form');
    let $removeForm = jQuery('#form-remove-form');

    if($newForm.length > 0)
        SCPTCF_initEventsOnNewForm();

    if($removeForm.length > 0) {
        $removeForm.on('submit', (event) => {
            let remove = confirm("Are you sure to remove this form?");

            if (!remove) {
                event.preventDefault(); // Previene l'invio del form se l'utente clicca "Annulla"
            }
        });
    }

});

function SCPTCF_initEventsOnNewForm() {

    if(jQuery('#new-form input[name=form_id]').length > 0)
        SCPTCF_renderFormPreview();


    jQuery('#new-form input:not([type=hidden]), #new-form select').each((i, el) => {

        jQuery(el).on('change', (ev) => {
            SCPTCF_renderFormPreview();
        });

    });

    jQuery('#new-form').on('submit', () => {
        jQuery('#form-preview-container').html("");
        return true;
    });

}

function SCPTCF_renderFormPreview() {
    let fields = [];
    let required_fields = [];

    jQuery('input[name^=fields]:checked').each((i, chk) => {
        fields.push(jQuery(chk).val());
    });

    jQuery('select[name^=required_fields]').each((i, select) => {

        if(jQuery(select).val() == "yes")
            required_fields.push(jQuery(select).attr('data-field'));


    });

    jQuery.ajax({
        url: args_cpt.ajax_url,
        method: "POST",
        dataType: 'json',
        data: {
            action: 'syrus-cpt-contact-form-refresh-preview',
            fields, required_fields,
            "syrus-nonce": ajax_object.ajax_nonce
        },
        success: (ajax_res) => {
            jQuery('#form-preview-container').html(ajax_res.html);
        }
    });

}
