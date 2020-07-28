<?php
/**
 * @package TH_Breaking_News
 */
?>
<div>
    <br />

    <label for="th_breaking_news_checked"><b>Make this post breaking news</b></label>
    <p>Tick here to make this post Breaking News. Please note by checking this, you will clear your current Breaking News post.</p>

    <br />

    <?php $breaking_news_checkbox_value = get_post_meta($object->ID, "th_breaking_news_checked", true); ?>

    <?php if ($breaking_news_checkbox_value == "" || $breaking_news_checkbox_value == "false") { ?>
        <input name="th_breaking_news_checked" type="checkbox" value="true">
    <?php } else if ($breaking_news_checkbox_value == "true") { ?>
        <input name="th_breaking_news_checked" type="checkbox" value="true" checked>
    <?php } ?>

    <hr />
    <br />

    <label for="th_breaking_news_custom_title"><b>Custom Breaking News title</b></label>
    <p>Use this field to change the title displayed on the website. Leave blank to use main post title.</p>

    <input name="th_breaking_news_custom_title" type="text" value="<?php echo get_post_meta($object->ID, "th_breaking_news_custom_title", true); ?>">

    <hr />
    <br />

    <label for="th_breaking_news_checked"><b>Set an expiry date for this post</b></label>
    <p>Check this box to remove this post Breaking News post from the website on your chosen date and time.</p>

    <br />

    <?php $expiry_date_checked = get_post_meta($object->ID, "th_breaking_news_expiry_date_checked", true); ?>
    <?php if ($expiry_date_checked == "") { ?>
        <input name="th_breaking_news_expiry_date_checked" type="checkbox" value="true">
    <?php } else if ($expiry_date_checked == "true") { ?>
        <input name="th_breaking_news_expiry_date_checked" type="checkbox" value="true" checked>
    <?php } ?>

    <?php $expiry_date = get_post_meta($object->ID, "th_breaking_news_expiry_date", true); ?>

    <?php if ($expiry_date_checked == "") { ?>
        <input name="th_breaking_news_expiry_date" type="text" value="<?php echo $expiry_date; ?>" class="th-breaking-news-timepicker" autocomplete="off" style="display:none;">
    <?php } else if ($expiry_date_checked == "true") { ?>
        <input name="th_breaking_news_expiry_date" type="text" value="<?php echo $expiry_date; ?>" class="th-breaking-news-timepicker" autocomplete="off">
    <?php } ?>

    <?php
        $has_expired = $this->test_expiration($object->ID);
        if ($has_expired == 'true') {
            echo '<p style="color: #cd2653;"><i>This Breaking News has expired</i></p>';
        }
    ?>
</div>