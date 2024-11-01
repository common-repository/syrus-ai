window.addEventListener('message', function(event) {
    let json = JSON.parse(atob(event.data));

    if(json.connected) {
        if(json.companies && json.companies.length > 0) {
            ajax_callback_set_connected_social(json.social, json.companies);
        }
        else {
            ajax_callback_set_connected_social(json.social);
        }
    }

    event.source.close();
});


jQuery(document).ready(() => {
    let params = new URLSearchParams(window.location.search);
    let social = params.get('social');
    let code = params.get('code');

    if(!social || !code)
        return false;

    ajax_callback(social,code);

    jQuery("#facebook_connect").click()
});


//LinkedIn
function syrus_AI_connectLinkedIn() {
    jQuery('#linkedin_connect').attr('disabled', true);
    jQuery('#linkedin_connect').html(
        '<span class="dashicons dashicons-update spin"></span>Connecting'
    );
    let social =  "linkedin";

    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-get-authorization-url-syrus-api",
            social
        },
        success: (ajax_res) => {
            let url = (ajax_res.url);

            let popupWidth = 1000;
            let popupHeight = 800;
            
            var win = window.open(url, "_blank", "width=" + popupWidth + ", height=" + popupHeight);
    
            var timer = setInterval(function() { 
                if(win.closed) {
                    clearInterval(timer);
                    jQuery('#linkedin_connect').attr('disabled', false);
                    jQuery('#linkedin_connect').html(
                        'Connect'
                    );
                }
            }, 1000);
        }
    });
}

function syrus_AI_disconnectLinkedIn() {
    jQuery('#btn-disconnect-linkedin').html(
        '<span class="dashicons dashicons-update spin"></span>Disconnecting'
    );

    jQuery('#btn-disconnect-linkedin').attr('disabled',true);


    Swal.fire({
        title: 'Ask',
        text: "Do you want disconnect LinkedIn?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, disconnect!',
        showClass: {
            popup: 'animate__animated animate__bounceInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__bounceOutUp'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            jQuery.ajax({
                url: args_social_settings.ajax_url,
                type: "POST",
                dataType: "json",
                data: {
                    action: "syrus-ai-revoke-linkedin-token",
                },
                success: (ajax_res) => {
                    if(ajax_res.status === 'success'){
                        swal_disconnection(true);
                        console.log('successo');
                    }else{
                        swal_disconnection(false);
                    }
                }
            });
        } else {
            jQuery('#btn-disconnect-linkedin').attr('disabled',false);
            jQuery('#btn-disconnect-linkedin').html(
                'Disconnect'
            );
        }
    })
}

function syrus_AI_shareLinkedIn() {
    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-test-share-linkedin",
        },
        success: (ajax_res) => {
            if(ajax_res.error == "Can't share post") {
                Swal.fire({
                    title: 'Error',
                    text: "You need logged to share post",
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'I understand',
                    heightAuto: false,
                    allowOutsideClick: false,
                    showClass: {
                        popup: 'animate__animated animate__headShake'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__bounceOutUp'
                    },
                })  
            } else {
                Swal.fire({
                    title: 'Success',
                    text: "Your post has shared correctly",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'I understand',
                    heightAuto: false,
                    allowOutsideClick: false,
                    showClass: {
                        popup: 'animate__animated animate__bounceInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__bounceOutUp'
                    }
                });
            }
        }
    });
}


//Facebook
function syrus_AI_connectFacebook() {
    jQuery('#facebook_connect').attr('disabled', true);
    jQuery('#facebook_connect').html(
        '<span class="dashicons dashicons-update spin"></span>Connecting'
    );


    let social =  "facebook";

    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-get-authorization-url-syrus-api",
            social
        },
        success: (ajax_res) => {
            let url = (ajax_res.url);

            let popupWidth = 1000;
            let popupHeight = 800;
            
            var win = window.open(url, "_blank", "width=" + popupWidth + ", height=" + popupHeight);
    
            var timer = setInterval(function() { 
                if(win.closed) {
                    clearInterval(timer);
                    jQuery('#facebook_connect').attr('disabled', false);
                    jQuery('#facebook_connect').html(
                        'Connect'
                    );
                }
            }, 1000);
        }
    });
}

