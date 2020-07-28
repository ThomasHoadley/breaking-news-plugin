<?php

/**
 * Trigger file on uninstall
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Remove the options

delete_option('th_breaking_news_font_colour');
delete_option('th_breaking_news_bg');
delete_option('th_breaking_news_bg_colour');
delete_option('th_breaking_news_selector');
delete_option('th_breaking_news_title');
delete_option('th_breaking_news_blinker');

// Remove the post meta
$args = array('post_type'=>'post', 'posts_per_page' => -1);

$the_query = new WP_Query( $args );
 
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        $id = get_the_ID();
        delete_post_meta( $id, 'th_breaking_news_checked' );
        delete_post_meta( $id, 'th_breaking_news_custom_title' );
        delete_post_meta( $id, 'th_breaking_news_expiry_date' );
        delete_post_meta( $id, 'th_breaking_news_expiry_date_checked' );
        delete_post_meta( $id, 'th_breaking_news_last_activated' );

    }
} 
wp_reset_postdata();