<?php
/**
 * Rodape do site.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="border-t border-line mt-16">
	<div class="w-full max-w-7xl mx-auto px-5 lg:px-10 py-8 flex flex-col lg:flex-row items-center lg:items-start justify-between gap-6 text-h6 text-muted">
		<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'footer-menu flex flex-col lg:flex-row gap-6 lg:gap-12 text-center lg:text-left',
				'fallback_cb'    => false,
				'depth'          => 3,
			)
		);
		?>
	</div>
</footer>
