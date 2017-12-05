<div class="phi-container">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<!-- post -->
	<h1>This is a single post</h1>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
	<h6><?php the_date() ?></h6>
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
