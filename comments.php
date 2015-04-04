<?php
/*
The comments page for Bones
*/

// Do not delete these lines
  if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');

  if ( post_password_required() ) { ?>
  	<div class="alert alert-info"><?php _e("This post is password protected. Enter the password to view comments.","sigami"); ?></div>
  <?php
    return;
  }
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<?php if ( ! empty($comments_by_type['comment']) ) : ?>
	<h3 id="comments"><?php comments_number('<span>' . __("No","sigami") . '</span> ' . __("Responses","sigami") . '', '<span>' . __("One","sigami") . '</span> ' . __("Response","sigami") . '', '<span>%</span> ' . __("Responses","sigami") );?> <?php _e("to","sigami"); ?> &#8220;<?php the_title(); ?>&#8221;</h3>

	<nav id="comment-nav">
		<ul class="clearfix">
	  		<li><?php previous_comments_link( __("Older comments","sigami") ) ?></li>
	  		<li><?php next_comments_link( __("Newer comments","sigami") ) ?></li>
	 	</ul>
	</nav>
	
	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=wp_bootstrap_comments'); ?>
	</ol>
	
	<?php endif; ?>
	
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<h3 id="pings">Trackbacks/Pingbacks</h3>
		
		<ol class="pinglist">
			<?php wp_list_comments('type=pings&callback=list_pings'); ?>
		</ol>
	<?php endif; ?>
	
	<nav id="comment-nav">
		<ul class="clearfix">
	  		<li><?php previous_comments_link( __("Older comments","sigami") ) ?></li>
	  		<li><?php next_comments_link( __("Newer comments","sigami") ) ?></li>
		</ul>
	</nav>

	<?php if ( ! comments_open() ) : ?>
	<p class="alert alert-info"><?php _e("Comments are closed","sigami"); ?>.</p>
	<?php endif; ?>

<?php endif; ?>


<?php if ( comments_open() ) : ?>

    <section id="respond" class="respond-form">
        <?php
        $title_reply = (function_exists('is_product') && is_product()) ? __("Review this product",'sigami') : __("Leave a Reply",'sigami');
        $comment_field = '	<div class="form-group">
<label for="comment" class="sr-only">' .
            ((function_exists('is_product') && is_product()) ? __("Review",'sigami'): __("Reply",'sigami'))
            . '</label>'
            . ' <textarea class="form-control" role="textbox" aria-multiline="true" name="comment" id="comment" rows="8" placeholder="'
            . __("Your Comment Here...",'sigami')
            . '"></textarea>
</div>';
        comment_form(
            array('comment_notes_before' => '',
                'comment_field' => $comment_field,
                'title_reply' => $title_reply)
        );
        ?>
    </section>

<?php endif; // if you delete this the sky will fall on your head ?>
