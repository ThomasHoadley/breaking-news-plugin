<?php

/**
 * @link https://tomhoadley.co.uk
 * @since 1.0.0
 * @package TH_Breaking_News
 *
 * @wordpress-plugin
 * Plugin Name: TH Breaking News
 * Plugin URI: https://tomhoadley.co.uk
 * Description: This plugin allows you to feature posts as breaking news on your website.
 * Version: 1.0.0
 * Author: Tom Hoadley
 * Author URI: https://tomhoadley.co.uk
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: th-breaking-news
 */

if (!defined('ABSPATH')) {
    die;
}

if (!class_exists('TH_Breaking_News')) {
    define('PLUGIN_NAME', plugin_basename(__FILE__));
    define('PLUGIN_PATH', plugin_dir_path(__FILE__));

    class TH_Breaking_News
    {

        /**
         * Register the class
         */
        function register()
        {
            add_action('init', array($this, 'handle_admin'));
            add_action('init', array($this, 'handle_frontend'));

            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend'));
            add_filter("plugin_action_links_" . PLUGIN_NAME, array($this, 'settings_link'));
        }

        /**
         * Runs when the plugin first activates
         */
        function activate()
        {
            $this->handle_admin();
            $this->handle_frontend();
        }

        /**
         * 
         * 
         * FRONTEND FUNCTIONS
         * 
         * 
         */

        /**
         * Initialise front end.
         */
        function handle_frontend()
        {
            add_action('wp_footer', array($this, 'th_breaking_news_template'));
        }

        /**
         * Enqueue front end scripts.
         */
        function enqueue_frontend()
        {
            wp_enqueue_style('th_breaking_news_frontend_style', plugins_url('/assets/frontend_style.css', __FILE__));
            wp_enqueue_script('th_breaking_news_frontend_script', plugins_url('/assets/frontend_script.js', __FILE__), array('jquery'), false, true);
        }


        /**
         * Returns the Breaking News bar template.
         */
        function th_breaking_news_template()
        {
            require_once PLUGIN_PATH . 'templates/breaking-news-bar.php';
        }

        /**
         * 
         * 
         * ADMIN FUNCTIONS
         * 
         * 
         */

        /**
         * Eneueue admin scripts.
         */
        function enqueue_admin()
        {

            wp_enqueue_style('th_breaking_news_datetime_style', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css');
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js', array('jquery'), false, true);
            wp_enqueue_script('th_breaking_news_admin_script', plugins_url('/assets/admin_script.js', __FILE__), array('wp-color-picker', 'datetimepicker'), false, true);
        }

        /**
         * Initialize admin functions
         */
        function handle_admin()
        {
            // Add the menu 
            add_action('admin_menu', array($this, 'add_admin_pages'));

            // Set up the post meta box
            add_action("add_meta_boxes", array($this, 'add_post_meta_box'));
            add_action("save_post", array($this, 'save_custom_meta_box'), 10, 3);

            // Set up the plugin option fields
            add_action('admin_init', array($this, 'th_breaking_news_register_title_field'));
            add_action('admin_init', array($this, 'th_breaking_news_register_selector_field'));
            add_action('admin_init', array($this, 'th_breaking_news_register_bg_field'));
            add_action('admin_init', array($this, 'th_breaking_news_register_font_colour_field'));
            add_action('admin_init', array($this, 'th_breaking_news_register_blinker_field'));
        }

        /**
         * 
         * 
         * REGISTER POST CUSTOM FIELDS
         * 
         * 
         */

        /**
         * Add the meta box to the new posts
         */
        function add_post_meta_box()
        {
            add_meta_box("th-breaking-news-meta-box", "Breaking News", array($this, "meta_box_markup"), "post", "side", "high", null);
        }

        /**
         * Display the metabox mark up
         */
        function meta_box_markup($object)
        {
            wp_nonce_field(basename(__FILE__), "meta-box-nonce");
            require_once PLUGIN_PATH . 'templates/metabox.php';
        }


        /**
         * Save the post meta boxes
         */
        function save_custom_meta_box($post_id, $post, $update)
        {
            if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
                return $post_id;

            if (!current_user_can("edit_post", $post_id))
                return $post_id;

            if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                return $post_id;

            $slug = "post";
            if ($slug != $post->post_type) {
                return $post_id;
            }

            $th_breaking_news_checked_value = "";
            $th_breaking_news_custom_title_value = "";
            $th_breaking_news_expiry_date_checked_value = "";
            $th_breaking_news_expiry_date_value = "";

            if (isset($_POST["th_breaking_news_checked"])) {
                $this->reset_breaking_news();
                $th_breaking_news_checked_value = $_POST["th_breaking_news_checked"];
            }
            update_post_meta($post_id, "th_breaking_news_checked", $th_breaking_news_checked_value);


            if (isset($_POST["th_breaking_news_custom_title"])) {
                $th_breaking_news_custom_title_value = $_POST["th_breaking_news_custom_title"];
            }
            update_post_meta($post_id, "th_breaking_news_custom_title", $th_breaking_news_custom_title_value);

            if (isset($_POST["th_breaking_news_expiry_date_checked"])) {
                $th_breaking_news_expiry_date_checked_value = $_POST["th_breaking_news_expiry_date_checked"];
            }
            update_post_meta($post_id, "th_breaking_news_expiry_date_checked", $th_breaking_news_expiry_date_checked_value);

            if (isset($_POST["th_breaking_news_expiry_date"])) {
                $th_breaking_news_expiry_date_value = $_POST["th_breaking_news_expiry_date"];
            }
            update_post_meta($post_id, "th_breaking_news_expiry_date", $th_breaking_news_expiry_date_value);
        }

        /**
         * Clear all the breaking news posts.
         */
        function reset_breaking_news()
        {
            $args = array(
                'meta_query' => array(
                    array(
                        'key' => 'th_breaking_news_checked',
                        'value' => 'true',
                        'compare' => '='
                    )
                )
            );

            $the_query = new WP_Query($args);

            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    update_post_meta(get_the_ID(), 'th_breaking_news_checked', 'false');
                }
            }

            wp_reset_postdata();
        }


        /**
         * 
         * 
         * REGISTER OPTION CUSTOM FIELDS
         * 
         * 
         */

        /**
         * Add the plugin page to the WordPress menu.
         */
        function add_admin_pages()
        {
            add_options_page(
                'Breaking News', // page <title>Title</title>
                'Breaking News', // menu link text
                'manage_options', // capability to access the page
                'th-breaking-news', // page URL slug
                array($this, 'breaking_news_admin_page'), // callback function with content
                10 // priority
            );
        }

        /**
         * Add a custom settings link to the plugin section.
         */
        function settings_link(array $links)
        {
            $settings_link = '<a href="options-general.php?page=th-breaking-news">Settings</a>';
            array_push($links, $settings_link);
            return $links;
        }

        /**
         * Render the plugin admin page.
         */
        function breaking_news_admin_page()
        {
            require_once PLUGIN_PATH . 'templates/admin.php';
        }


        /**
         * Add the title field section
         */
        function th_breaking_news_register_title_field()
        {

            register_setting(
                'th_breaking_news_settings', // settings group name
                'th_breaking_news_title' // option name
            );

            add_settings_section(
                'title-section', // section ID
                '', // title 
                '', // callback function 
                'th-breaking-news' // page slug
            );

            add_settings_field(
                'th_breaking_news_title',
                'Banner title<br/>',
                array($this, 'th_breaking_news_text_field_html'), // function which prints the field
                'th-breaking-news', // page slug
                'title-section', // section ID
                array(
                    'label_for' => 'th_breaking_news_title',
                    'class' => 'th-breaking-news-class', // for <tr> element
                )
            );
        }

        /**
         * Title field HTML
         */
        function th_breaking_news_text_field_html()
        {
            $text = get_option('th_breaking_news_title');
            printf(
                '<input type="text" id="th_breaking_news_title" name="th_breaking_news_title" value="%s" />',
                esc_attr($text)
            );
        }

        /**
         * Add the selector field section
         */
        function th_breaking_news_register_selector_field()
        {

            register_setting(
                'th_breaking_news_settings', // settings group name
                'th_breaking_news_selector' // option name
            );

            add_settings_section(
                'selector-section', // section ID
                '', // title
                '', // callback function
                'th-breaking-news' // page slug
            );

            add_settings_field(
                'th_breaking_news_selector',
                'CSS Selector: Insert the Breaking News bar after your given element.<br />',
                array($this, 'th_breaking_news_selector_field_html'), // function which prints the field
                'th-breaking-news', // page slug
                'selector-section', // section ID
                array(
                    'label_for' => 'th_breaking_news_selector',
                    'class' => 'th-breaking-news-class', // for <tr> element
                )
            );
        }

        /**
         * Selector field HTML
         */
        function th_breaking_news_selector_field_html()
        {
            $selector = get_option('th_breaking_news_selector');
            printf(
                '<input type="text" id="th_breaking_news_selector" name="th_breaking_news_selector" value="%s" />',
                esc_attr($selector)
            );
        }


        /**
         * Add the background field section
         */
        function th_breaking_news_register_bg_field()
        {

            register_setting(
                'th_breaking_news_settings', // settings group name
                'th_breaking_news_bg', // option name
                array($this, 'validate_background')
            );

            add_settings_section(
                'bg-section', // section ID
                '', // title
                '', // callback function
                'th-breaking-news' // page slug
            );

            add_settings_field(
                'th_breaking_news_bg',
                'Banner background colour<br/>',
                array($this, 'th_breaking_news_bg_field_html'), // function which prints the field
                'th-breaking-news', // page slug
                'bg-section', // section ID
                array(
                    'label_for' => 'th_breaking_news_bg'
                )
            );
        }

        /**
         * Background field HTML
         */
        function th_breaking_news_bg_field_html()
        {
            $text = get_option('th_breaking_news_bg');
            printf(
                '<input class="th-breaking-news-color-field" data-default-color="#cd2653" type="text" id="th_breaking_news_bg" name="th_breaking_news_bg" value="%s" />',
                esc_attr($text)
            );
        }


        /**
         * Add the font colour section
         */
        function th_breaking_news_register_font_colour_field()
        {

            register_setting(
                'th_breaking_news_settings', // settings group name
                'th_breaking_news_font_colour', // option name
                array($this, 'validate_font_colour') // sanitization function
            );

            add_settings_section(
                'font_colour-section', // section ID
                '', // title
                '', // callback function
                'th-breaking-news' // page slug
            );

            add_settings_field(
                'th_breaking_news_font_colour',
                'Banner font colour<br/>',
                array($this, 'th_breaking_news_font_colour_field_html'), // function which prints the field
                'th-breaking-news', // page slug
                'font_colour-section', // section ID
                array(
                    'label_for' => 'th_breaking_news_font_colour'
                )
            );
        }

        /**
         * font colour field HTML
         */
        function th_breaking_news_font_colour_field_html()
        {
            $text = get_option('th_breaking_news_font_colour');
            printf(
                '<input class="th-breaking-news-color-field" data-default-color="#fff" type="text" id="th_breaking_news_font_colour" name="th_breaking_news_font_colour" value="%s" />',
                esc_attr($text)
            );
        }


        /**
         * Add the blinker field section
         */
        function th_breaking_news_register_blinker_field()
        {

            register_setting(
                'th_breaking_news_settings', // settings group name
                'th_breaking_news_blinker' // option name
            );

            add_settings_section(
                'blinker-section', // section ID
                '', // blinker
                '', // callback function
                'th-breaking-news' // page slug
            );

            add_settings_field(
                'th_breaking_news_blinker',
                'Turn on banner blinker animation<br/>',
                array($this, 'th_breaking_news_blinker_field_html'), // function which prints the field
                'th-breaking-news', // page slug
                'blinker-section', // section ID
                array(
                    'label_for' => 'th_breaking_news_blinker',
                    'class' => 'th-breaking-news-class', // for <tr> element
                )
            );
        }

        /**
         * Blinker field HTML
         */
        function th_breaking_news_blinker_field_html()
        {
            $field = get_option('th_breaking_news_blinker');
            if ($field == 'true') {
                printf(
                    '<input type="checkbox" id="th_breaking_news_blinker" name="th_breaking_news_blinker" value="true" checked />',
                    esc_attr($field)
                );
            } else {
                printf(
                    '<input type="checkbox" id="th_breaking_news_blinker" name="th_breaking_news_blinker" value="true" />',
                    esc_attr($field)
                );
            }
        }


        /**
         * 
         * 
         * HELPER FUNCTIONS
         * 
         * 
         */

         /**
         * Test if breaking news should be displayed or not
         */
        function display_breaking_news($post_id)
        {
            $display_breaking_news_value = "true";

            if ($post_id == '') {
                $display_breaking_news_value = "false";
                return $display_breaking_news_value;
            }

            $expiry_date = get_post_meta($post_id, "th_breaking_news_expiry_date", true);
            $expiry_date_formatted = strtotime($expiry_date);

            $current_date = date_i18n("Y-m-d H:i:s");
            $current_date_formatted = strtotime($current_date);

            $expiry_date_checked = get_post_meta($post_id, "th_breaking_news_expiry_date_checked", true);


            if ($expiry_date_checked == "true" && $current_date_formatted >= $expiry_date_formatted) {
                $display_breaking_news_value = "false";
            }

            return $display_breaking_news_value;
        }


        /**
         * Test expiration
         */

        function test_expiration($post_id)
        {
            $expired = "false";

            $expiry_date = get_post_meta($post_id, "th_breaking_news_expiry_date", true);
            $expiry_date_formatted = strtotime($expiry_date);

            $current_date = date_i18n("Y-m-d H:i:s");
            $current_date_formatted = strtotime($current_date);

            $expiry_date_checked = get_post_meta($post_id, "th_breaking_news_expiry_date_checked", true);

            if ($expiry_date_checked == "true" && $current_date_formatted >= $expiry_date_formatted) {
                $expired = "true";
            }

            return $expired;
        }

        /**
         * Get the post that was last set as Breaking News
         */
        function get_breaking_news_post_id()
        {
            $id = "";

            $args = array(
                'meta_query' => array(
                    array(
                        'key' => 'th_breaking_news_checked',
                        'value' => 'true',
                        'compare' => '='
                    )
                )
            );

            $the_query = new WP_Query($args);
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $id = get_the_ID();
                    return $id;
                }
            }
            wp_reset_postdata();
        }

        /**
         * Validate the background colour
         */
        public function validate_background($fields)
        {
            $valid_field = "";
            $background = $fields;
            if (FALSE === $this->check_colour($background)) {
                add_settings_error('th_breaking_news_settings', 'th_breaking_news_bg_error', 'Insert a valid hex colour for the background colour', 'error');
                $valid_field = get_option('th_breaking_news_bg');;
            } else {
                $valid_field = $background;
            }

            return apply_filters('validate_background', $valid_field, $fields);
        }

        /**
         * Validate the font colour
         */
        public function validate_font_colour($fields)
        {
            $valid_field = "";
            $font_colour = $fields;

            if (FALSE === $this->check_colour($font_colour)) {
                add_settings_error('th_breaking_news_settings', 'th_breaking_news_font_colour_error', 'Insert a valid hex colour for the font colour', 'error');
                $valid_field = get_option('th_breaking_news_font_colour');
            } else {
                $valid_field = $font_colour;
            }

            return apply_filters('validate_font_colour', $valid_field, $fields);
        }


        /**
         * Function that will check if value is a valid HEX color.
         */
        public function check_colour($value)
        {

            if (preg_match('/^#[a-f0-9]{6}$/i', $value) || preg_match('/^$/', $value)) {
                return true;
            }
            return false;
        }
    }


    $thBreakingNews = new TH_Breaking_News();
    $thBreakingNews->register();

    // activate
    register_activation_hook(__FILE__, array($thBreakingNews, 'activate'));

}
