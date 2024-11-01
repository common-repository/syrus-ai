<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

    global $syrusAIPlugin;
    $general_settings = $syrusAIPlugin->get_general_settings();
    // $newsapi_token = $syrusAIPlugin->get_newsapi_token();
    $newsMaking = $syrusAIPlugin->get_news_db("keyword");
    // $settings = $syrusAIPlugin->get_newsmaking_settings();

    // $cron_settings = get_option("syrus-ai-newsmaking-cron-settings", true);
    // $domains = get_option("syrus-ai-domains-automation-translation", true);
    // $status_cron = get_option("syrus-ai-enable-cron-newsmaking", true);
    // $hours = $syrusAIPlugin->get_hours();
    // $cron_hour = $syrusAIPlugin->get_cron_hour();
?>
<h3><?php esc_html_e('Advanced Newsmaking','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can carry out your news making based on the keywords and country selected.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">1) <?php esc_html_e("Insert keywords",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Enter the keywords on which to carry out newsmaking",'syrus-ai') ?></p>
    </div>
    <div class="col-4">
        <div class="input-group">
            <input type="text" id="input-keywords-news-making" class="w-100" placeholder="Insert keyword ES: apple, peach, watermelon, ..." value="<?php echo isset($settings['keyword']) && $settings['keyword'] ? esc_html($settings['keyword']) : ''?>">
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">2) <?php esc_html_e("Select country",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Select the country on which to carry out newsmaking",'syrus-ai') ?></p>
    </div>
    <div class="col-4">
        <select class="form-select w-100" id="select-country-news-making">
            <option value="Argentina">Argentina</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Belgium">Belgium</option>
            <option value="Brazil">Brazil</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Canada">Canada</option>
            <option value="China">China</option>
            <option value="Colombia">Colombia</option>
            <option value="Cuba">Cuba</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Egypt">Egypt</option>
            <option value="France">France</option>
            <option value="Germany">Germany</option>
            <option value="Greece">Greece</option>
            <option value="Hong Kong">Hong Kong</option>
            <option value="Hungary">Hungary</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Ireland">Ireland</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Japan">Japan</option>
            <option value="Latvia">Latvia</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Mexico">Mexico</option>
            <option value="Morocco">Morocco</option>
            <option value="Netherlands">Netherlands</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nigeria">Nigeria</option>
            <option value="Norway">Norway</option>
            <option value="Philippines">Philippines</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russia</option>
            <option value="Saudi">Saudi Arabia</option>
            <option value="Serbia">Serbia</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Korea">South Korea</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Taiwan">Taiwan</option>
            <option value="Thailand">Thailand</option>
            <option value="Turkey">Turkey</option>
            <option value="UAE">UAE</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="United States">United States</option>
            <option value="Venuzuela">Venuzuela</option>
        </select>
    </div>
</div>

<div class="row mb-5">
    <div class="col-auto">
        <h4 class="m-0">3) <?php esc_html_e("Results",'syrus-ai') ?></h4>
        <p><?php esc_html_e("Click 'Start' button for your News Making",'syrus-ai') ?></p>
    </div>
    <div class="col-3 mb-2">
        <button class="btn btn-primary btn-start-news-making" onclick="getNewsMaking()" data-loading-text="<?php esc_html_e("Loading",'syrus-ai') ?>"><?php !empty($newsMaking) ? esc_html_e("Refresh",'syrus-ai') : esc_html_e("Start",'syrus-ai') ?></button>
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