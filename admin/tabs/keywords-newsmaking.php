<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

    global $syrusAIPlugin;
    $general_settings = $syrusAIPlugin->get_general_settings();
    $newsapi_token = $syrusAIPlugin->get_newsapi_token();
    $newsMaking = $syrusAIPlugin->get_news_db("keyword");
    $settings = $syrusAIPlugin->get_newsmaking_settings();

    $cron_settings = get_option("syrus-ai-newsmaking-cron-settings", true);
    $domains = get_option("syrus-ai-domains-automation-translation", true);
    $status_cron = get_option("syrus-ai-enable-cron-newsmaking", true);
    $hours = $syrusAIPlugin->get_hours();
    $cron_hour = $syrusAIPlugin->get_cron_hour();
?>
<h3><?php esc_html_e('Advanced Newsmaking','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can carry out your news making based on the keywords and domains selected.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">1) <?php esc_html_e("Insert domains",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Enter the domains on which to carry out newsmaking",'syrus-ai') ?></p>
    </div>
    <div class="col-4">
        <div class="input-group">
            <select class="selectCustomDomainNewsMaking w-100" id="select-domain-news-making" multiple="multiple">
                <?php
                // if(isset($settings['domain'])) {
                $domains = $settings['domain'];
                foreach($domains as $domain) { ?>
                    <option selected><?php echo esc_html($domain) ?></option>
                <?php }
                //} ?>
            </select>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">2) <?php esc_html_e("Insert keywords",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Enter the keywords on which to carry out newsmaking",'syrus-ai') ?></p>
    </div>
    <div class="col-4">
        <div class="input-group">
            <input type="text" id="select-keyword-news-making" class="w-100" placeholder="Insert keyword ES: apple + peach" value="<?php echo isset($settings['keyword']) && $settings['keyword'] ? esc_html($settings['keyword']) : ''?>">
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">3) <?php esc_html_e("Select language",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Select the language on which to carry out newsmaking",'syrus-ai') ?></p>
    </div>
    <div class="col-4">
        <select class="form-select w-100" id="select-language-news-making">
            <option value="en" <?php echo isset($settings['language']) && $settings['language'] == "en" ? 'selected' : ''?>>English</option>
            <option value="it" <?php echo isset($settings['language']) && $settings['language'] == "it" ? 'selected' : ''?>>Italian</option>
        </select>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">4) <?php esc_html_e("Select date",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Select the date on which to carry out newsmaking",'syrus-ai') ?></p>
    </div>
    <div class="col-4">
        <select class="form-select" id="select-date-news-making">
            <option value="today" <?php echo isset($settings['date']) && $settings['date'] == "today" ? 'selected' : ''?>>Today</option>
            <option value="yesterday" <?php echo isset($settings['date']) && $settings['date'] == "yesterday" ? 'selected' : ''?>>Yesterday</option>
            <option value="last_week" <?php echo isset($settings['date']) && $settings['date'] == "last_week" ? 'selected' : ''?>>Last week</option>
        </select>
    </div>
</div>

<div class="row mb-5">
    <div class="col-auto">
        <h4 class="m-0">5) <?php esc_html_e("Results",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Click 'Start' button for your News Making",'syrus-ai') ?></p>
    </div>
    <div class="col-3 mb-2">
        <button class="btn btn-primary btn-start-news-making" onclick="startKeywordNewsMaking()" data-loading-text="<?php esc_html_e("Loading",'syrus-ai') ?>"><?php !empty($newsMaking) ? esc_html_e("Refresh",'syrus-ai') : esc_html_e("Start",'syrus-ai') ?></button>
    </div>
    <div class="col-12 text-end mb-2">
        <button class="btn btn-primary btn-clear-table-news" onclick="clearKeywordNews()" data-loading-text="<?php esc_html_e("Loading",'syrus-ai') ?>">Clear table</button>
    </div>

    <div class="col-12">
        <div class="table-responsive container-results-table">
            <?php if(!empty($newsMaking)) { ?>
                <table class="table">
                    <thead>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                    <?php foreach($newsMaking as $news) { ?>
                        <tr>
                            <td><a href="<?php echo esc_attr($news['link']) ?>" target="_blank"><?php echo esc_html($news['title']) ?></a></td>
                            <td><?php echo esc_html($news['author']) ?></td>
                            <td><?php echo esc_html($news['date']) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>

<template id="swalInvalidAPIKey">
    <swal-title><?php esc_html_e("Error",'syrus-ai') ?></swal-title>
    <swal-html>
        <p class="m-0">
            <?php esc_html_e("Invalid NewsAPI API key",'syrus-ai') ?><br>
            <?php esc_html_e("Change it from ",'syrus-ai') ?><a href="<?php echo esc_url(admin_url("admin.php?page=syrus-ai-admin-page&tab=advanced-settings#NewsAPI")) ?>"><?php esc_html_e('here','syrus-ai') ?></a>
        </p>
    </swal-html>
    <swal-param name="toast" value="true" />
    <swal-param name="position" value="bottom" />
    <swal-param name="showCloseButton" value="true" />
    <swal-param name="showConfirmButton" value="false" />
</template>

<template id="swalSuccess">
    <swal-title><?php esc_html_e("News Making done",'syrus-ai') ?></swal-title>
    <swal-html>
        <p class="m-0">
            <?php esc_html_e("News Making done, please wait few seconds or refresh the page",'syrus-ai') ?><br>
        </p>
    </swal-html>
    <swal-param name="toast" value="true" />
    <swal-param name="position" value="bottom" />
    <swal-param name="showCloseButton" value="true" />
    <swal-param name="showConfirmButton" value="false" />
</template>

<template id="clearSuccess">
    <swal-title><?php esc_html_e("Clear table done",'syrus-ai') ?></swal-title>
    <swal-html>
        <p class="m-0">
            <?php esc_html_e("Clear table done, please wait few seconds or refresh the page",'syrus-ai') ?><br>
        </p>
    </swal-html>
    <swal-param name="toast" value="true" />
    <swal-param name="position" value="bottom" />
    <swal-param name="showCloseButton" value="true" />
    <swal-param name="showConfirmButton" value="false" />
</template>

<template id="swalInvalidData">
    <swal-title><?php esc_html_e("ERROR",'syrus-ai') ?></swal-title>
    <swal-html>
        <p class="m-0">
            <?php esc_html_e("You must fill in all fields",'syrus-ai') ?><br>
        </p>
    </swal-html>
    <swal-param name="toast" value="true" />
    <swal-param name="position" value="bottom" />
    <swal-param name="showCloseButton" value="true" />
    <swal-param name="showConfirmButton" value="false" />
</template>
