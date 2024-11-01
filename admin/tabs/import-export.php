<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="row">
    <div class="col-12">
        <h1><?php esc_html_e('Import - Export','syrus-ai') ?></h1>
    </div>

    <div class="col-12 mb-3">
        <h5>Configuration token</h5>
        <p><?php esc_html_e("Use the token below for importing your configuration in another website",'syrus-ai') ?></p>
        <p><b>Token: </b><br><span style="color:#0073aa;"><?php echo esc_html(base64_encode(wp_json_encode([ 't' => get_option("syrus_ai_bearer_token", true), 'f' => $_SERVER['SERVER_NAME']]))); ?></p></span>
    </div>

    <div class="col-12">
        <h5><?php esc_html_e("Import configuration",'syrus-ai') ?></h5>
        <p><?php esc_html_e("Put a Syrus AI configuration token to import the configuration from another website",'syrus-ai') ?></p>
    </div>

    <div class="col-10">
        <input type="text" class="form-control form-control-sm" name="import_configuration_token">
    </div>
    <div class="col-2">
        <button type="button" class="btn btn-primary btn-sm" id="btn-import-configuration" onclick="importConfiguration()">Import</button>
    </div>
</div>

<template id="swal-template-import">
    <swal-icon type="warning" color="red"></swal-icon>
    <swal-title><?php esc_html_e("Attention",'syrus-ai') ?></swal-title>
    <swal-html><?php esc_html_e("Pay attention and continue only if you are sure of what you are doing!",'syrus-ai') ?></swal-html>
    <swal-button type="confirm"><?php esc_html_e("Continue",'syrus-ai') ?></swal-button>
    <swal-button type="deny"><?php esc_html_e("Cancel",'syrus-ai') ?></swal-button>
</template>
