<?php
/**
 * @package TH_Breaking_News
 * The template used for the front end banner
 */

$bg_colour = (get_option('th_breaking_news_bg') ? get_option('th_breaking_news_bg') : '#cd2653');
$font_colour = (get_option('th_breaking_news_font_colour') ? get_option('th_breaking_news_font_colour') : '#fff');
$breakingNewsTitle = (get_option('th_breaking_news_title') ? get_option('th_breaking_news_title') . ':' : '');
$blinker = (get_option( 'th_breaking_news_blinker' ) ? "true" : "");
$selector = (get_option( 'th_breaking_news_selector' ) ? get_option( 'th_breaking_news_selector' ) : "");

$post_id = $this->get_breaking_news_post_id();
$post_custom_title = get_post_meta($post_id, "th_breaking_news_custom_title", true);
$post_title = ($post_custom_title ? $post_custom_title : get_the_title($post_id));

$display_breaking_news = $this->display_breaking_news($post_id);

?>

<?php if ($display_breaking_news === "true") { ?>

    <div class="th-breaking-news" data-selector="<?= $selector; ?>" style="background-color:<?= htmlspecialchars($bg_colour); ?>;color:<?= htmlspecialchars($font_colour); ?>">

        <h4 class="th-breaking-news-header">
            <?php if($blinker == "true") : ?>
                <span class="blinking-text">
            <?php endif; ?>

            <?= htmlspecialchars($breakingNewsTitle); ?>
            <a href="<?= get_the_permalink($post_id)?>"><?= htmlspecialchars($post_title); ?></a>

            <?php if($blinker == "true") : ?>
                </span>
            <?php endif; ?>
        </h4>
    </div>

<?php } ?>