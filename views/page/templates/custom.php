<div class="phi-container">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<!-- post -->
	<?php get_template_include('post-header', ['title'=>'bonjour']) ?>
	<h1>This is a custom page</h1>
		<?php ardump(get_post(1750));?>
		<?php ardump(['bnjour']) ?>
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
