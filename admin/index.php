<?php

if(!defined('ABSPATH'))
    exit;

global $syrusAIPlugin;
global $syrusAITemplate;
global $pagenow;

$tab = filter_input(
    INPUT_GET,
    'tab',
    FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ['options' => 'esc_html']
);

$tab = $tab ?: "home";

$page = filter_input(
    INPUT_GET,
    'page',
    FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ['options' => 'esc_html']
);

$showSidebar = false;

if(in_array($tab,[]))
    $showSidebar = true;

?>

<section class="section-header-admin">
    <div class="container-fluid">
        <div class="row">
            <div class="col-1 text-end">
                <img src="<?php echo esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/logo.png') ?>" alt="" width="75" height="75" loading="lazy">
            </div>
            <div class="col-auto d-flex flex-column justify-content-center">
                <h2 class="mb-0">SYRUS AI</h1>
                <p class="text-muted mb-0"><i><?php esc_html_e("Plugin version",'syrus-ai') ?> <?php echo esc_html($syrusAIPlugin->settings['plugin_version']) ?></i></p>
            </div>
            <div class="col-auto ms-auto d-flex flex-column justify-content-center">
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=support')) ?>">
                    <button type="button" class="btn btn-sm btn-support"><?php esc_html_e("Support",'syrus-ai') ?></button>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section-topnav-menu">
    <nav>
        <li class="<?php echo $tab == 'social-settings' ? 'active' : '' ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=social-settings')) ?>" class=""><span class="dashicons dashicons-share me-2"></span><?php esc_html_e("Social Network",'syrus-ai') ?></a></li>

        <li tabindex="0" class="<?php echo $tab == 'news-making' ? 'active' : '' ?>">
            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=ai-newsmaking')) ?>" class=""><span class="dashicons dashicons-welcome-widgets-menus me-2"></span><?php esc_html_e("Newsmaking",'syrus-ai') ?></a><span class="syrus-ai-coming-soon"><?php esc_html_e("Coming Soon",'syrus-ai') ?></span>
            <!-- <div class="submenu" style="display:none">
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=category-newsmaking')) ?>"><span class="dashicons dashicons-admin-site-alt me-2"></span><?php esc_html_e("By category and country",'syrus-ai') ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=keywords-newsmaking')) ?>"><span class="dashicons dashicons-admin-network me-2"></span><?php esc_html_e("By keywords and domains",'syrus-ai') ?></a>
            </div> -->
        </li>

        <li tabindex="0" class="with-submenu <?php echo in_array($tab,['ai-translate','history-ai-translate','settings-ai-translate']) ? 'active' : '' ?>">
            <a href="#" class=""><span class="dashicons dashicons-translation me-2"></span><?php esc_html_e("AI Translate",'syrus-ai') ?></a>
            <div class="submenu" style="display:none">
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=ai-translate')) ?>" class="<?php echo $tab == 'ai-translate' ? 'active' : '' ?>"><span class="dashicons dashicons-translation me-2"></span><?php esc_html_e("Translate",'syrus-ai') ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=history-ai-translate')) ?>" class="<?php echo $tab == 'history-ai-translate' ? 'active' : '' ?>"><span class="dashicons dashicons-backup me-2"></span><?php esc_html_e("Translation History",'syrus-ai') ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=settings-ai-translate')) ?>" class="<?php echo $tab == 'settings-ai-translate' ? 'active' : '' ?>"><span class="dashicons dashicons-admin-settings me-2"></span><?php esc_html_e("Settings",'syrus-ai') ?></a>
            </div>
        </li>

        <li tabindex="0" class="with-submenu <?php echo $tab == 'test' ? 'active' : '' ?>">
            <a href="#" class=""><?php esc_html_e("AI Generator",'syrus-ai') ?></a>
            <span class="syrus-ai-coming-soon"><?php esc_html_e("Coming Soon",'syrus-ai') ?></span>
            <div class="submenu" style="display:none">
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=coming-soon')) ?>"><?php esc_html_e("Image Generator",'syrus-ai') ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=coming-soon')) ?>"><?php esc_html_e("Content Generator",'syrus-ai') ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=coming-soon')) ?>"><?php esc_html_e("Video Generator",'syrus-ai') ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=coming-soon')) ?>"><?php esc_html_e("Slides Generator",'syrus-ai') ?></a>
            </div>
        </li>

        <li class="<?php echo $tab == 'test' ? 'active' : '' ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=coming-soon')) ?>" class=""><?php esc_html_e("Link Matrix",'syrus-ai') ?></a><span class="syrus-ai-coming-soon"><?php esc_html_e("Coming Soon",'syrus-ai') ?></span></li>


        <li class="<?php echo $tab == 'logs' ? 'active' : '' ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=logs')) ?>" class=""><span class="dashicons dashicons-list-view me-2"></span><?php esc_html_e("Logs",'syrus-ai') ?></a></li>

        <li tabindex="0" class="with-submenu <?php echo in_array($tab,['contact-forms', 'contact-forms-new', 'contact-forms-edit']) ? 'active' : '' ?>">
            <a href="#" class=""><span class="dashicons dashicons-align-left me-2"></span><?php esc_html_e("Contact Forms",'syrus-ai') ?></a>
            <div class="submenu" style="display:none">
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=contact-forms&syrus-nonce='.wp_create_nonce( 'contact-forms' ))) ?>"><span class="dashicons dashicons-editor-table me-2"></span><?php esc_html_e("All forms",'syrus-ai') ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=contact-forms-new')) ?>"><span class="dashicons dashicons-welcome-add-page me-2"></span><?php esc_html_e("New form",'syrus-ai') ?></a>
            </div>
        </li>

        <li class="<?php echo $tab == 'advanced-settings' ? 'active' : '' ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=advanced-settings')) ?>" class=""><span class="dashicons dashicons-admin-settings me-2"></span><?php esc_html_e("Settings",'syrus-ai') ?></a></li>

        <li class="<?php echo $tab == 'import-export' ? 'active' : '' ?>"><a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=import-export')) ?>" class=""><?php esc_html_e("Import/Export",'syrus-ai') ?></a></li>
    </nav>
</section>

<div class="container-fluid">
    <div class="row">
        <div class="col-1<?php echo $showSidebar ? '0' : '2' ?> col-tab">
            <?php

                $contact_forms_parent = strpos($tab, 'contact-forms') !== false ? explode('contact-forms-',$tab) : null;
                $tab_path = 'admin/tabs/';

                if($contact_forms_parent) {
                    $subtab = isset($contact_forms_parent[1]) ? $contact_forms_parent[1] : 'index';
                    $tab = str_replace('-' . $subtab, '', $tab) . '/' . $subtab;
                }

                $syrusAITemplate->include($tab_path . $tab);

            ?>
        </div>
    </div>
</div>
