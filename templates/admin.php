<?php
/**
 * @package TH_Breaking_News
 * Template used for the options page.
 */

$current_breaking_news_ID = $this->get_breaking_news_post_id();
$currentBreakingNewsTitle = get_the_title($current_breaking_news_ID);
$display_breaking_news = $this->display_breaking_news($current_breaking_news_ID);
?>

<div class="wrap">
    <h2 class="wp-heading-inline">Breaking News</h2>
    <p>Use this area to edit the Breaking News bar that is displayed on the front end of the website.</p>
    <p>To edit the breaking news, please find your desired post from <a href="/wp-admin/edit.php/wp-admin/edit.php">here</a> and check "Make this post breaking news".</p>

    <hr>

    <h2>Current Breaking News</h2>
    <?php if ($display_breaking_news == "true") { ?>
        <h2><a href="/wp-admin/post.php?post=<?= $current_breaking_news_ID; ?>&action=edit" target="_blank"><?= $currentBreakingNewsTitle; ?></a></h2>
    <?php } else { ?>
        <p>There are currently no active Breaking News posts.</p>
    <?php }; ?>
    <hr>

    <form method="post" action="options.php">
        <h3>Breaking News section options</h3>
        <p>Edit the fields below to change the format of the breaking news section displayed on the website.</p>
        <p>Leave blank if you want to use defaults.</p>

        <?php
            settings_fields('th_breaking_news_settings'); // settings group name
            do_settings_sections('th-breaking-news'); // a page slug
            submit_button();
        ?>
    </form>

</div>