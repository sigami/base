<!doctype html>
<?php if( current_theme_supports('sigami-ie') ) : ?>
<!--[if IEMobile 7 ]><html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> ><!--<![endif]-->
<?php else : ?>
<html <?php language_attributes(); ?> >
<?php endif; ?>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php wp_head(); ?>
    <?php if( current_theme_supports('sigami-ie') ) : ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
    <?php endif; ?>
</head>
<body <?php body_class(); ?>>
<header role="banner">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" title="<?php echo get_bloginfo('description'); ?>"
                   href="<?php echo home_url(); ?>" rel="home"><?php bloginfo('name'); ?></a>
            </div>
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                <?php
                wp_nav_menu(
                    array(
                        'menu' => 'main_nav',
                        'menu_class' => 'nav navbar-nav',
                        'theme_location' => 'primary_navigation',
                        'container' => 'false',
                        'fallback_cb' => array('Sigami_Base', 'main_nav_fallback'),
                    )
                );
                ?>
                <?php do_action('sigami_navbar') ?>
            </div>
        </div>
    </nav>
</header>
