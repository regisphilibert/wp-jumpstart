<div class="phi-container">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<!-- post -->
	<h2><?php the_title() ?></h3>
	<h6><?php the_date() ?></h6>
	<p>
		<?php the_content(); ?>
	</p>
	<?php endwhile; ?>
	<!-- post navigation -->
	<?php else: ?>
	<!-- no posts found -->
	<?php endif; ?>
</div>