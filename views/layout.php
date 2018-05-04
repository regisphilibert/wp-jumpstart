<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<?php get_template_include('head'); ?>
	<body <?php body_class(); ?>>
	<?php get_template_include('header'); ?>
		<main>
			<?php echo $this->content(); ?>
		</main>
		<?php get_template_include('footer'); ?>
		<?php wp_footer(); ?>
	</body>
</html>