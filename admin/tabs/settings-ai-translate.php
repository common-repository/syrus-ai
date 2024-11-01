<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    $currentAITranslationDomains = get_option('syrus-ai-translations-domains') ? unserialize(get_option('syrus-ai-translations-domains')) : [];

    $page = filter_input(
        INPUT_GET,
        'page',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ['options' => 'esc_html']
    );

?>

<h3><?php esc_html_e('Settings','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can manage the settings for AI translations.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">1) <?php esc_html_e("Websites list",'syrus-ai') ?></h4>
        <p class="m-0"><?php esc_html_e("Enter all the WordPress sites from which you intend to take articles for translations",'syrus-ai') ?></p>
        <p class="m-0"><?php esc_html_e('Write domain without http or https, for example: google.com (not https://google.com), amazon.com (not https://amazon.com)','syrus-ai') ?></p>
        <p class="mb-3"><u><?php esc_html_e("Make sure the website you enter is a WordPress website",'syrus-ai') ?></u></p>
    </div>
    <div class="col-12 mb-4 d-flex gap-2 justify-content-start align-items-center">
        <input type="text" class="form-control form-control-sm" id="input-domain" placeholder="<?php esc_html_e("Insert domain here...",'syrus-ai') ?>">
        <button type="button" class="btn btn-primary btn-sm btn-add-domain" data-loading-text="<?php esc_html_e("Adding",'syrus-ai') ?>" onclick="addDomainTranslate()"><?php esc_html_e("Add",'syrus-ai') ?></button>
    </div>

    <div class="col-6 d-flex flex-column justify-content-start align-items-start gap-2 domain-list">
        <?php foreach($currentAITranslationDomains as $d) { ?>
            <div class="domain">
                <?php echo esc_html($d['domain']) ?>
                <span class="dashicons dashicons-dismiss" role="button" onclick="removeDomainTranslate('<?php echo esc_html($d['domain']) ?>')"></span>
            </div>
        <?php } ?>
    </div>

</div>

<div class="row mb-5">
    <div class="col-12 mb-3">
        <h4 class="m-0">2) <?php esc_html_e("Translate",'syrus-ai') ?></h4>
        <p class="m-0"><?php esc_html_e("Click the button below to go to the translate section",'syrus-ai') ?></p>
    </div>
    <div class="col-12">
        <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=ai-translate')) ?>">
            <button type="button" class="btn btn-primary btn-sm"><?php esc_html_e("Translate section",'syrus-ai') ?></button>
        </a>
    </div>

</div>


<template id="swalRemoveDomain">
    <swal-title><?php esc_html_e("Are you sure?",'syrus-ai') ?></swal-title>
    <swal-icon type="warning"></swal-icon>
    <swal-html>
        <?php esc_html_e("Click 'CONTINUE' button to remove the selected domain",'syrus-ai') ?>
    </swal-html>
    <swal-param name="showCloseButton" value="true" />
    <swal-button type="confirm"><?php esc_html_e("CONTINUE",'syrus-ai') ?></swal-button>
    <swal-button type="cancel"><?php esc_html_e("CANCEL",'syrus-ai') ?></swal-button>
</template>
