<footer role="contentinfo">
    <hr/>
    <div class="container">

        <div id="widget-footer" class="row">
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer1')) : ?>
            <?php endif; ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer2')) : ?>
            <?php endif; ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer3')) : ?>
            <?php endif; ?>
        </div>


        <p class="attribution">&copy; <?php bloginfo('name'); ?></p>

    </div>
    <!-- end #inner-footer -->

</footer> <!-- end footer -->
<?php if (current_theme_supports('sigami-ie')) : ?>
    <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload', function () {
        CFInstall.check({mode: 'overlay'})
    })</script>
    <![endif]-->
<?php endif; ?>
<?php wp_footer(); // js scripts are inserted using this function ?>
<?php if (current_theme_supports('sigami-grunt')) : ?>
    <script src="//localhost:35729/livereload.js"></script>
<?php endif; ?>
</body>

</html>