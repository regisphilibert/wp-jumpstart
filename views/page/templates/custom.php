<div class="phi-container">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<!-- post -->
	<h1>This is a custom page</h1>
	<p>
		<?php the_content(); ?>
	</p>
	<hr>
	<?php endwhile; ?>
	<!-- post navigation -->
	<?php else: ?>
	<!-- no posts found -->
	<?php endif; ?>
</div>
