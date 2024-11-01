jQuery(document).ready(() => {

    let current_wp_page = (new URL(window.location.href)).pathname.split('/').pop();

    initNavSubmenus();

    initSyrusAIAjaxSetup();

    if(current_wp_page == 'post-new.php' || current_wp_page == 'post.php')
        initSyrusAIMediaTab();

    if(jQuery('#syrus-ai-generate-post-thumbnail').length > 0)
        jQuery('#syrus-ai-generate-post-thumbnail').on('click', (event) => {
            event.preventDefault();
            SyrusAIGeneratePostThumbnail();
        });
});

function initNavSubmenus() {
    jQuery('.section-topnav-menu nav li.with-submenu').on('click',(e) => {
        let $el = jQuery(e.currentTarget);
        let $realTarget = jQuery(e.target);
        let $submenu = jQuery($el.find('.submenu'));

        if($realTarget.parent().get(0) == $submenu.get(0))
            return true;

        e.preventDefault();
        
        jQuery('.section-topnav-menu nav li.with-submenu .submenu').hide();

        jQuery('.section-topnav-menu nav li.with-submenu').each((index,element) => {
            if(element != $el.get(0))
                jQuery(element).removeClass('open');
        });

        if($el.hasClass('open')) {
            $el.removeClass('open');
            $submenu.hide();
            $el.blur();
        } else {
            $el.addClass('open');
            $submenu.show();
        }



    });
}

function initSyrusAIAjaxSetup() {
    jQuery.ajaxSetup({
        beforeSend: function(xhr, settings) {
            if(settings.data) {
                let params = new URLSearchParams(settings.data);
                let _action = params.get('action');
                
                if(_action) {
                    let actionPrefix = _action.slice(0, 9)

                    if(actionPrefix === "syrus-ai-" && _action != "syrus-ai-genera-nonce" && !params.get('_ajaxNonce')) {

                        jQuery.ajax({
                            url: argsAdminAI.ajaxUrl,
                            type: "POST",
                            dataType: 'json',
                            data: {
                                _ajaxNonce: argsAdminAI.nonce,
                                action: 'syrus-ai-genera-nonce',
                                actionForNonce: _action
                            },
                            success: (ajax_res) => {
                            if(ajax_res.status != "success")
                                return false;

                            settings.data += "&_ajaxNonce=" + ajax_res.nonce
                            jQuery.ajax(settings);
                            return false;
                            }
                        });

                        return false;
                    }
                }
            } 
        }
    });
}

function generaContenutoWs() {
    jQuery('#btn-generazione-contenuto-ws, input[name=link_generatore_contenuto_ws]').attr('disabled',true);
    jQuery('#btn-generazione-contenuto-ws').text("Generazione in corso...");

    jQuery.ajax({
        url: argsAdminAI.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-generate-article-ws",
            link: jQuery('input[name=link_generatore_contenuto_ws]').val(),
        },
        success: (ajax_res) => {
            if(ajax_res.status == "success") {
                if(parent.tinyMCE.activeEditor !== null) {
                    parent.tinyMCE.activeEditor.setContent(ajax_res.result.content);
                }
                else {
                    parent.jQuery('#content').val(ajax_res.result.content);
                }

                parent.jQuery("#title").val(ajax_res.result.title);
				
				if(jQuery('#title-prompt-text').length)
					jQuery('#title-prompt-text').addClass('screen-reader-text');
				
                parent.jQuery("#TB_closeWindowButton").click();
            }

            jQuery('#btn-generazione-contenuto-ws, input[name=link_generatore_contenuto_ws]').attr('disabled',false);
            jQuery('#btn-generazione-contenuto-ws').text("Genera contenuto");
        }
    });
}


function initSyrusAIMediaTab() {

    // const Parent = wp.media.view.Router;

    // wp.media.view.Router = Parent.extend({
    //     addNav() {
    //         const $button = jQuery(
    //             '<button type="button" role="tab" class="media-menu-item" id="menu-item-syrus-ai-image-generator">Syrus AI Image Generator</button>'
    //         );

    //         this.$el.append($button); // append
    //     },

    //     initialize() {
    //         Parent.prototype.initialize.apply(this, arguments);
    //         this.addNav(); // add buttons
    //         return this; // return
    //     },
    // });

    // jQuery('#menu-item-syrus-ai-image-generator').on('click', (event) => {
    //     jQuery('.media-menu-item').removeClass('active');

    // });

    // if (frame.length) {
    //     $(".close-ii-modal").on("click", function (e) {
    //         e.preventDefault();
    //         frame.removeClass("active");
    //     });
    // }

	// if (wp.media) {
	// 	const frame = $("#instant_images_modal");
	// 	if (frame.length) {
	// 		instant_images.setEditor(frame);
	// 	}
	// }
}

function SyrusAIGeneratePostThumbnail() {
    let post_title = jQuery('input[name=post_title]').val();
    let post_content = parent.tinyMCE.activeEditor !== null ? parent.tinyMCE.activeEditor.getContent() : parent.jQuery('#content').val();
    let $a = jQuery('#syrus-ai-generate-post-thumbnail');

    if($a.hasClass('generating'))
        return false;

    if(!post_title.trim())
        return alert(jQuery('#syrus-ai-missing-post-title-text'));

    if(!post_content.trim())
        return alert(jQuery('#syrus-ai-missing-post-content-text'));

    $a.addClass('generating');
    $a.text($a.attr('data-generating-text'));

    jQuery.ajax({
        url: argsAdminAI.ajaxUrl,
        type: "POST",
        dataType: "json",
        data: {
            action: 'syrus-ai-generate-post-thumbnail',
            post_title,
            post_content
        },
        success: (ajax_res) => {
            $a.removeClass('generating');
            $a.text($a.attr('data-original-text'));

            if(wp.media.frame !== undefined)
            jQuery('#set-post-thumbnail').click();
            // wp.media.frame.open();
        }
    });
    
}