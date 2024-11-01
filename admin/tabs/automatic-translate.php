<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    global $syrusAIPlugin;

    $general_settings = $syrusAIPlugin->get_general_settings();
    $translate_domain = $syrusAIPlugin->get_translate_domain();
    $articles = $syrusAIPlugin->get_translate_db();
?>

<h3><?php esc_html_e('AI Translate','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can translate articles taken from another WordPress website and automatically put them in your website drafts.",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<div class="row mb-5">
    <div class="col-12">
        <h4 class="m-0">1) <?php esc_html_e("Select websites",'syrus-ai') ?></h4>
        <p class="m-0"><?php esc_html_e("Enter the website below for taking the last articles and click 'Start' button",'syrus-ai') ?></p>
        <p class="m-0"><?php esc_html_e('Write domain without http or https, for example: google.com (not https://google.com), amazon.com (not https://amazon.com)','syrus-ai') ?></p>
        <p class="mb-3"><u><?php esc_html_e("Make sure the website you enter is a WordPress website",'syrus-ai') ?></u></p>
    </div>
    <div class="col-4 d-flex gap-3">
        <input type="text" id="domain-input" class="form-control form-control-sm" placeholder="Insert domain here...">
        <button type="button" class="btn btn-primary btn-sm btn-add-domain"><?php esc_html_e("Start",'syrus-ai') ?></button>
    </div>
</div>

<h1><?php esc_html_e('Translate and Publish','syrus-ai') ?></h1>

<div class="container mt-5">
    <div class="row">
        <div class="col-6">
            <div class="card card-translate">
                <div class="card-body text-center">
                    <div class="row">
                        <h5 class="card-title">Get articles from domain</h5>
                        <div class="col-12 mt-5">
                            <input class="text-center" id="input-domain-automatic" type="text" placeholder="Insert domain" style="width: 20rem">
                        </div>
                        <button href="#" class="btn btn-primary mt-5 button-automatic" onclick="automatic()">Get Articles</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-translate">
                <div class="card-body text-center">
                    <h5 class="card-title">COMING SOON</h5>
                    <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a> -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <p class="m-0"><i><?php esc_html_e("You can edit settings here:",'syrus-ai') ?></i><a href="<?php echo esc_url(admin_url('admin.php?page=syrus-ai-admin-page&tab=advanced-settings')) ?>" class="ms-2"><?php esc_html_e("Here",'syrus-ai') ?></a></p>
        </div>
    </div>
    <div class="mt-5">
        <button class="btn btn-primary" id="translate-button" onclick="do_translate()"><?php echo esc_html_e('Translate and publish','syrus-ai') ?></button>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Titolo</th>
                    <th scope="col">Categorie</th>
                    <th scope="col">Tag</th>
                    <th scope="col">URL</th>
                    <th scope="col">Seleziona</th>
                </tr>
            </thead>
            <tbody>
            <?php if(empty($articles)) {
                ?>
                <tr>
                    <td colspan="7">
                        <h1 class="text-center">No result</h1>
                    </td>
                </tr>
                <?php
            } ?>
            <?php foreach($articles as $article) { ?>
                <?php
                $date = $article['date'];
                $date = substr($date, "0", "10");
                $date = date_create($date);
                $correct_date = date_format($date, "d/m/y")
                ?>
                <tr>
                    <td><?php echo esc_html($correct_date) ?></td>
                    <td><?php echo esc_html($article['title']) ?></td>
                    <td><?php echo esc_html($article['categories']) ?></td>
                    <td><?php echo esc_html($article['tags']) ?></td>
                    <td><a href="<?php echo esc_url($article['link']) ?>" target="_blank"><?php echo esc_html($article['link']) ?></a></td>
                    <td><input type="checkbox" name="" id=""></td>
                    <input type="hidden" id="content" value="<?php echo esc_attr($article['content']) ?>">
                    <input type="hidden" id="media" value="<?php echo esc_attr($article['media']) ?>">
                </tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
</div>
