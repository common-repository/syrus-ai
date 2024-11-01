<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    if (!isset($_REQUEST['syrus-nonce']) ||  ! wp_verify_nonce( sanitize_text_field(wp_unslash($_REQUEST['syrus-nonce'])), 'contact-forms' ) ) {
      echo "Nonce failure!";
    }

    global $syrusCPT_ContactForm;

    $forms = $syrusCPT_ContactForm->getForms();

    $page = filter_input(
        INPUT_GET,
        'page',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ['options' => 'esc_html']
    );

?>

<h3><?php esc_html_e('Contact forms','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can manage all your contact forms.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row">
    <div class="col-12">
        <div class="wrap">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Title', 'syrus-ai'); ?></th>
                        <th><?php esc_html_e('Shortcode', 'syrus-ai'); ?></th>
                        <th><?php esc_html_e('Date', 'syrus-ai'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($forms->have_posts()) : ?>
                        <?php while($forms->have_posts()) : $forms->the_post(); ?>
                            <tr>
                                <td><a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=contact-forms-edit' . '&form=' . get_the_ID())) ?>"><?php the_title() ?></a></td>
                                <td>[syrus_contact_form id=<?php the_ID() ?>]</td>
                                <td><?php the_date() ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
