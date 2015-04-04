<?php

class  Sigami_Activation
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function Sigami_Activation()
    {
        if (is_admin() && isset($_GET['activated']) && 'themes.php' == $GLOBALS['pagenow']) {
            wp_redirect(admin_url('themes.php?page=theme_activation_options'));
            exit;
        }

        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this,'admin_menu'), 50);
        add_action('switch_theme', array($this,'switch_theme'));
    }

    function admin_init()
    {
        if(strpos(wp_get_referer(),'theme_activation_options') == false)
            return;

        register_setting( 'base_activation_options', 'sigami_base_options' );

        $sigami_base_options = get_option('sigami_base_options',array());

        if(isset($sigami_base_options['done']))
            return;


        if ($sigami_base_options['create_front_page'] === 'true') {

            $default_pages = array(__('Home', 'sigami'));
            $existing_pages = get_pages();
            $temp = array();

            foreach ($existing_pages as $page) {
                $temp[] = $page->post_title;
            }

            $pages_to_create = array_diff($default_pages, $temp);

            foreach ($pages_to_create as $new_page_title) {
                $add_default_pages = array(
                    'post_title' => $new_page_title,
                    'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum consequat, orci ac laoreet cursus, dolor sem luctus lorem, eget consequat magna felis a magna. Aliquam scelerisque condimentum ante, eget facilisis tortor lobortis in. In interdum venenatis justo eget consequat. Morbi commodo rhoncus mi nec pharetra. Aliquam erat volutpat. Mauris non lorem eu dolor hendrerit dapibus. Mauris mollis nisl quis sapien posuere consectetur. Nullam in sapien at nisi ornare bibendum at ut lectus. Pellentesque ut magna mauris. Nam viverra suscipit ligula, sed accumsan enim placerat nec. Cras vitae metus vel dolor ultrices sagittis. Duis venenatis augue sed risus laoreet congue ac ac leo. Donec fermentum accumsan libero sit amet iaculis. Duis tristique dictum enim, ac fringilla risus bibendum in. Nunc ornare, quam sit amet ultricies gravida, tortor mi malesuada urna, quis commodo dui nibh in lacus. Nunc vel tortor mi. Pellentesque vel urna a arcu adipiscing imperdiet vitae sit amet neque. Integer eu lectus et nunc dictum sagittis. Curabitur commodo vulputate fringilla. Sed eleifend, arcu convallis adipiscing congue, dui turpis commodo magna, et vehicula sapien turpis sit amet nisi.',
                    'post_status' => 'publish',
                    'post_type' => 'page'
                );

                wp_insert_post($add_default_pages);
            }

            $home = get_page_by_title(__('Home', 'sigami'));
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home->ID);
            add_post_meta($home->ID, '_wp_page_template', 'template-homepage.php',true) || update_post_meta( $home->ID, '_wp_page_template', 'template-homepage.php' );

            $home_menu_order = array(
                'ID' => $home->ID,
                'menu_order' => -1
            );
            wp_update_post($home_menu_order);
        }

        if ($sigami_base_options['change_permalink_structure'] === 'true') {

            if (get_option('permalink_structure') !== '/%postname%/') {
                global $wp_rewrite;
                $wp_rewrite->set_permalink_structure('/%postname%/');
                flush_rewrite_rules();
            }
        }
        $sigami_base_options['done'] = "true";
        update_option('sigami_base_options', $sigami_base_options);
        //wp_redirect(admin_url('themes.php'));
        //exit;
    }
    function admin_menu(){
        $sigami_base_options = get_option('sigami_base_options');

        if ( $sigami_base_options === false ) {
            add_theme_page(
                __('Theme Activation', 'sigami'),
                __('Theme Activation', 'sigami'),
                'edit_theme_options',
                'theme_activation_options',
                array($this,'render_page')
            );
        } else {
            if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'theme_activation_options') {
                flush_rewrite_rules();
                wp_redirect(admin_url('themes.php'));
                exit;
            }
        }
    }
    function render_page(){
        ?>
        <div class="wrap">
            <h2><?php printf(__('%s Theme Activation', 'sigami'), wp_get_theme()); ?></h2>
            <div class="update-nag">
                <?php _e('These settings are optional and should usually be used only on a fresh installation', 'sigami'); ?>
            </div>
            <?php settings_errors();

            $sigami_base_options = get_option('sigami_base_options');

            print_r($sigami_base_options);

            ?>

            <form method="post" action="options.php">
                <?php settings_fields('base_activation_options'); ?>
                <table class="form-table">
                    <tr valign="top"><th scope="row"><?php _e('Create static front page?', 'sigami'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Create static front page?', 'sigami'); ?></span></legend>
                                <select name="sigami_base_options[create_front_page]" id="create_front_page">
                                    <option value="true"><?php echo _e('Yes', 'sigami'); ?></option>
                                    <option selected="selected" value="false"><?php echo _e('No', 'sigami'); ?></option>
                                </select>
                                <p class="description"><?php printf(__('Create a page called Home and set it to be the static front page', 'sigami')); ?></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row"><?php _e('Change permalink structure?', 'sigami'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Update permalink structure?', 'sigami'); ?></span></legend>
                                <select name="sigami_base_options[change_permalink_structure]" id="change_permalink_structure">
                                    <option selected="selected" value="true"><?php echo _e('Yes', 'sigami'); ?></option>
                                    <option value="false"><?php echo _e('No', 'sigami'); ?></option>
                                </select>
                                <p class="description"><?php printf(__('Change permalink structure to /&#37;postname&#37;/', 'sigami')); ?></p>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row"><?php _e('Will this theme use Piklist?', 'sigami'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Add pages to menu?', 'sigami'); ?></span></legend>
                                <select name="sigami_base_options[piklist]" id="add_pages_to_main_nav">
                                    <option value="true"><?php echo _e('Yes', 'sigami'); ?></option>
                                    <option selected="selected" value="false"><?php echo _e('No', 'sigami'); ?></option>
                                </select>
                                <p class="description"><?php _e('Activating this will prompt installation', 'sigami'); ?></p>
                            </fieldset>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>

    <?php
    }
    function switch_theme() {
        delete_option('sigami_base_options');
    }
}

Sigami_Activation::get_instance();