function syrus_AI_disconnectFacebook() {
    jQuery('#btn-disconnect-fb').html(
        '<span class="dashicons dashicons-update spin"></span>Connecting'
    );

    jQuery('#btn-disconnect-fb').attr('disabled',true);

    Swal.fire({
        title: 'Ask',
        text: "Do you want disconnect Facebook?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            jQuery.ajax({
                url: args_social_settings.ajax_url,
                type: "POST",
                dataType: "json",
                data: {
                    action: "syrus-ai-revoke-fb-token",
                },
                success: (ajax_res) => {
                    if(ajax_res.status === 'success'){
                        swal_disconnection(true);
                    }else{
                        swal_disconnection(false);
                    }      
                }
            });
        } else {
            jQuery('#btn-disconnect-fb').attr('disabled',false);
            jQuery('#btn-disconnect-fb').html(
                'Disconnect'
            );
        }
    })
}

function syrus_AI_shareFacebook() {
    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-test-share-facebook",
        },
        success: (ajax_res) => {
            if(ajax_res.error == "Can't share post") {
                Swal.fire({
                    title: 'Error',
                    text: "You need logged to share post",
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'I understand',
                    heightAuto: false,
                    allowOutsideClick: false
                })  
            } else {
                Swal.fire({
                    title: 'Success',
                    text: "Your post has shared correctly",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'I understand',
                    heightAuto: false,
                    allowOutsideClick: false
                })
            }
        }
    });
}


//Twitter
function syrus_AI_connectTwitter() {
    jQuery('#twitter_connect').attr('disabled', true);
    jQuery('#twitter_connect').html(
        '<span class="dashicons dashicons-update spin"></span>Connecting'
    );

    let social =  "twitter";

    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-get-authorization-url-syrus-api",
            social
        },
        success: (ajax_res) => {
            let url = (ajax_res.url);

            let popupWidth = 1000;
            let popupHeight = 800;
            
            var win = window.open(url, "_blank", "width=" + popupWidth + ", height=" + popupHeight);
            var timer = setInterval(function() { 
                if(win.closed) {
                    clearInterval(timer);
                    jQuery('#twitter_connect').attr('disabled',false);
                    jQuery('#twitter_connect').html(
                        'Connect'
                    );            }
            }, 1000);
        }
    });
}

function syrus_AI_disconnectTwitter() {
    jQuery('#btn-disconnect-twitter').html(
        '<span class="dashicons dashicons-update spin"></span>Connecting'
    );
    jQuery('#btn-disconnect-twitter').attr('disabled',true);

    Swal.fire({
        title: 'Ask',
        text: "Do you want disconnect Twitter?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, disconnect!',
        showClass: {
            popup: 'animate__animated animate__bounceInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__bounceOutUp'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            jQuery.ajax({
                url: args_social_settings.ajax_url,
                type: "POST",
                dataType: "json",
                data: {
                    action: "syrus-ai-revoke-twitter-token",
                },
                success: (ajax_res) => {
                    if(ajax_res.status === 'success'){
                        swal_disconnection(true);
                    }else{
                        swal_disconnection(false);
                    }
                }
            });
        }else {
            jQuery('#btn-disconnect-twitter').attr('disabled',false);
            jQuery('#btn-disconnect-twitter').html(
                'Connect'
            );
        }
    })
}

function syrus_AI_shareTwitter() {
    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-test-share-twitter",
        },
        success: (ajax_res) => {
            if(ajax_res.error == "Can't share post") {
                Swal.fire({
                    title: 'Error',
                    text: "You need logged to share post",
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'I understand',
                    heightAuto: false,
                    allowOutsideClick: false,
                    showClass: {
                        popup: 'animate__animated animate__headShake'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__bounceOutUp'
                    },
                })  
            } else {
                Swal.fire({
                    title: 'Success',
                    text: "Your post has shared correctly",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'I understand',
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
}


//Instagram
function syrus_AI_connectInstagram() {
    jQuery('#instagram_connect').attr('disabled', true);
    jQuery('#instagram_connect').html(
        '<span class="dashicons dashicons-update spin"></span>Connecting'
    );
    let social =  "instagram";

    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-get-authorization-url-syrus-api",
            social
        },
        success: (ajax_res) => {
            let url = (ajax_res.url);

            let popupWidth = 1000;
            let popupHeight = 800;
            
            var win = window.open(url, "_blank", "width=" + popupWidth + ", height=" + popupHeight);
    
            var timer = setInterval(function() { 
                if(win.closed) {
                    clearInterval(timer);
                    jQuery('#instagram_connect').attr('disabled', false);
                    jQuery('#instagram_connect').html(
                        'Connect'
                    );
                }
            }, 1000);
        }
    });
}

