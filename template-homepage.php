<?php
/*
Template Name: Homepage
*/
?>

<?php get_header(); ?>
    <div class="container">
        <div id="content" class="row">

            <main id="main" class="col-sm-12" role="main">

                <?php while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

                        <header>

                            <?php
                            $post_thumbnail_id = get_post_thumbnail_id();
                            $featured_src = wp_get_attachment_image_src($post_thumbnail_id, 'featured-home');
                            ?>

                            <div class="jumbotron"
                                 style="background-image: url('<?php echo $featured_src[0]; ?>'); background-repeat: no-repeat; background-position: 0 0;">

                                <div class="page-header">
                                    <h1 class="entry-title"><?php bloginfo('title'); ?><br>
                                        <small><?php echo get_bloginfo ( 'description' ); ?></small>
                                    </h1>
                                </div>

                            </div>

                        </header>

                        <section class="row">

                            <div class="col-sm-8 entry-content">

                                <?php the_content(); ?>

                            </div>

                            <?php get_sidebar('homepage'); // sidebar front-page ?>

                        </section>
                        <!-- end article header -->

                        <footer>


                        </footer>
                        <!-- end article footer -->

                    </article> <!-- end article -->


                <?php endwhile; ?>

            </main>
            <!-- end #main -->

            <?php //get_sidebar(); // sidebar 1 ?>

        </div>
        <!-- end #content -->
    </div>
<?php get_footer(); ?>
