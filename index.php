<?php get_header(); ?>
    <div class="container">
			<div id="content" class="row">
				<div id="main" class="col-sm-8" role="main">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?> itemscope itemtype="http://schema.org/BlogPosting"  >
                        <header>
                            <div class="page-header">
                                <h1 itemprop="name" class="entry-title" ><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" rel="bookmark" itemprop="url" ><span itemprop="name"><?php the_title(); ?></span></a></h1>
                            </div>
                            <dl class="entry-meta">
                                <?php if( get_the_time('U') == get_the_modified_time('U') ) : ?>
                                    <dt><?php _e("Posted", "sigami"); //published ?></dt>
                                    <dd><time class="updated" datetime="<?php echo get_the_time('c'); ?>" itemprop="datePublished"><?php echo get_the_date(); ?></time>.</dd>
                                <?php else : ?>
                                    <dt><?php _e("Updated", "sigami"); ?></dt>
                                    <dd><time class="updated" datetime="<?php echo get_the_modified_time('c'); ?>" itemprop="dateModified"><?php echo get_the_modified_date(); ?></time></dd>
                                <?php endif; ?>
                                <dd><span class="author vcard"><?php _e('by', 'sigami'); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" ><span itemprop="author" rel="author" class="fn"><?php echo get_the_author(); ?></span></a></span></dd>
                                <dt>&nbsp;&amp;&nbsp;&nbsp;<?php _e("Filed under", "sigami"); ?></dt>
                                <dd itemprop="keywords"><?php the_category(', '); ?></dd>
                            </dl>
                        </header>
                        <div>
                            <?php the_post_thumbnail( 'featured', array( 'itemprop'=>'image', 'class'=>'thumbnail img-responsive') ); ?>
                        </div>
                        <div itemprop="description" class="entry-content" >
                            <?php
                            if( in_array(get_post_format(),array(
                                'gallery', // gallery of images
                                'link',    // quick link to other site
                                'image',   // an image
                                'quote',   // a quick quote
                                'status',  // a Facebook like status update
                                'video',   // video)
                                'audio',   // video)
                            ) ) || is_sticky($post->ID) ) {
                                the_content();
                            } else {
                                the_excerpt();
                            }
                            ?>
                        </div>
                        <footer>
                            <?php the_tags('<p class="tags"><span class="tags-title" >' . __("Tags","sigami") . ': </span>', ' ', '</p>'); ?>
                            <?php $comments = get_comments_number($post->ID); ?>
                            <?php if( $comments != 0) : ?>
                                <meta itemprop="interactionCount" content="UserComments:<?php echo $comments ?>"/>
                                <p><?php printf( _n( '%d Comment', '%d Comments', $comments, 'sigami' ), $comments ); ?></p>
                            <?php else : ?>
                                <meta itemprop="interactionCount" content="UserComments:0"/>
                            <?php endif; ?>
                        </footer>
                    </article>
					<?php endwhile; ?>
					<?php if (current_theme_supports( 'sigami-pagination' )) {  ?>
						<?php Sigami_Views::numeric_navi();  ?>
					<?php } else { ?>
                            <nav class="wp-prev-next">
                                <ul class="pager">
                                    <li class="previous"><?php next_posts_link( '<span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>'.__(' Older Entries', "sigami") ) ?></li>
                                    <li class="next"><?php previous_posts_link( __('Newer Entries ', "sigami") . '<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>') ?></li>
                                </ul>
                            </nav>
					<?php } ?>
					<?php else : ?>
                        <?php get_template_part('lib/parts/not-found'); ?>
					<?php endif; ?>
				</div>
				<?php get_sidebar();  ?>
			</div>
    </div>
<?php get_footer(); ?>