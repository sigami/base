<?php get_header(); ?>
    <div class="container">
        <div id="content" class="row">
            <main id="main" class="col-sm-12" role="main">
                <article id="page-not-found" class="jumbotron text-center">
                    <header>
                        <h1 class="entry-title"><?php _e("Epic 404 - Article Not Found", "sigami"); ?></h1>
                    </header>
                    <div class="entry-content">
                        <p class="lead"><?php _e("This is embarassing. We can't find what you were looking for.", "sigami"); ?></p>
                    </div>
                    <footer >
                        <?php get_search_form(); ?>
                    </footer>
                </article>
            </main>
        </div>
    </div>
<?php get_footer(); ?>