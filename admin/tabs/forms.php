<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    if (!isset($_REQUEST['syrus-nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['syrus-nonce'])), 'forms' ) ) {
      echo "Nonce failure!";
    }

    global $syrus, $syrusCPT_ContactForm;
    
    $action = filter_input(
        INPUT_GET,
        'action',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ['options' => 'esc_html']
    );
    

    $tab = filter_input(
        INPUT_GET,
        'tab',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ['options' => 'esc_html']
    );

?>

<?php if(!$action) { ?>
    <h2><?php esc_html_e("Your Forms",'syrus-ai') ?></h2>
    <p class="m-0"><?php esc_html_e("Here you can manage your website contact forms",'syrus-ai') ?></p>
    <hr class="mt-1 mb-4">

    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=' . $tab  . '&action=new')) ?>">
                    <button class="btn btn-sm btn-primary"><?php esc_html_e('Add new', 'syrus-ai') ?></button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php $syrusCPT_ContactForm->displayTable() ?>
            </div>
        </div>
    </div>
<?php } else { ?>
    <?php $syrusCPT_ContactForm->manageAction($action);  ?>
<?php } ?>
