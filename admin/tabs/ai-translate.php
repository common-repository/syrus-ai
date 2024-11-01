<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    global $syrusAIPlugin;

    $currentAITranslationDomains = get_option('syrus-ai-translations-domains') ? unserialize(get_option('syrus-ai-translations-domains')) : [];

    $page = filter_input(
        INPUT_GET,
        'page',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ['options' => 'esc_html']
    );

    $domain = filter_input(
        INPUT_GET,
        'domain',
        FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ['options' => 'esc_html']
    );

    $current_page = filter_input(
        INPUT_GET,
        'page_n',
        FILTER_SANITIZE_NUMBER_INT,
        ['options' => 'esc_html']
    );

    if(!$current_page)
        $current_page = 1;

    $exists = $domain ? true : false;

    if($exists) {
        $articles = $syrusAIPlugin->get_translate_db($domain);

        $pagination = $articles["2"];

        $articles = $articles["1"];

        foreach($currentAITranslationDomains as $d) {
            if($d['domain'] != $domain)
                continue;

            $exists = true;
        }
    }

    // Numero totale di pagine
    $total_pages = isset($pagination) ? $pagination : ''; // Sostituisci con il tuo valore reale

    // var_dump($total_pages);
    // die;


    // Numero di pagine da mostrare prima e dopo la pagina corrente
    $pages_to_show = 2;

    // Calcola la pagina iniziale e finale da mostrare
    $start_page = max(1, $current_page - $pages_to_show);
    $end_page = min($total_pages, $current_page + $pages_to_show);

    // var_dump($end_page, $total_pages, $current_page, $pages_to_show);die;

    // Mostra il link "Precedente" solo se non sei sulla prima pagina
    $prev_page = ($current_page > 1) ? $current_page - 1 : false;

    // Mostra il link "Successiva" solo se non sei sull'ultima pagina
    $next_page = ($current_page < $total_pages) ? $current_page + 1 : false;
?>

<input type="hidden" name="domain" value="<?php echo $exists ? esc_html($domain) : '' ?>">

<h3><?php esc_html_e('AI Translate','syrus-ai') ?></h3>
<p class="m-0"><?php esc_html_e("Here you can manage the articles from the websites you added",'syrus-ai') ?></p>
<hr class="mt-1 mb-4">

<!-- Tabs navs -->
<ul class="nav nav-tabs nav-tabs-domains" id="ex1" role="tablist">
    <?php foreach($currentAITranslationDomains as $d) { ?>
        <li class="nav-item domain-tab mb-0" role="presentation">
            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=ai-translate&domain=' . $d['domain'])) ?>" class="nav-link bg-white <?php echo ($exists) ? ($d['domain'] == $domain ? 'active' : '') : '' ?>" role="tab"><?php echo esc_html($d['domain']) ?></a>
        </li>
    <?php } ?>

    <?php if(count($currentAITranslationDomains) > 0) {?>
        <li class="nav-item domain-tab mb-0 add-domain-tab" role="presentation">
            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=settings-ai-translate')) ?>" class="nav-link bg-white" role="tab">
                <span class="dashicons dashicons-insert"></span> <?php esc_html_e("Add new domain",'syrus-ai') ?>
            </a>
        </li>
    <?php } ?>
</ul>

