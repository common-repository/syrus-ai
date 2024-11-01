<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $connected_social = get_option('syrus-ai-connected-social') ? unserialize(get_option('syrus-ai-connected-social')) : [];

    $facebook_connected = isset($connected_social['facebook']) && $connected_social['facebook']['connected'] ? true : false;
    $linkedin_connected = isset($connected_social['linkedin']) && $connected_social['linkedin']['connected'] ? true : false;
    $twitter_connected = isset($connected_social['twitter']) && $connected_social['twitter']['connected'] ? true : false;
    $instagram_connected = isset($connected_social['instagram']) && $connected_social['instagram']['connected'] ? true : false;

    $linkedin_companies = isset($connected_social['linkedin']) && isset($connected_social['linkedin']['companies']) ? $connected_social['linkedin']['companies'] : null;

    global $syrusAIPlugin;

    $general_settings = $syrusAIPlugin->get_general_settings();

    $share = get_option("syrus-ai-enable-social", true);

?>

<h3><?php esc_html_e('Social Network','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can manage your social network connections and sharing settings.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row mb-5">
    <div class="col-auto mb-2">
        <h4 class="m-0">1) <?php esc_html_e("Social Connections",'syrus-ai') ?></h4>
        <p class="m-0"><?php esc_html_e("Click the 'Connect' button to connect your profile to the plugin",'syrus-ai') ?></p>
    </div>
    <div class="col-12 d-flex align-items-center justify-content-start mb-2">
        <?php if($share == "1") {?>
            <button type="button" class="btn btn-danger btn-sm" onclick="control_sharing()">
            <?php esc_html_e("Disable sharing",'syrus-ai'); ?>
            </button>
        <?php } else {?>
            <button type="button" class="btn btn-success btn-sm" onclick="control_sharing()">
            <?php esc_html_e("Enable sharing",'syrus-ai'); ?>
            </button>
        <?php }?>
    </div>

    <div class="col-12">
    <div class="table-responsive">
            <table class="table">
                <thead>
                    <th style="width:5%"></th>
                    <th style="width:20%">Social</th>
                    <th class="text-center" style="width:5%"><?php esc_html_e("Status",'syrus-ai'); ?></th>
                    <th class="text-center"><?php esc_html_e("Expiration date",'syrus-ai') ?></th>
                    <th class="text-center"><?php esc_html_e("Actions",'syrus-ai'); ?></th>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">
                            <img src="<?php echo esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/fb-logo.png') ?>" width="25px" height="25px" alt="">
                        </td>
                        <td>Facebook</td>
                        <td class="text-center">
                            <?php if($facebook_connected) { ?>
                                <span class="dashicons dashicons-yes-alt text-success"></span>
                            <?php } else { ?>
                                <span class="dashicons dashicons-dismiss text-danger"></span>
                            <?php } ?>
                        </td>
                        <td class="text-center">N/D</td>
                        <td class="text-center">
                            <?php if($facebook_connected) { ?>
                                <button type="button" class="btn btn-danger btn-sm" id="btn-disconnect-fb" onclick="syrus_AI_disconnectFacebook()"><?php esc_html_e('Disconnect','syrus-ai') ?></button>
                            <?php } else { ?>
                                    <button type="button" class="btn btn-primary btn-sm" id="facebook_connect" onclick="syrus_AI_connectFacebook()"><?php esc_html_e('Connect','syrus-ai') ?></button>
                                </a>
                            <?php } ?>
                            <button type="button" class="btn btn-success btn-sm" onclick="syrus_AI_shareFacebook()">TEST</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <img src="<?php echo esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/twitter-logo.png') ?>" width="25px" height="25px" alt="">
                        </td>
                        <td>Twitter</td>
                        <td class="text-center">
                            <?php if($twitter_connected) { ?>
                                <span class="dashicons dashicons-yes-alt text-success"></span>
                            <?php } else { ?>
                                <span class="dashicons dashicons-dismiss text-danger"></span>
                            <?php } ?>
                        </td>

                        <td class="text-center">
                            <?php if($twitter_connected) { ?>
                                <p class="m-0 fs-5">∞</p>
                            <?php } ?>
                        </td>

                        <td class="text-center">
                            <?php if($twitter_connected) { ?>
                                <button type="button" class="btn btn-danger btn-sm" onclick="syrus_AI_disconnectTwitter()"><?php esc_html_e('Disconnect','syrus-ai') ?></button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-primary btn-sm" id="twitter_connect" onclick="syrus_AI_connectTwitter()"><?php esc_html_e('Connect','syrus-ai') ?></button>
                            <?php } ?>
                            <button type="button" class="btn btn-success btn-sm" onclick="syrus_AI_shareTwitter()">TEST</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center">
                            <img src="<?php echo esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/linkedin-logo.png') ?>" width="25px" height="25px" alt="">
                        </td>
                        <td>LinkedIn</td>

                        <td class="text-center">
                            <?php if($linkedin_connected) { ?>
                                <span class="dashicons dashicons-yes-alt text-success"></span>
                            <?php } else { ?>
                                <span class="dashicons dashicons-dismiss text-danger"></span>
                            <?php } ?>
                        </td>

                        <td class="text-center">

                        </td>

                        <td class="text-center">
                            <?php if($linkedin_connected) { ?>
                                <button type="button" class="btn btn-danger btn-sm" id="btn-disconnect-linkedin" onclick="syrus_AI_disconnectLinkedIn()"><?php esc_html_e('Disconnect','syrus-ai') ?></button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-primary btn-sm" id="linkedin_connect" onclick="syrus_AI_connectLinkedIn()"><?php esc_html_e('Connect','syrus-ai') ?></button>
                            <?php } ?>

                            <button type="button" class="btn btn-success btn-sm" onclick="syrus_AI_shareLinkedIn()">TEST</button>
                        </td>
                    </tr>

                    <?php if($linkedin_companies) { ?>
                        <tr class="sub-tr">
                            <td></td>
                            <td colspan="4" class="ld-company">
                                <input type="radio" name="where_ld" value="0" class="form-check-input me-2" onclick="saveWherePost('linkedin')" <?php echo $connected_social['linkedin']['where_post'] == 0 ? 'checked' : '' ?>><?php esc_html_e('Private profile','syrus-ai') ?>
                            </td>
                        </tr>

                        <?php foreach($linkedin_companies as $ld_company) { $company = (object) $ld_company; ?>
                            <tr class="sub-tr">
                                <td></td>
                                <td colspan="4" class="ld-company">
                                    <input type="radio" name="where_ld" value="<?php echo esc_attr($company->id) ?>" onclick="saveWherePost('linkedin')" class="form-check-input me-2" <?php echo $connected_social['linkedin']['where_post'] == $company->id ? 'checked' : '' ?>><?php echo esc_html($company->name) ?>
                                </td>
                            </tr>
                    <?php }} ?>

                    <!-- <tr>
                        <td class="text-center"><img src="<?php echo esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/ig-logo.png') ?>" width="25px" height="25px" alt=""></td>
                        <td>Instagram</td>
                        <td colspan="3"><?php esc_html_e('Coming Soon','syrus-ai') ?></td>
                    </tr> -->

                    <tr>
                        <td class="text-center">
                            <img src="<?php echo esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/ig-logo.png') ?>" width="25px" height="25px" alt="">
                        </td>
                        <td>Instagram</td>
                        <td class="text-center">
                            <?php if($instagram_connected) { ?>
                                <span class="dashicons dashicons-yes-alt text-success"></span>
                            <?php } else { ?>
                                <span class="dashicons dashicons-dismiss text-danger"></span>
                            <?php } ?>
                        </td>

                        <td class="text-center">
                            <?php if($instagram_connected) { ?>
                                <p class="m-0 fs-5">∞</p>
                            <?php } ?>
                        </td>

                        <td class="text-center">
                            <?php if($instagram_connected) { ?>
                                <button type="button" class="btn btn-danger btn-sm" id="btn-disconnect-ig" onclick="syrus_AI_disconnectInstagram()"><?php esc_html_e('Disconnect','syrus-ai') ?></button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-primary btn-sm" id="twitter_connect" onclick="syrus_AI_connectInstagram()"><?php esc_html_e('Connect','syrus-ai') ?></button>
                            <?php } ?>
                            <button type="button" class="btn btn-success btn-sm" onclick="syrus_AI_shareInstagram()">TEST</button>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center"><img src="<?php echo esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/pinterest-logo.png') ?>" width="25px" height="25px" alt=""></td>
                        <td>Pinterest</td>
                        <td colspan="3"><?php esc_html_e('Coming Soon','syrus-ai') ?></td>
                    </tr>
                    <tr>
                        <td class="text-center"><img src="<?php echo esc_url($syrusAIPlugin->settings['plugin_url'] . '/assets/images/tumblr-logo.png') ?>" width="25px" height="25px" alt=""></td>
                        <td>Tumblr</td>
                        <td colspan="3"><?php esc_html_e('Coming Soon','syrus-ai') ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">2) <?php esc_html_e("Sharing Criteria",'syrus-ai') ?></h4>
        <p class="m-0"><?php esc_html_e("Select the categories and tags that must match those in the article to share on social media",'syrus-ai') ?></p>
        <p><b><?php esc_html_e("Warning",'syrus-ai') ?>: </b><u><?php esc_html_e("If no category and no tag has been selected, any article will be shared.",'syrus-ai') ?></u></p>
    </div>
    <div class="col-4 mb-3">
        <h6 class="m-0"><?php esc_html_e("Categories") ?></h6>
        <p class="mb-2"><?php esc_html_e("Select the categories of articles you wish to share on connected social networks",'syrus-ai') ?></p>
        <div class="syrus-ai-category-checklist-container">
            <ul>
                <?php wp_category_checklist($post_id = false, $descendants_and_self = false, $general_settings['category']) ?>
            </ul>
        </div>
    </div>

    <div class="col-4 mb-3">
        <h6 class="m-0"><?php esc_html_e("Tags") ?></h6>
        <p class="mb-2"><?php esc_html_e("Select the tags of articles you wish to share on connected social networks",'syrus-ai') ?></p>
        <div class="syrus-ai-tags-checklist-container">
            <ul>
                <?php $syrusAIPlugin->tags_checklist(); ?>
            </ul>
        </div>
    </div>

    <div class="col-4 mb-3">
        <h6 class="m-0"><?php esc_html_e("Social Networks",'syrus-ai') ?></h6>
        <p class="mb-2"><?php esc_html_e("Select the social networks where you intend to share the articles",'syrus-ai') ?></p>

        <div class="d-flex syrus-ai-social-checklist-container">
            <?php $syrusAIPlugin->social_connected_checklist() ?>
        </div>
    </div>

    <div class="col-12 d-flex align-items-end justify-content-start">
        <button type="button" class="btn btn-primary btn-sm" onclick="save_general_settings()">
            <?php esc_html_e("Save sharing criteria",'syrus-ai'); ?>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h4 class="mb-1">3) <?php esc_html_e('Prompt Configuration','syrus-ai') ?> <span class="fs-6">(<?php echo esc_html(get_locale()) ?>)</span></h4>
    </div>


    <div class="col-7 mb-1">
        <input type="text" class="form-control form-control-sm w-100" readonly value="<?php echo esc_attr($syrusAIPlugin->get_associative_prompt()['title']) ?>">
    </div>

    <div class="col-7 mb-1">
        <textarea name="default_main" autocomplete="off" rows="5" maxlength="400" class="form-control form-control-sm w-100"><?php echo esc_html($syrusAIPlugin->get_associative_prompt()['default_main']) ?></textarea>
    </div>

    <div class="col-7 mb-2">
        <input type="text" class="form-control form-control-sm w-100" readonly value="<?php echo esc_html($syrusAIPlugin->get_associative_prompt()['social']) ?>">
    </div>

    <div class="col-12">
        <button type="button" class="btn btn-primary btn-sm" onclick="syrusAI_save_prompt()" id="btn-save-prompt"><?php esc_html_e("Save prompt",'syrus-ai') ?></button>
    </div>
</div>
