<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    global $syrusAIPlugin;
    $general_settings = $syrusAIPlugin->get_general_settings();
    $newsapi_token = $syrusAIPlugin->get_newsapi_token();
    $newsMaking = $syrusAIPlugin->get_news_db("category");
    $settings = $syrusAIPlugin->get_newsmaking_settings();

    $cron_settings = get_option("syrus-ai-newsmaking-cron-settings", true);
    $domains = get_option("syrus-ai-domains-automation-translation", true);
    $status_cron = get_option("syrus-ai-enable-cron-newsmaking", true);
    $hours = $syrusAIPlugin->get_hours();
    $cron_hour = $syrusAIPlugin->get_cron_hour();
?>
<h3><?php esc_html_e('General News Making','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can carry out your news making based on the category and country selected.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">1) <?php esc_html_e("Select a category",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Select the category for which you intend to do newsmaking",'syrus-ai') ?></p>
    </div>
    <div class="col-4">
        <select class="form-select w-100" id="select-category-news-making">
            <option value="general">General</option>
            <option value="business">Business</option>
            <option value="entertainment">Entertainment</option>
            <option value="health">Health</option>
            <option value="science">Science</option>
            <option value="sports">Sports</option>
            <option value="technology">Technology</option>
        </select>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">2) <?php esc_html_e("Select a country",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Select the country for which you intend to do newsmaking",'syrus-ai') ?></p>
    </div>
    <div class="col-4">
        <select class="form-select w-100" id="select-country-news-making">
            <option value="ar">Argentina</option>
            <option value="au">Australia</option>
            <option value="at">Austria</option>
            <option value="be">Belgium</option>
            <option value="br">Brazil</option>
            <option value="bg">Bulgaria</option>
            <option value="ca">Canada</option>
            <option value="cn">China</option>
            <option value="co">Colombia</option>
            <option value="cu">Cuba</option>
            <option value="cz">Czech Republic</option>
            <option value="eg">Egypt</option>
            <option value="fr">France</option>
            <option value="de">Germany</option>
            <option value="gr">Greece</option>
            <option value="hk">Hong Kong</option>
            <option value="hu">Hungary</option>
            <option value="in">India</option>
            <option value="id">Indonesia</option>
            <option value="ie">Ireland</option>
            <option value="il">Israel</option>
            <option value="it">Italy</option>
            <option value="jp">Japan</option>
            <option value="lv">Latvia</option>
            <option value="lt">Lithuania</option>
            <option value="my">Malaysia</option>
            <option value="mx">Mexico</option>
            <option value="ma">Morocco</option>
            <option value="nl">Netherlands</option>
            <option value="nz">New Zealand</option>
            <option value="ng">Nigeria</option>
            <option value="no">Norway</option>
            <option value="ph">Philippines</option>
            <option value="pl">Poland</option>
            <option value="pt">Portugal</option>
            <option value="ro">Romania</option>
            <option value="ru">Russia</option>
            <option value="sa">Saudi Arabia</option>
            <option value="rs">Serbia</option>
            <option value="sg">Singapore</option>
            <option value="sk">Slovakia</option>
            <option value="si">Slovenia</option>
            <option value="za">South Africa</option>
            <option value="kr">South Korea</option>
            <option value="se">Sweden</option>
            <option value="ch">Switzerland</option>
            <option value="tw">Taiwan</option>
            <option value="th">Thailand</option>
            <option value="tr">Turkey</option>
            <option value="ae">UAE</option>
            <option value="ua">Ukraine</option>
            <option value="gb">United Kingdom</option>
            <option value="us">United States</option>
            <option value="ve">Venuzuela</option>
        </select>
    </div>
</div>

<div class="row mb-5">
    <div class="col-auto">
        <h4 class="m-0">3) <?php esc_html_e("Results",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Click 'Start' button for your News Making",'syrus-ai') ?></p>
    </div>
    <div class="col-3 mb-2">
        <button class="btn btn-primary btn-start-news-making" onclick="startCategoryNewsMaking()" data-loading-text="<?php esc_html_e("Loading",'syrus-ai') ?>"><?php !empty($newsMaking) ? esc_html_e("Refresh",'syrus-ai') : esc_html_e("Start",'syrus-ai') ?></button>
    </div>
    <div class="col-12 text-end mb-2">
        <button class="btn btn-primary btn-clear-table-news" onclick="clearCategoryNews()" data-loading-text="<?php esc_html_e("Loading",'syrus-ai') ?>">Clear table</button>
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
                            <td><a href="<?php echo esc_url($news['link']) ?>" target="_blank"><?php echo esc_html($news['title']) ?></a></td>
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