<?php if(count($currentAITranslationDomains) > 0) { ?>
    <div class="tabs-content-domains <?php echo $domain ? 'active' : '' ?>">
        <div class="container-fluid">
            <?php if($domain) { ?>
                <div class="row p-2">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary" id="btn-take-articles" data-loading-text="<?php esc_html_e("Loading",'syrus-ai') ?>" onclick="getNewArticles()"><?php esc_html_e("Take new articles",'syrus-ai') ?></button>
                    </div>
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <th scope="col">WP ID</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Categories</th>
                                    <th scope="col">Tags</th>
                                    <th scope="col">Published At</th>
                                    <th scope="col">Actions</th>
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
                                    <!-- <td><input type="checkbox" name="" id=""></td> -->
                                    <td><?php echo esc_html($article['id_wp']) ?></td>
                                    <td><img class="img-thumbnail" id="media-article" src="<?php echo esc_html($article['media']) ?>" alt="" style="width: 250px; height: 120px; object-fit: cover;"></td>
                                    <td><a target="_blank" href="<?php echo esc_html($article['link']) ?>"><?php echo esc_html($article['title']) ?></a></td>
                                    <td><?php echo esc_html($article['categories']) ?></td>
                                    <td><?php echo esc_html($article['tags']) ?></td>
                                    <td><?php echo esc_html($correct_date) ?></td>
                                    <td>
                                        <div class="row">
                                            <div class="d-flex">
                                                <?php if($article['post_id'] == "") { ?>
                                                    <button type="button" class="btn btn-primary" onclick="do_translate('<?php echo esc_html($article['id_wp']) ?>');">Translate</button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-secondary" onclick="do_translate('<?php echo esc_html($article['id_wp']) ?>', <?php echo esc_html($article['post_id']) ?>);"><span class="dashicons dashicons-translation"></span> Again</button>
                                                <?php } ?>
                                                <button type="button" class="btn btn-danger ms-2" onclick="delete_article('<?php echo esc_html($article['id_wp']) ?>');">Remove</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            <ul class="pagination">
                                <?php if ($prev_page !== false) { ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo 'admin.php?page=syrus-ai-admin-page&tab=ai-translate&domain=' . urlencode($exists ? $domain : '') . '&page_n=' . urlencode($prev_page) ?>">Previous</a></li>
                                <?php } ?>

                                <?php for ($i = $start_page; $i <= $end_page; $i++) { ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo 'admin.php?page=syrus-ai-admin-page&tab=ai-translate&domain=' . urlencode($exists ? $domain : '') . '&page_n=' . urlencode($i) ?>"><?php echo esc_html($i) ?></a></li>
                                <?php } ?>

                                <?php if ($next_page !== false) { ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo 'admin.php?page=syrus-ai-admin-page&tab=ai-translate&domain=' . urlencode($exists ? $domain : '') . '&page_n=' . urlencode($next_page) ?>">Next</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } else { ?>
    <div class="row mb-5">
        <div class="col-12 mb-3">
            <p class="m-0"><?php esc_html_e("It looks like you didn't enter any domains, go",'syrus-ai') ?> <a href="<?php echo esc_url(admin_url('admin.php?page=' . $page . '&tab=settings-ai-translate')) ?>"><?php esc_html_e("here",'syrus-ai') ?></a> <?php esc_html_e(" to add them and start translating.",'syrus-ai') ?></p>
        </div>
    </div>
<?php } ?>

<template id="loading">
    <swal-title><?php esc_html_e("Loading translation",'syrus-ai') ?></swal-title>
    <swal-html>
        <p class="m-0">
            <?php esc_html_e("Translation of the article in progress...",'syrus-ai') ?><br>
        </p>
    </swal-html>
    <swal-param name="toast" value="true" />
    <swal-param name="position" value="bottom" />
    <swal-param name="showCloseButton" value="true" />
    <swal-param name="showConfirmButton" value="false" />
</template>

<template id="success">
    <swal-title><?php esc_html_e("Translation completed",'syrus-ai') ?></swal-title>
    <swal-html>
        <p class="m-0">
            <?php esc_html_e("The translation of the article was successful",'syrus-ai') ?><br>
        </p>
    </swal-html>
    <swal-param name="toast" value="true" />
    <swal-param name="position" value="bottom" />
    <swal-param name="showCloseButton" value="true" />
    <swal-param name="showConfirmButton" value="false" />
</template>

<template id="success_delete">
    <swal-title><?php esc_html_e("Article deleted successfully",'syrus-ai') ?></swal-title>
    <swal-html>
        <p class="m-0">
            <?php esc_html_e("The article was successfully deleted",'syrus-ai') ?><br>
        </p>
    </swal-html>
    <swal-param name="toast" value="true" />
    <swal-param name="position" value="bottom" />
    <swal-param name="showCloseButton" value="true" />
    <swal-param name="showConfirmButton" value="false" />
</template>
