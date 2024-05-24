<div id="colophon" class="site-footer">

	<?php if ( is_active_sidebar( 'footer' ) ) : ?>
		<div class="widgets">
			<div class="container">
				<div class="row">
					<?php dynamic_sidebar( 'footer' ); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="copyright">Copyright &copy; <?php get_theme_mod('company'); ?> <?php echo date('Y'); ?> <?php _e( 'All rights reserved', 'layback' ); ?></div>

</div>

<?php wp_footer(); ?>

</body>
</html>