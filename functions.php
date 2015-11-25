<?php
if ( ! isset( $content_width ) ) $content_width = 900;
/**
 * Class Sigami_Base
 * Main class of the plugin
 */

class  Sigami_Base {
    private static $instance = null;
    public $theme_dir;
    public $theme_url;
    public $has_child = false;
    static function get_instance() {
    if ( null == self::$instance ) {
        self::$instance = new self;
    }
        return self::$instance;
    }
    private function Sigami_Base() {
        /** CONSTANTS **/
        $this->theme_dir = get_template_directory();
        $this->theme_url = get_template_directory_uri();
        if( get_template_directory() != get_stylesheet_directory() ){
            $this->has_child = true;
        }
        //echo $this->theme_dir;
        /** THEME SUPPORTS **/
        add_action('after_setup_theme',array($this,'after_setup_theme'));
        /** THUMBNAIL SIZE OPTIONS **/
        add_image_size('featured', 880, 430, true);
        add_image_size('featured-home', 1200, 350, true);
        /** Show custom image sizes */
        add_filter('image_size_names_choose', array($this, 'image_size_names_choose'), 11, 1);
        /** Sidebars & Widgetizes Areas **/
        add_action('widgets_init', array($this,'widgets_init'));

        /** Composer autoload **/
        locate_template("/lib/vendor/composer/vendor/autoload.php",true,true);
	    $clean_files = array();
        /**  Include all php files inside classes folder */
        $files = glob($this->theme_dir."/lib/classes/*.php");
        foreach ($files as $file){
            $clean = str_replace($this->theme_dir,'',$file);
            $clean_files[] = $clean;
        }
        //Support Child themes replacement of classes.
        if($this->has_child && is_dir(get_stylesheet_directory()."/lib/classes")){
            $child_dir = get_stylesheet_directory();
            $files_child = glob($child_dir."/lib/classes/*.php");
            if(!empty($files_child)){
                foreach ($files_child as $file){
                    $clean_files[] = str_replace($child_dir,'',$file);
                }
            }
        }
        $all_files = array_unique($clean_files);
        foreach($all_files as $file)
            locate_template($file,true,true);

        /** Inlcude Styles and Scripts */
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
    }
    function after_setup_theme(){
        /** TTRANSLATION SUPPORT **/
        load_theme_textdomain('sigami', $this->theme_dir . '/lib/languages');
        add_theme_support( "title-tag" );
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(125, 125, true);
        add_theme_support('custom-background');
        add_theme_support('automatic-feed-links');
        add_theme_support( 'html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ) );
//         Add post format support - if these are not needed, comment them out
        add_theme_support('post-formats',      // post formats
            array(
//                'aside',   // title less blurb
                'gallery', // gallery of images
                'link',    // quick link to other site
                'image',   // an image
                'quote',   // a quick quote
                'status',  // a Facebook like status update
                'video',   // video
                'audio',   // audio
//                'chat'     // chat transcript
            )
        );
        add_editor_style( 'lib/assets/dist/jutzu.css' );
        /** Menu Support **/
        register_nav_menus(
            array(
                'primary_navigation' => 'Primary Navigation',
                'footer_links' => 'Footer Links'
            ));
        /** Custom Theme Supports  **/
        //numbered pagination
        add_theme_support('sigami-pagination');
        //internet explorer 8 support
        add_theme_support('sigami-ie');
        //reload browser on grunt, only for development
        add_theme_support('sigami-grunt');
    }
    function widgets_init()
    {
        register_sidebar(array(
            'id' => 'homepage',
            'name' => 'Homepage Sidebar',
            'description' => __('Used only on the homepage page template.','sigami'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widgettitle">',
            'after_title' => '</h4>',
        ));

        register_sidebar(array(
            'id' => 'sidebar1',
            'name' => 'Main Sidebar',
            'description' => __('Used on every page BUT the homepage page template.','sigami'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widgettitle">',
            'after_title' => '</h4>',
        ));


        register_sidebar(array(
            'id' => 'footer1',
            'name' => 'Footer 1',
            'before_widget' => '<div id="%1$s" class="widget col-sm-4 %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widgettitle">',
            'after_title' => '</h4>',
        ));

        register_sidebar(array(
            'id' => 'footer2',
            'name' => 'Footer 2',
            'before_widget' => '<div id="%1$s" class="widget col-sm-4 %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widgettitle">',
            'after_title' => '</h4>',
        ));

        register_sidebar(array(
            'id' => 'footer3',
            'name' => 'Footer 3',
            'before_widget' => '<div id="%1$s" class="widget col-sm-4 %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widgettitle">',
            'after_title' => '</h4>',
        ));
    }
    function image_size_names_choose( $sizes ) {
        $new_sizes = array();
        $added_sizes = get_intermediate_image_sizes();
        foreach( $added_sizes as $key => $value) {
            $new_sizes[$value] = $value;
        }
        $new_sizes = array_merge( $new_sizes, $sizes );
        return $new_sizes;
    }
    function wp_enqueue_scripts() {
        if (!is_admin()) {
            if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
                wp_enqueue_script('comment-reply');
        }

        wp_register_style('jutzu', $this->theme_url . '/lib/assets/dist/jutzu.css', array(), '1.0', 'all');
        wp_enqueue_style('jutzu');

        wp_register_script('jutzu', $this->theme_url. '/lib/assets/dist/jutzu.js', array('jquery'), '1.2');
        wp_enqueue_script('jutzu');

    }
    static function main_nav_fallback(){
	    //TODO fake menu.
        wp_page_menu( $args = array('menu_class'=>'nav navbar-nav') );
    }
    static function footer_links_fallback(){
        wp_page_menu( $args = array() );
    }

}
Sigami_Base::get_instance();
