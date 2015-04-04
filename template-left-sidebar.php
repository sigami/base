<?php
/*
Template Name: Left Sidebar Page
*/
?>

<?php get_header(); ?>
    <div class="container">
        <div id="content" class="row">
            <?php get_sidebar(); ?>
            <main id="main" class="col col-lg-8" role="main">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?> itemscope itemtype="http://schema.org/Article"  >
                        <header>
                            <div class="page-header">
                                <?php the_post_thumbnail( 'featured', array('itemprop'=>'image') ); ?>
                                <h1 itemprop="name" class="entry-title" ><?php the_title(); ?></h1>
                            </div>
                            <dl class="entry-meta">
                                <dt><?php _e("Posted", "sigami"); ?></dt>
                                <dd><time class="published" datetime="<?php echo get_the_time('c'); ?>" itemprop="datePublished"><?php echo get_the_date(); ?></time>.</dd>
                                <?php if(get_the_time('U') < get_the_modified_time('U')) : ?>
                                    <dt><?php _e("Updated", "sigami"); ?></dt>
                                    <dd><time class="updated" datetime="<?php echo get_the_modified_time('c'); ?>" itemprop="dateModified"><?php echo get_the_modified_date(); ?></time></dd>
                                <?php endif; ?>
                                <dd><span class="author vcard"><?php _e('by', 'sigami'); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" ><span itemprop="author" rel="author" class="fn"><?php echo get_the_author(); ?></span></a></span></dd>
                            </dl>
                        </header>
                        <div itemprop="articleBody" class="entry-content" >
                            <?php the_content(); ?>
                        </div>
                        <footer>
                            <?php if( user_can(get_current_user_id(),'edit_posts') ) : ?>
                                <a href="<?php echo get_edit_post_link(); ?>" class="btn btn-success edit-post"><i class="icon-pencil icon-white"></i> <?php _e("Edit post","sigami"); ?></a>
                            <?php endif; ?>
                        </footer>
                    </article>
                    <?php comments_template('',true); ?>
                <?php endwhile; ?>
            </main>
        </div>
    </div>
<?php get_footer(); ?>
