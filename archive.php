<?php get_header(); ?>
    <div class="container">
			<div id="content" class="row">
				<div id="main" class="col-sm-8" role="main">
					<div class="page-header">
                        <h1>
					<?php if (is_category()) { ?>
							<span><?php _e("Posts Categorized:", "sigami"); ?></span> <?php single_cat_title(); ?>
					<?php } elseif (is_tag()) { ?> <h1>
							<span><?php _e("Posts Tagged:", "sigami"); ?></span> <?php single_tag_title(); ?>
					<?php } elseif (is_author()) {
                        $current_author = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
                        ?>

							<span><?php _e("Posts By:", "sigami"); ?></span> <?php echo $current_author->display_name ; ?>
					<?php } elseif (is_day()) { ?>
							<span><?php _e("Daily Archives:", "sigami"); ?></span> <?php the_time('l, F j, Y'); ?>
					<?php } elseif (is_month()) { ?>
					    	<span><?php _e("Monthly Archives:", "sigami"); ?></span> <?php the_time('F Y'); ?>
					<?php } elseif (is_year()) { ?>
					    	<span><?php _e("Yearly Archives:", "sigami"); ?></span> <?php the_time('Y'); ?>
					<?php } ?>
                        </h1>
					</div>
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?> itemscope itemtype="http://schema.org/BlogPosting"  >
                            <header>
                                <div class="page-header">
                                    <h2 itemprop="name" class="entry-title" ><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>" rel="bookmark" itemprop="url" ><span itemprop="name"><?php the_title(); ?></span></a></h2>
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
                                    <dd itemprop="keywords">
                                        <?php
                                            if(get_post_type() == 'post'){
                                                the_category(', ');
                                            } else {
                                                // get post by post id
                                                $post_type = $post->post_type;
                                                $taxonomies = get_object_taxonomies( $post_type, 'objects' );
                                                $out = array();
                                                foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){
                                                    $terms = get_the_terms( $post->ID, $taxonomy_slug );
                                                    if ( !empty( $terms ) ) {
                                                        foreach ( $terms as $term ) {
                                                            $out[] =
                                                                '<a href="'
                                                                .    get_term_link( $term->slug, $taxonomy_slug ) .'">'
                                                                .    $term->name
                                                                . "</a>";
                                                        }
                                                    }
                                                }
                                                echo  implode(', ', $out );
                                            }
                                        ?>
                                    </dd>
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
                                        ) ) ) {
                                        the_content();
                                    } else {
                                        the_excerpt();
                                    }
                                ?>
                            </div>
                            <footer>
                                <?php the_tags('<p class="tags"><span class="tags-title" >' . __("Tags","sigami") . ': </span>', ' ', '</p>'); ?>
                                <?php if( $comments = get_comments_number($post->ID) != 0) : ?>
                                    <meta itemprop="interactionCount" content="UserComments:<?php echo $comments ?>"/>
                                    <p><?php printf( _n( '%d Comment', '%d Comments', $comments, 'sigami' ), $comments ); ?></p>
                                <?php else : ?>
                                    <meta itemprop="interactionCount" content="UserComments:0"/>
                                <?php endif; ?>
                            </footer>
                        </article>
					<?php endwhile; ?>
					<?php if (current_theme_supports( 'sigami-pagination' )) {  ?>
						<div class="text-center"><?php Sigami_Views::numeric_navi();  ?></div>
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