<?php
/**  Menu and Boostrap Compatibility
 -------------------------------------- */
Class Sigami_Bootstrap
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function Sigami_Bootstrap()
    {
        /*********** Nav Menu ************/
        add_filter('nav_menu_css_class', array($this, 'nav_menu_css_class'), 10, 2);
        add_filter('wp_nav_menu_args', array($this, 'wp_nav_menu_args'));
        add_filter('nav_menu_item_id', '__return_null');
        /*********** img caption ************/
        add_filter('img_caption_shortcode', array($this, 'img_caption_shortcode'), 10, 3);
        /*********** password form ************/
        add_filter('the_password_form', array($this, 'the_password_form'));
        /*********** Tag Widget ************/
        add_filter('widget_tag_cloud_args', array($this, 'widget_tag_cloud_args'));
        add_action('wp_tag_cloud', array($this, 'wp_tag_cloud'));
        /** Enable shortcodes in widgets **/
        add_filter('widget_text', 'do_shortcode');
        /** Disable jump in 'read more' link **/
        add_filter('the_content_more_link', array($this, 'the_content_more_link'));
        /** Remove height/width attributes on images so they can be responsive **/
        add_filter('post_thumbnail_html', array($this, 'remove_thumbnail_dimensions'), 10);
        add_filter('image_send_to_editor', array($this, 'remove_thumbnail_dimensions'), 10);
        /** Add thumbnail class to thumbnail links **/
        add_filter('wp_get_attachment_link', array($this, 'wp_get_attachment_link'), 10, 1);
    }

    function img_caption_shortcode($output, $attr, $content)
    {
        if (is_feed())
            return $output;
        $defaults = array(
            'id' => '',
            'align' => 'alignnone',
            'width' => '',
            'caption' => ''
        );
        $attr = shortcode_atts($defaults, $attr);
        // If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
        if ($attr['width'] < 1 || empty($attr['caption']))
            return $content;
        // Set up the attributes for the caption <figure>
        $attributes = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '');
        $attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
        $attributes .= ' style="width: ' . esc_attr($attr['width']) . 'px"';
        $output = '<figure' . $attributes . '>';
        $output .= do_shortcode($content);
        $output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
        $output .= '</figure>';
        return $output;
    }

    function is_element_empty($element)
    {
        $element = trim($element);
        return empty($element) ? false : true;
    }

    function nav_menu_css_class($classes, $item)
    {
        $slug = sanitize_title($item->title);
        $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
        $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);
        $classes[] = 'menu-' . $slug;
        $classes = array_unique($classes);
        return array_filter($classes, array($this, 'is_element_empty'));
    }

    function wp_nav_menu_args($args = '')
    {
        $nav_menu_args['container'] = false;
        if (!$args['items_wrap'])
            $nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
        if (!$args['depth'])
            $nav_menu_args['depth'] = 2;
        if (!$args['walker'])
            $nav_menu_args['walker'] = new Sigami_Walker();
        return array_merge($args, $nav_menu_args);
    }

    function the_password_form()
    {
        global $post;
        $label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
        $o = '<div class="clearfix"><form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
	' . '<p>' . __("This post is password protected. To view it please enter your password below:", 'sigami') . '</p>' . '
	<label for="' . $label . '">' . __("Password:", 'sigami') . ' </label><div class="input-append"><input name="post_password" id="' . $label . '" type="password" size="20" /><input type="submit" name="Submit" class="btn btn-primary" value="' . esc_attr__("Submit", 'sigami') . '" /></div>
	</form></div>
	';
        return $o;
    }

    function widget_tag_cloud_args($args)
    {
        $args['number'] = 20; // show less tags
        $args['largest'] = 9.75; // make largest and smallest the same - i don't like the varying font-size look
        $args['smallest'] = 9.75;
        $args['unit'] = 'px';
        return $args;
    }

    function wp_tag_cloud($taglinks)
    {
        $tags = explode('</a>', $taglinks);
        $regex = "#(.*tag-link[-])(.*)(' title.*)#e";

        foreach ($tags as $tag) {
            $tagn[] = preg_replace($regex, "('$1$2 label tag-'.get_tag($2)->slug.'$3')", $tag);
        }

        $taglinks = implode('</a>', $tagn);

        return '<div id="tag-cloud">' . $taglinks . '</div>';
    }

    function the_content_more_link($link)
    {
        $offset = strpos($link, '#more-');
        if ($offset) {
            $end = strpos($link, '"', $offset);
        }
        if ($end) {
            $link = substr_replace($link, '', $offset, $end - $offset);
        }
        return $link;
    }

    function remove_thumbnail_dimensions($html)
    {
        $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
        return $html;
    }

    function wp_get_attachment_link($html)
    {
        $postid = get_the_ID();
        $html = str_replace('<a', '<a class="thumbnail"', $html);
        return $html;
    }
}

Sigami_Bootstrap::get_instance();

class Sigami_Walker extends Walker_Nav_Menu
{
    function check_current($classes)
    {
        return preg_match('/(current[-_])|active|dropdown/', $classes);
    }

    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $output .= "\n<ul class=\"dropdown-menu\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $item_html = '';
        parent::start_el($item_html, $item, $depth, $args);
        if ($item->is_dropdown && ($depth === 0)) {
            $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
            $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
        } elseif (stristr($item_html, 'li class="divider'))
            $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
        elseif (stristr($item_html, 'li class="dropdown-header'))
            $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
        $output .= $item_html;
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));
        if ($element->is_dropdown)
            $element->classes[] = 'dropdown';
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}


/************* COMMENT LAYOUT *********************/

// Comment Layout
function wp_bootstrap_comments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment; ?>
    <li <?php comment_class(); ?>>
    <article id="comment-<?php comment_ID(); ?>" class="clearfix">
        <div class="comment-author vcard clearfix">
            <div class="avatar col-sm-3">
                <?php echo get_avatar($comment, $size = '75'); ?>
            </div>
            <div class="col-sm-9 comment-text">
                <?php printf('<h4>%s</h4>', get_comment_author_link()) ?>
                <?php edit_comment_link(__('Edit', 'sigami'), '<span class="edit-comment btn btn-sm btn-info"><i class="glyphicon-white glyphicon-pencil"></i>', '</span>') ?>

                <?php if ($comment->comment_approved == '0') : ?>
                    <div class="alert-message success">
                        <p><?php _e('Your comment is awaiting moderation.', 'sigami') ?></p>
                    </div>
                <?php endif; ?>

                <?php comment_text() ?>

                <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a
                        href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>"><?php comment_time('F jS, Y'); ?> </a>
                </time>

                <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>
        </div>
    </article>
    <!-- </li> is added by wordpress automatically -->
<?php
} // don't remove this bracket!

// Display trackbacks/pings callback function
function list_pings($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    ?>
<li id="comment-<?php comment_ID(); ?>"><i class="icon icon-share-alt"></i>&nbsp;<?php comment_author_link(); ?>
<?php

}


?>