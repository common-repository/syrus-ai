jQuery(document).ready(() => {
    console.log("News Making - Script");

    jQuery('.selectCustomDomainNewsMaking').select2({
        tags: true,
        placeholder: "Select or enter one or more domains ES: syrus.today"
    });
});

function save_newsmaking_settings() {
    let domain = jQuery('#select-domain-news-making').val();
    let language = jQuery('#select-language-news-making').val();
    let keyword = jQuery('#select-keyword-news-making').val();
    let date = jQuery('#select-date-news-making').val();
    let category = jQuery('#select-category-news-making').val();
    let country = jQuery('#select-country-news-making').val();
    
    jQuery.ajax({
        url: args_news_making.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-newsmaking-settings",
            domain: domain,
            language: language,
            keyword: keyword,
            date: date,
            category: category,
            country: country
        }, 
        success: (ajax_res) => {
            Swal.fire({
                title: 'Success',
                text: 'Settings saved',
                icon: 'success',
                showCloseButton: true,
                timer: 2000,
                timerProgressBar: true,
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
                if (result.dismiss === Swal.DismissReason.timer) {
                    setTimeout(() => {  location.reload(); }, 1000);
                }
            });
        }
    });

}

function changeCronStatus() {
    let mode = jQuery('#mode-cron-newsmaking').val();
    let email = jQuery('#email-cron-newsmaking').val();

    if(email === "") {
        Swal.fire({
            title: 'Error',
            text: "You need add email",
            icon: 'error',
            showCloseButton: true,
            timer: 2000,
            timerProgressBar: true,
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
            if (result.dismiss === Swal.DismissReason.timer) {
                setTimeout(() => {  location.reload(); }, 1000);
            }
        });

        return true;
    }

    let cron_hour = jQuery('#cron-hour').val();

    jQuery.ajax({
        url: args_advanced_settings.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-save-cron-hour",
            cron_hour
        }
    });

    jQuery.ajax({
        url: args_news_making.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-update-newsmaking-cron-status",
            mode: mode,
            email: email
        }, 
        success: (ajax_res) => {
            let status = ajax_res.status;

            if(status == "1") {
                var text = "Cron enabled successfully!";
            } else {
                var text = "Cron disabled successfully!";
            }
            
            Swal.fire({
                title: 'Success',
                text: text,
                icon: 'success',
                showCloseButton: true,
                timer: 2000,
                timerProgressBar: true,
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
                if (result.dismiss === Swal.DismissReason.timer) {
                    setTimeout(() => {  location.reload(); }, 1000);
                }
            });
        }
    });
}

function doNewsMaking() {
    let domain = jQuery('#select-domain-news-making').val();
    let language = jQuery('#select-language-news-making').val();
    let keyword = jQuery('#select-keyword-news-making').val();

    let date = jQuery('#select-date-news-making').val();

    if(domain === "" || language === "" || keyword === "" || date === "") {
        Swal.fire({
            title: 'Error',
            text: "You must fill in all the fields in the settings",
            icon: 'error',
            showCloseButton: true,
            timer: 2000,
            timerProgressBar: true,
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
            if (result.dismiss === Swal.DismissReason.timer) {
                setTimeout(() => {  location.reload(); }, 1000);
            }
        });

        return true;
    }

    const currentdate = new Date();

    let currentDay = String(currentdate.getDate()).padStart(2, '0');

    let currentMonth = String(currentdate.getMonth()+1).padStart(2,"0");

    let currentYear = currentdate.getFullYear();

    if(date == "today") {
        date = `${currentYear}-${currentMonth}-${currentDay}`;
    } else if(date == "yesterday") {
        date = `${currentYear}-${currentMonth}-${currentDay - 1}`;
    } else if(date == "last_week") {
        date = `${currentYear}-${currentMonth}-${currentDay - 7}`;
    }

    jQuery(".button-refresh-news-making").prop('disabled', true);
    jQuery(".button-refresh-news-making").attr('disabled', 'disabled');

    jQuery.ajax({
        url: args_news_making.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-news-making",
            type: "first",
            domain: domain,
            language: language,
            q: keyword,
            from: date
        }, 
        success: (ajax_res) => {
            if(ajax_res.status == "apiKeyInvalid") {
                Swal.fire({
                    title: 'Error',
                    text: 'Invalid api key!',
                    icon: 'error',
                    showCloseButton: true,
                    timer: 2000,
                    timerProgressBar: true,
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
                    if (result.dismiss === Swal.DismissReason.timer) {
                        setTimeout(() => {  location.reload(); }, 1000);
                    }
                });
            } else {
                location.reload();
            }
        }
    });
}

function doNewsMaking2() {
    let category = jQuery('#select-category-news-making').val();
    let country = jQuery('#select-country-news-making').val();

    jQuery(".button-refresh-news-making").prop('disabled', true);
    jQuery(".button-refresh-news-making").attr('disabled', 'disabled');

    jQuery.ajax({
        url: args_news_making.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
            action: "syrus-ai-news-making2",
            category: category,
            country: country
        }, 
        success: (ajax_res) => {
            if(ajax_res.status == "apiKeyInvalid") {
                Swal.fire({
                    title: 'Error',
                    text: 'Invalid api key!',
                    icon: 'error',
                    showCloseButton: true,
                    timer: 2000,
                    timerProgressBar: true,
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
                    if (result.dismiss === Swal.DismissReason.timer) {
                        setTimeout(() => {  location.reload(); }, 1000);
                    }
                });
            } else {
                location.reload();
            }
        }
    });
}