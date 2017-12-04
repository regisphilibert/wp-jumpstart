<header>
    <div class="phi-container">
    	<a class="phi-Logo" href="/"><span>🍚</span> <span>phil</span> <small>(the Wordpress edition)</small></a>
		<?php 
		    $args = array(
		        'theme_location' => 'main-menu',
		        'menu' => '',
		        'container' => 'nav',
		        'container_class'=>'a1n-main-menu-container',
		        'container_id' => '',
		        'menu_class' => 'menu',
		        'menu_id' => '',
		        'echo' => true,
		        'fallback_cb' => 'wp_page_menu',
		        'before' => '',
		        'after' => '',
		        'link_before' => '',
		        'link_after' => '',
		        'items_wrap' => '<div id = "%1$s" class = "phi-MainNav">%3$s</div>',
		        'depth' => 0,
		        'walker' => new Phil_Nav_Walker
		    );

		    wp_nav_menu( $args );
		?>
    </div>
</header>