function syrus_AI_disconnectInstagram() {
    jQuery('#btn-disconnect-ig').html(
        '<span class="dashicons dashicons-update spin"></span>Disconnecting'
    );

    jQuery('#btn-disconnect-ig').attr('disabled',true);

    Swal.fire({
        title: 'Ask',
        text: "Do you want disconnect Instagram?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            jQuery.ajax({
                url: args_social_settings.ajax_url,
                type: "POST",
                dataType: "json",
                data: {
                    action: "syrus-ai-revoke-ig-token",
                },
                success: (ajax_res) => {
                    if(ajax_res.status === 'success'){
                        swal_disconnection(true);
                    }else{
                        swal_disconnection(false);
                    }      
                }
            });
        } else {
            jQuery('#btn-disconnect-ig').attr('disabled',false);
            jQuery('#btn-disconnect-ig').html('Disconnect');
        }
    });
}

function syrus_AI_shareInstagram() {
    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-test-share-ig",
        },
        success: (ajax_res) => {
            console.log(ajax_res);
        }
    });
}


//Utils
function syrusAI_test_async() {
    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-test-async",
        },
        success: (ajax_res) => {
          console.log(ajax_res);
        }
    });
}

function saveWherePost(social) {
    let where = jQuery('input[name=where_ld]:checked').val();

    jQuery('.ld-company').css('color', 'grey');
    jQuery('.ld-company input').prop('disabled', true);
    
    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-where-post",
            where, social
        },
        success: (ajax_res) => {
            if(ajax_res.status == 'success') {
                jQuery('.ld-company').css('color', 'black');
                jQuery('.ld-company input').prop('disabled', false);
            }
        }
    });
}

function swal_disconnection(status){
    if(status){
        var title = 'Success';
        var text = 'Successful disconnection!';
        var icon = 'success';
        var timer = 2000;
    }else{
        var title = 'Error';
        var text = 'Error during the disconnection!';
        var icon = 'error';
        var timer = 5000;
    }

    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCloseButton: true,
        timer: timer,
        timerProgressBar: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        showClass: {
            popup: 'animate__animated animate__bounceInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__bounceOutUp'
        },
        didOpen: () => {
          Swal.showLoading()
          const b = Swal.getHtmlContainer().querySelector('b')
          timerInterval = setInterval(() => {
            b.textContent = Swal.getTimerLeft()
          }, 100)
        },
        willClose: () => {
          clearInterval(timerInterval);
        }
    }).then((result) => {
        if ((result.dismiss === Swal.DismissReason.timer) || (result.dismiss === Swal.DismissReason.close) ||(result.dismiss === Swal.DismissReason.esc) || (result.dismiss === Swal.DismissReason.backdrop)) {
            setTimeout(() => {  location.reload(); }, 1000);
        }
    });    
}

function ajax_callback_set_connected_social(social, companies = null) {
    jQuery.ajax({
        url: args_social_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-social-connected",
            social, companies
        },
        success: (ajax_res) => {
            if(ajax_res['status'] === 'success'){
                Swal.fire({
                    title: 'Success',
                    text: 'Successful connection!',
                    icon: 'success',
                    showCloseButton: true,
                    timer: 2000,
                    timerProgressBar: true,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    showClass: {
                        popup: 'animate__animated animate__bounceInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__bounceOutUp'
                    },
                    didOpen: () => {
                      Swal.showLoading()
                      const b = Swal.getHtmlContainer().querySelector('b')
                      timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                      }, 100)
                    },
                    willClose: () => {
                      clearInterval(timerInterval);
                    }
                }).then((result) => {
                    if ((result.dismiss === Swal.DismissReason.timer) || (result.dismiss === Swal.DismissReason.close) ||(result.dismiss === Swal.DismissReason.esc) || (result.dismiss === Swal.DismissReason.backdrop)) {
                        setTimeout(() => {  location.reload(); }, 1000);
                    }
                });    
            }else{
                Swal.fire({
                    title: 'Error!',
                    text: 'There was a problem during the connection!',
                    icon: 'error',
                    showCloseButton: true,
                    timer: 5000,
                    timerProgressBar: true,
                    timerProgressBar: true,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    showClass: {
                        popup: 'animate__animated animate__headShake'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__bounceOutUp'
                    },
                    didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                    }, 100)
                    },
                    willClose: () => {
                    clearInterval(timerInterval);
                    }
                }).then((result) => {
                    if ((result.dismiss === Swal.DismissReason.timer) || (result.dismiss === Swal.DismissReason.close) ||(result.dismiss === Swal.DismissReason.esc) || (result.dismiss === Swal.DismissReason.backdrop)) {
                        setTimeout(() => {  location.reload(); }, 1000);
                    }
                });
            }
        }
    });
}