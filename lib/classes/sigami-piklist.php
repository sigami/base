<?php
/**
 * Class Sigami_Piklist
 * Adds piklist support to the theme
 * Use this class for all piklist hooks.
 */
class  Sigami_Piklist {
    private static $instance = null;
    public static function get_instance() {
    if ( null == self::$instance ) {
        self::$instance = new self;
    }
        return self::$instance;
    }
    private function Sigami_Piklist() {
        add_action( 'init', array( $this, 'init' ) );
    }
    public function init() {
        if(is_admin()) {
            locate_template('/lib/vendor/class-piklist-checker.php', true, true);
            if (!piklist_checker::check(__FILE__, 'theme'))
                return;
        }
    }
}

$options = get_option('sigami_base_options');

if( isset($options['piklist']) && $options['piklist'] == 'true' )
    Sigami_Piklist::get_instance();