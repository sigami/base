<?php
/** Class for views and other stuff
-------------------------------------- */
class  Sigami_Views
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    private function Sigami_Views()
    {
        /** Get <head> <title> to behave like other themes **/
        add_filter('wp_title', __CLASS__.'::'.'wp_title', 10, 2);
        /** Remove <p> tags from around images **/
        add_filter('the_content', __CLASS__.'::'.'the_content');
        /** Add lead class to first paragraph **/
        add_filter('the_content', __CLASS__.'::'. 'the_content_lead');
        /** Add flex-video widescreen classes  **/
        add_filter('embed_oembed_html', __CLASS__.'::'. 'embed_oembed_html',10,2);
        /** Excerpt stuff  **/
        add_filter('excerpt_length', __CLASS__.'::'. 'excerpt_length');
        add_filter('excerpt_more', __CLASS__.'::'. 'excerpt_more');
        /** Comments fields */
        add_filter( 'comment_form_default_fields', __CLASS__.'::'.'comment_form_default_fields');
        /**  Schema.org keywords on tags **/
        add_filter( "term_links-post_tag", __CLASS__.'::'."term_links_post_tag" );
        /** wp page links */
        add_filter('wp_link_pages_args', __CLASS__.'::'.'wp_link_pages_args');
        add_filter('wp_link_pages_link',__CLASS__.'::'.'wp_link_pages_link',10,2);
        add_filter('wp_link_pages',__CLASS__.'::'.'wp_link_pages');
    }
    static function wp_title($title, $sep)
    {
        global $paged, $page;

        if (is_feed()) {
            return $title;
        }

        // Add the site name.
        $title .= get_bloginfo('name');

        // Add the site description for the home/front page.
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page())) {
            $title = "$title $sep $site_description";
        }

        // Add a page number if necessary.
        if ($paged >= 2 || $page >= 2) {
            $title = "$title $sep " . sprintf(__('Page %s', 'sigami'), max($paged, $page));
        }

        return $title;
    }
    static function the_content($content)
    {
        return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    }
    static function the_content_lead($content)
    {
        if(get_post_type() == 'post'){
            return preg_replace('/<p([^>]+)?>/', '<p$1 class="lead">', $content, 1);
        }
        return $content;
    }
    static function embed_oembed_html($cache, $url, $attr = '', $post_ID = '')
    {
        if( stripos($url,'twitter.com') == false )
            return '<span>'.$url.'</span><div class="embed-responsive embed-responsive-16by9">' . $cache . '</div>';
        else
            return $cache;
    }
    static function excerpt_length($length)
    {
        return $length;
    }
    static function excerpt_more($more)
    {
        global $post;
        return '...  <a href="' . get_permalink($post->ID) . '" class="more-link" title="Read ' . get_the_title($post->ID) . '">'.__('Read more &raquo;','sigami').'</a>';
    }
    static function comment_form_default_fields(){

        $commenter = wp_get_current_commenter();
        $req = get_option( 'require_name_email' );


        return array(

            'author' =>
                '<div class="form-group">
			  <label for="author">' . __("Name",'sigami')
                . ($req ? " (". __("required",'sigami') .")" : '')
                . ' </label>
			  <div class="input-group">
			  	<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			  	<input class="form-control" type="text" name="author" id="author" value="'
                . esc_attr($commenter['comment_author']). '" placeholder="'
                . __("Your Name",'sigami'). '"'
                . ($req ? ' required aria-required="true"' : ''). '/>'
                . '</div>'
                . '</div>',

            'email' =>
                '<div class="form-group">
			  <label for="email">' . __("Email",'sigami')
                . ($req ? " (". __("required",'sigami') .")" : '')
                . ' </label>
			  <div class="input-group">
			  	<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
			  	<input class="form-control" type="email" name="email" id="email" value="'
                . esc_attr($commenter['comment_author_email']). '" placeholder="'
                . __("Your Email",'sigami'). '"'
                . ($req ? ' required aria-required="true"' : ''). '/>'
                . '</div>'
                . '<span class="help-block">'. __("will not be published",'sigami'). '</span>'
                . '</div>',

            'url' => '<div class="form-group">
			  <label for="author">' . __("Website",'sigami')
                . ' </label>
			  <div class="input-group">
			  	<span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
			  	<input class="form-control" type="url" name="url" id="url" value="'
                . esc_attr($commenter['comment_author_url']). '" placeholder="'
                . __("Your Website",'sigami'). '"'
                . '/>'
                . '</div>'
                . '</div>'
        );
    }
    static function term_links_post_tag($term_links){
        $return = array();
        foreach($term_links as $term){
            $clean = str_replace('rel="tag">','rel="tag"><span itemprop="keywords">',$term);
            $return[]= str_replace('</a>','</span></a>',$clean);
        }
        return $return;
    }
    static function wp_link_pages_args($args){
        $args['before'] = '<nav><ul class="pagination">';
        $args['after'] = '</ul></nav>';
        return $args;
    }
    static function wp_link_pages_link($link,$i){
        if($i == false || $i == null || $i == 0){
            $i = 1;
        }
        $page = get_query_var('page','1');
        $return = ($page == $i) ? '<li class="active"><a href="#">' : '<li>';
        $return .= ($page == $i) ? "$link</a></li>" : "$link</li>";
        return $return;
    }
    static function wp_link_pages($output){
        return str_replace('<li>1</li>','<li class="active"><a href="#">1</a></li>',$output);
    }
	static function numeric_navi($before = '', $after = '',$query=null)
	{

		if($query instanceof WP_Query ){
			$wp_query = $query;
		} else {
			global $wp_query;
		}
//    $request = $wp_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = intval(get_query_var('paged'));
		$numposts = $wp_query->found_posts;
		$max_page = $wp_query->max_num_pages;
		if ($numposts <= $posts_per_page) {
			return;
		}
		if (empty($paged) || $paged == 0) {
			$paged = 1;
		}
		$pages_to_show = 7;
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start = floor($pages_to_show_minus_1 / 2);
		$half_page_end = ceil($pages_to_show_minus_1 / 2);
		$start_page = $paged - $half_page_start;
		if ($start_page <= 0) {
			$start_page = 1;
		}
		$end_page = $paged + $half_page_end;
		if (($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if ($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		if ($start_page <= 0) {
			$start_page = 1;
		}

		echo $before . '<ul class="pagination">' . "";
		if ($paged > 1) {
			$first_page_text = "&laquo";
			echo '<li class="prev"><a href="' . get_pagenum_link() . '" title="' . __('First', 'sigami') . '">' . $first_page_text . '</a></li>';
		}

		$prevposts = get_previous_posts_link(__('&larr; Previous', 'sigami'));
		if ($prevposts) {
			echo '<li>' . $prevposts . '</li>';
		} else {
			echo '<li class="disabled"><a href="#">' . __('&larr; Previous', 'sigami') . '</a></li>';
		}

		for ($i = $start_page; $i <= $end_page; $i++) {
			if ($i == $paged) {
				echo '<li class="active"><a href="#">' . $i . '</a></li>';
			} else {
				echo '<li><a href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
			}
		}
		echo '<li class="">';
		next_posts_link(__('Next &rarr;', 'sigami'));
		echo '</li>';
		if ($end_page < $max_page) {
			$last_page_text = "&raquo;";
			echo '<li class="next"><a href="' . get_pagenum_link($max_page) . '" title="' . __('Last', 'sigami') . '">' . $last_page_text . '</a></li>';
		}
		echo '</ul>' . $after . "";
	}	
}

$sigamiViews = Sigami_Views::get_instance();








