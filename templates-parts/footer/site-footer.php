<?php
/**
 * Rodape do site.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="border-t border-line mt-16">
	<div class="container py-8 flex flex-col lg:flex-row items-center justify-between gap-4 text-h6 text-muted">
		<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'flex gap-6',
				'fallback_cb'    => false,
				'depth'          => 1,
			)
		);
		?>
	</div>
</footer>
