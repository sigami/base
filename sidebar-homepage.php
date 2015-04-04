				<div id="sidebar2" class="col-sm-4" role="complementary">
				
					<?php if ( is_active_sidebar( 'home-page' ) ) : ?>

						<?php dynamic_sidebar( 'home-page' ); ?>

					<?php else : ?>

						<!-- This content shows up if there are no widgets defined in the backend. -->
						
						<div class="alert alert-message">
						
							<p><?php _e("Please activate some Widgets","sigami"); ?>.</p>
						
						</div>

					<?php endif; ?>

				</div>
