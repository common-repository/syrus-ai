<?php
    global $syrusAIPlugin;
    $writesonic_key = $syrusAIPlugin->get_writesonic_token() ? true : false;
?>

<p class="descrizione-meta-box" style="font-weight: 300;">
    <?php if(!$writesonic_key) { ?>
        <?php esc_html_e("You haven't set any token to use Writesonic, go to settings and add one.",'syrus') ?>
    <?php } else { ?>
        <?php esc_html_e("Use the field below to specify the link of the article that Writesonic will rewrite, the content of the response will be automatically inserted into the post.",'syrus') ?>
    <?php } ?>
</p>
<input type="text" maxlength="1000" name="link_generatore_contenuto_ws" placeholder="<?php esc_html_e('Insert an article link','syrus') ?>" style="width:100%; margin-bottom:15px">
<button type="button" class="button button-primary button-large" style="width:100%" id="btn-generazione-contenuto-ws" onclick="generaContenutoWs()"><?php esc_html_e("Generate article",'syrus') ?></button>
