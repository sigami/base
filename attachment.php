<?php get_header(); ?>
    <div class="container">
        <div id="content" class=" row">

            <main id="main" class="col-sm-12" role="main">

                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
                        <header>
                            <div class="page-header">
                                <?php the_post_thumbnail( 'featured'  ); ?>

                                <h1 class="single-title" itemprop="headline"><?php the_title(); ?></h1></div>

                            <p class="meta"><?php _e("Posted", "sigami"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php echo get_the_date('F j, Y', '','', FALSE); ?></time> <?php _e("by", "sigami"); ?> <?php the_author_posts_link(); ?>.</p>

                        </header> <!-- end article header -->

                        <section class="post_content " itemprop="articleBody">
                            <img class="img-responsive" src="<?php echo wp_get_attachment_url($post->ID) ?>">
                            <?php the_content(); ?>
                            <?php wp_link_pages(); ?>

                        </section> <!-- end article section -->

                        <footer>

                            <?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","sigami") . ':</span> ', ' ', '</p>'); ?>

                            <?php
                            // only show edit button if user has permission to edit posts
                            if( $user_level > 0 ) {
                                ?>
                                <a href="<?php echo get_edit_post_link(); ?>" class="btn btn-success edit-post"><i class="icon-pencil icon-white"></i> <?php _e("Edit post","sigami"); ?></a>
                            <?php } ?>

                        </footer> <!-- end article footer -->

                    </article> <!-- end article -->


                <?php endwhile; ?>

                <?php else : ?>

                    <article id="post-not-found">
                        <header>
                            <h1><?php _e("Not Found", "sigami"); ?></h1>
                        </header>
                        <section class="post_content">
                            <p><?php _e("Sorry, but the requested resource was not found on this site.", "sigami"); ?></p>
                        </section>
                        <footer>
                        </footer>
                    </article>

                <?php endif; ?>

            </main> <!-- end #main -->


        </div> <!-- end #content -->
    </div>
<?php get_footer(); ?>