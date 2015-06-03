<?php
/**  Cleans output html
-------------------------------------- */
class Sigami_Cleanup
{

    private static $instance = null;

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function Sigami_Cleanup()
    {
        $actions = array('init', 'template_redirect');
        $filters = array('language_attributes', 'style_loader_tag', 'body_class', 'get_bloginfo_rss', 'request');
        foreach ($actions as $action) {
            add_action($action, array($this, $action));
        }
        foreach ($filters as $filter) {
            add_filter($filter, array($this, $filter));
        }
        add_filter('get_avatar', array($this,'remove_self_closing_tags')); // <img />
        add_filter('comment_id_fields', array($this,'remove_self_closing_tags')); // <input />
        add_filter('post_thumbnail_html', array($this,'remove_self_closing_tags')); // <img />
        add_filter('the_generator', '__return_false');
    }

    function init()
    {
        remove_action('wp_head', 'feed_links_extra', 3);                    // Category Feeds
        remove_action('wp_head', 'feed_links', 2);                          // Post and Comment Feeds
        remove_action('wp_head', 'rsd_link');                               // EditURI link
        remove_action('wp_head', 'wlwmanifest_link');                       // Windows Live Writer
        remove_action('wp_head', 'index_rel_link');                         // index link
        remove_action('wp_head', 'parent_post_rel_link', 10, 0);            // previous link
        remove_action('wp_head', 'start_post_rel_link', 10, 0);             // start link
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // Links for Adjacent Posts
        remove_action('wp_head', 'wp_generator');
        global $wp_widget_factory;
        if(isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])){
            remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
        }
        add_rewrite_rule(__('search','sigami').'/(.+?)/?$', 'index.php?s=$matches[1]', 'top');
    }


    function language_attributes()
    {
        $attributes = array();
        if (function_exists('is_rtl')) {
            if (is_rtl() == 'rtl') {
                $attributes[] = 'dir="rtl"';
            }
        }
        $local = explode("_",get_locale());
        $attributes[] = 'lang="' . $local[0] . '"';
        $output = implode(' ', $attributes);
        return $output;
    }

    function style_loader_tag($input)
    {
        preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
        // Only display media if it is meaningful
        $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
        return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
    }

    function body_class($classes)
    {
        // Add post/page slug
        if (is_single() || is_page() && !is_front_page()) {
            $classes[] = basename(get_permalink());
        }

        // Remove unnecessary classes
        $home_id_class = 'page-id-' . get_option('page_on_front');
        $remove_classes = array(
            'page-template', 'page', 'home',
            $home_id_class
        );
        $classes = array_diff($classes, $remove_classes);

        return $classes;
    }

    function template_redirect()
    {
        global $wp_rewrite;
        if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks())
            return;
        $search_base = __('search','sigami');
        if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {
            wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
            exit();
        }
    }

    function get_bloginfo_rss($bloginfo)
    {
        $default_tagline = 'Just another WordPress site';
        return ($bloginfo === $default_tagline) ? '' : $bloginfo;
    }

    function request($query_vars)
    {
        if (isset($_GET['s']) && empty($_GET['s']))
            $query_vars['s'] = ' ';
        return $query_vars;
    }

    function remove_self_closing_tags($input)
    {
        return str_replace(' />', '>', $input);
    }
}

Sigami_Cleanup::get_instance();
