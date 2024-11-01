<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    global $syrusAIPlugin;
    $general_settings = $syrusAIPlugin->get_general_settings();
    $newsapi_token = $syrusAIPlugin->get_newsapi_token();
    $newsMaking = $syrusAIPlugin->get_news_db();
    $settings = $syrusAIPlugin->get_newsmaking_settings();
    $country = $syrusAIPlugin->get_newsmaking_country();

    $cron_settings = get_option("syrus-ai-newsmaking-cron-settings", true);

    $domains = get_option("syrus-ai-domains-automation-translation", true);
    $status_cron = get_option("syrus-ai-enable-cron-newsmaking", true);
    $hours = $syrusAIPlugin->get_hours();
    $cron_hour = $syrusAIPlugin->get_cron_hour();
?>

<h1><?php esc_html_e('Newsmaking','syrus-ai') ?></h1>

<div class="container">
    <div class="row col-12">
        <div class="card w-100" style="height: 18rem;">
            <div class="card-body">
                <h5 class="card-title text-center">Newsmaking Settings</h5>
                <div class="row">
                    <div class="col-6">
                        <div class="card-text text-center mt-2">
                            <h6>General Newsmaking</h6>
                        </div>
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-6 text-center">
                                <div class="card-text d-flex justify-content-center">
                                    <div class="form-group w-100">
                                        <label for="select-category-news-making">Category</label>
                                        <select class="form-select w-100" id="select-category-news-making">
                                            <option value="general" <?php echo isset($settings['category']) && $settings['category'] == "general" ? 'selected' : ''?>>General</option>
                                            <option value="business" <?php echo isset($settings['category']) && $settings['category'] == "business" ? 'selected' : ''?>>Business</option>
                                            <option value="entertainment" <?php echo isset($settings['category']) && $settings['category'] == "entertainment" ? 'selected' : ''?>>Entertainment</option>
                                            <option value="health" <?php echo isset($settings['category']) && $settings['category'] == "health" ? 'selected' : ''?>>Health</option>
                                            <option value="science" <?php echo isset($settings['category']) && $settings['category'] == "science" ? 'selected' : ''?>>Science</option>
                                            <option value="sports" <?php echo isset($settings['category']) && $settings['category'] == "sports" ? 'selected' : ''?>>Sports</option>
                                            <option value="technology" <?php echo isset($settings['category']) && $settings['category'] == "technology" ? 'selected' : ''?>>Technology</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-text d-flex justify-content-center mt-2">
                                    <div class="form-group w-100">
                                        <label for="select-country-news-making">Nation</label>
                                        <select class="form-select w-100" id="select-country-news-making">
                                            <?php foreach($country as $value => $nation) {?>
                                                        <option value="<?php echo esc_attr($value) ?>" <?php echo isset($settings['country']) && $settings['country'] == $value ? 'selected' : ''?>> <?php echo esc_html($nation) ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card-text text-center mt-2">
                            <h6>Keyword Newsmaking</h6>
                        </div>
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="card-text">
                                    <div class="form-group">
                                        <label for="select-domain-news-making">Domains</label>
                                        <div class="input-group">
                                            <select class="selectCustomDomainNewsMaking w-100" id="select-domain-news-making" multiple="multiple">
                                                <?php
                                                $domains = $settings['domain'];
                                                foreach($domains as $domain) { ?>
                                                    <option selected><?php echo esc_html($domain) ?></option>
                                                <?php }
                                                //} ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-text">
                                    <div class="form-group">
                                        <label for="select-keyword-news-making">Keyword</label>
                                        <div class="input-group">
                                            <input type="text" id="select-keyword-news-making" class="w-100" placeholder="Insert keyword ES: apple + peach" value="<?php echo isset($settings['keyword']) && $settings['keyword'] ? esc_attr($settings['keyword']) : ''?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="card-text">
                                    <div class="form-group">
                                        <label for="select-language-news-making">Language</label>
                                        <select class="form-select w-100" id="select-language-news-making">
                                            <option value="en" <?php echo isset($settings['language']) && $settings['language'] == "en" ? 'selected' : ''?>>English</option>
                                            <option value="it" <?php echo isset($settings['language']) && $settings['language'] == "it" ? 'selected' : ''?>>Italian</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-text mt-2">
                                    <div class="form-group">
                                        <label for="select-date-news-making">Date</label>
                                        <select class="form-select" id="select-date-news-making">
                                            <option value="today" <?php echo isset($settings['date']) && $settings['date'] == "today" ? 'selected' : ''?>>Today</option>
                                            <option value="yesterday" <?php echo isset($settings['date']) && $settings['date'] == "yesterday" ? 'selected' : ''?>>Yesterday</option>
                                            <option value="last_week" <?php echo isset($settings['date']) && $settings['date'] == "last_week" ? 'selected' : ''?>>Last week</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary button-save-newsmaking" onclick="save_newsmaking_settings()">Save</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card text-center w-100" style="height: 20rem;">
                <div class="card-body">
                    <h5 class="card-title">Manual Newsmaking</h5>
                    <div class="mt-4">
                        <div class="col-12">
                            <div class="card-text">
                                <h6>General Newsmaking</h6>
                                <button class="btn btn-primary button-refresh-news-making w-100" onclick="doNewsMaking2()">Do</button>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="card-text">
                                <h6>Keyword Newsmaking</h6>
                                <button class="btn btn-primary button-refresh-news-making w-100" onclick="doNewsMaking()">Do</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-center w-100" style="height: 20rem;">
                <div class="card-body">
                    <h5 class="card-title">Automatic Newsmaking</h5>
                    <p class="card-text" style="font-size: 1em;">Newsmaking status <?php echo $status_cron == "1" ? 'ACTIVATED' : 'DISACTIVATATED'?></p>
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label for="mode-cron-newsmaking">Mode</label>
                            <select id="mode-cron-newsmaking" class="w-100">
                                <option value="general" <?php echo isset($cron_settings) && $cron_settings['mode'] == "general" ? 'selected' : ''?>>General Newsmaking</option>
                                <option value="keyword" <?php echo isset($cron_settings) && $cron_settings['mode'] == "keyword" ? 'selected' : ''?>>Keyword Newsmaking</option>
                            </select>
                            <label for="email-cron-newsmaking" class="mt-4">Email where to send the newsmaking</label>
                            <input type="text" id="email-cron-newsmaking" class="w-100" placeholder="Insert your email" value="<?php echo isset($cron_settings) ? esc_attr($cron_settings['email']) : ''?>">
                        </div>
                        <div class="col-1"></div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-2"></div>
                        <div class="col-4">
                            <a href="#" class="btn <?php echo $status_cron == "0" ? 'btn-success' : 'btn-danger'?>" onclick="changeCronStatus()"><?php echo $status_cron == "0" ? 'ACTIVE' : 'INACTIVE'?></a>
                        </div>
                        <div class="col-4">
                            <select class="form-select w-100" id="cron-hour">
                            <?php foreach($hours as $hour) { ?>
                                <option value="<?php echo esc_attr($hour) ?>" <?php echo $hour == $cron_hour ? 'selected' : ''?>><?php echo esc_html($hour) ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="col-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Author</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($newsMaking)) { ?>
            <tr>
                <td colspan="3">
                    <h1 class="text-center">No result</h1>
                </td>
            </tr>
            <?php } ?>
        <?php foreach($newsMaking as $news) { ?>
            <tr>
                <td><a href="<?php echo esc_attr($news['link']) ?>" target="_blank"><?php echo esc_html($news['title']) ?></a></td>
                <td><?php echo esc_html($news['author']) ?></td>
                <td><?php echo esc_html($news['date']) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
